<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

require_once('include/timezone/timezones.php');

/**
 * Date/time conversion class
 *
 * DB format - Y-m-d H:i:s (2009-12-31 23:46:12), in GMT
 * Display format - user-defined, in user-supplied timezone
 *
 */
class TimeDate {
	/**
	 * DB date format
	 * @var string
	 */
	var $dbDayFormat = 'Y-m-d';
	/**
	 * DB time format
	 * @var string
	 */
	var $dbTimeFormat = 'H:i:s';
	/**
	 * DB date & time formats
	 * For convenience
	 * @var string
	 */
	var $dbDateTimeFormat = 'Y-m-d H:i:s';

	protected $supported_strings = array(
		'a' => '[ap]m',
		'A' => '[AP]M',
		'd' => '[0-9]{1,2}',
		'h' => '[0-9]{1,2}',
		'H' => '[0-9]{1,2}',
		'i' => '[0-9]{1,2}',
		'm' => '[0-9]{1,2}',
		'Y' => '[0-9]{4}',
		's' => '[0-9]{1,2}'
	);

	/**
	 * Map the tokens passed into this as a "format" string to
	 * PHP's internal date() format string values.
	 *
	 * @var array
	 * @access private
	 * @see build_format()
	 */
	private $time_token_map = array(
		'a' => 'a', // meridiem: am or pm
		'A' => 'A', // meridiem: AM or PM
		'd' => 'd', // days: 1 through 31
		'h' => 'h', // hours: 01 through 12
		'H' => 'H', // hours: 00 through 23
		'i' => 'i', // minutes: 00 through 59
		'm' => 'm', // month: 1 - 12
		'Y' => 'Y', // year: four digits
		's' => 's', // seconds
	);

    /**
     * The current timezone information for the current user
     * @var array
     */
    private $current_user_timezone = null;

    /**
     * The current user timezone adjustment
     * @var int
     */
    private $current_user_adjustment = null;
    /**
     * The target TZ for current user timezone adjustment
     * @var array
     */
    private $current_user_adjustment_tz = null;

    /**
     * Default midnight string in user format
     * @var string
     */
    private $default_midnight = null;

    /**
     * For testability - disallow using cache
     * @var bool
     */
    public $allow_cache = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        // avoids a potential E_STRICT warning when using any date function
        if ( function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get'))
            date_default_timezone_set(@date_default_timezone_get());
    }

	/**
	 * Returns the current users timezone info or another user;
	 *
	 * @param User $user user object for which you want to display, null for current user
	 * @return Array of timezone info
	 */
	public function getUserTimeZone($user = null){
		global $timezones, $current_user;
		$usertimezone = array();
		if(empty($user) || (!empty($user->id) && $user->id == $current_user->id)) {
			if(isset($this->current_user_timezone)) return $this->current_user_timezone; // current user, return saved timezone info
			$user = $current_user;
		}

		if(isset($user))
		{
			if($usertimezone = $user->getPreference('timezone')) {
					if(empty($timezones[$usertimezone])) {
						$GLOBALS['log']->fatal('TIMEZONE:NOT DEFINED-'. $usertimezone);
						$usertimezone = array();
					} else {
						$usertimezone = $timezones[$usertimezone];
					}
			}
		}

		if(!empty($user->id) && $user->id == $current_user->id) $this->current_user_timezone = $usertimezone; // save current_user
		return $usertimezone;
	}

	/**
	 * @deprecated for public use
	 * function adjustmentForUserTimeZone()
	 * this returns the adjustment for a user against the server time
	 *
	 * @param array $timezone_to_adjust pass in a timezone to adjust for
	 * @return integer number of minutes to adjust a time by to get the appropriate time for the user
	 */
	public function adjustmentForUserTimeZone($timezone_to_adjust = null){
		if(isset($this->current_user_adjustment) && $this->current_user_adjustment_tz == $timezone_to_adjust){
			return $this->current_user_adjustment;
		}

		$adjustment = 0;
		$this->current_user_adjustment_tz = $timezone_to_adjust;

		if(empty($timezone_to_adjust)) {
			$timezone = $this->getUserTimeZone();
		} else {
			$timezone = $timezone_to_adjust;
		}
		if(empty($timezone)) {
			return $adjustment;
		}

		$server_offset = date('Z')/60;
		$server_in_ds = date('I');
		$user_in_ds = $this->inDST(date('Y-m-d H:i:s'), $timezone);
		$user_offset = $timezone['gmtOffset'] ;

		//compensate for ds for user
		if($user_in_ds) {
			$user_offset += 60;
		}

		//either both + or -
		$adjustment += $server_offset - $user_offset;
		if(empty($timezone_to_adjust)) $this->current_user_adjustment = $adjustment; // save current_user adj

		return $adjustment;
	}

	/**
     * @deprecated for public use
	 * function getWeekDayName($indexOfDay)
	 * Returns a days name
	 *
	 * @param INT(WEEKDAY INDEX) $indexOfDay
	 * @return STRING representing the given weekday
	 */
	function getWeekDayName($indexOfDay){
		static $dow = array ( 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' );
		return $dow[$indexOfDay];
	}
	/**
     * @deprecated for public use
	 * function getMonthName($indexMonth)
	 * Returns a Months Name
	 *
	 * @param INT(MONTH INDEX) $indexMonth
	 * @return STRING representation of the month
	 */
	function getMonthName($indexMonth){
		static $months = array ( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
		return $months[$indexMonth];
	}

	/**
     * @deprecated for public use
	 * function getDateFromRules($year, $startMonth, $startDate, $weekday, $startTime )
	 * Converts the rules for a timezones dst into a string representation of a date for the given year
	 *
	 * @param STRING(YEAR) $year
	 * @param INT(MONTH INDEX) $startMonth
	 * @param INT(DATE INDEX) $startDate
	 * @param INT(WEEKDAY INDEX) $weekday
	 * @param INT(START TIME IN SECONDS) $startTime
	 * @return unknown
	 */
	function getDateFromRules($year, $startMonth, $startDate, $weekday, $startTime ){
		if($weekday < 0)return date( 'Y-m-d H:i:s', strtotime("$year-$startMonth-$startDate") + $startTime);
		$dayname = self::getWeekDayName($weekday);
		if($startDate > 0)$startMonth--;
		$month = self::getMonthName($startMonth);
		$startWeek = floor($startDate/7);
		//echo "$startWeek week $dayname - $month 1, $year<br>";
		return date( 'Y-m-d H:i:s', strtotime( "$startWeek week $dayname", strtotime( "$month 1, $year" ) ) + $startTime );

	}

	/**
	 * @deprecated for public use
	 * 	function getDSTRange($year, $zone)
	 *
	 * returns the start and end date for dst for a given timezone and year or false if that zone doesn't support dst
	 *
	 * @param STRING(Year e.g. 2005) $year
	 * @param ARRAY (TIME ZONE INFO) $zone
	 * @return ARRAY OF DATE REPRESENTING THE START AND END OF DST or FALSE if the zone doesn't support dst
	 */
	function getDSTRange($year, $zone){
		$range = array();
		if(empty($zone['dstOffset'])){
			return false;
		}

		$range['start'] = $this->getDateFromRules($year, $zone['dstMonth'], $zone['dstStartday'], $zone['dstWeekday'],  $zone['dstStartTimeSec']);
		$range['end'] = $this->getDateFromRules($year, $zone['stdMonth'], $zone['stdStartday'], $zone['stdWeekday'],  $zone['stdStartTimeSec']);
		return $range;
	}

     /**
      * @deprecated for public use
      *
      * Is the date in DST or not
      * @param $date
      * @param $zone
      */
	function inDST($date, $zone){
		$datetime = explode(' ', $date);
		$dateSplit = explode('-', $datetime[0]);
		if(empty($dateSplit[2]))return false;
		$dstRange = $this->getDSTRange($dateSplit[0], $zone);
		if(!$dstRange){
			return false;
		}
		$datestamp = strtotime($date);
		$startstamp = strtotime($dstRange['start']);
		$endstamp = strtotime($dstRange['end']);
		if((($datestamp >= $startstamp  || $datestamp < $endstamp) && $startstamp > $endstamp)
			|| ($datestamp >= $startstamp && $datestamp < $endstamp)
		){
			return true;
		}
		return false;
	}

	/**
	 * Create regexp from datetime format
	 * @param string $format
	 * @return string Regular expression string
	 */
	function get_regular_expression($format) {
		$newFormat = '';
		$regPositions = array();
		$ignoreNextChar = false;
		$count = 1;
		$format_characters = str_split($format, 1);
		foreach ($format_characters as $char) {
			if (!$ignoreNextChar && isset($this->supported_strings[$char])) {
				$newFormat.= '('.$this->supported_strings[$char].')';
				$regPositions[$char] = $count;
				$count++;
			} else {
				$ignoreNextChar = false;

				$newFormat.= $char;

			}
			if ($char == "\\") {
				$ignoreNextChar = true;
			}
		}

		return array('format'=>$newFormat, 'positions'=>$regPositions);

	}

	/**
	 * Verify if the date string conforms to a format
	 *
	 * @param string $date
	 * @param string $format Format to check
	 * @param string $toformat
	 * @return bool Is the date ok?
	 */
	public function check_matching_format($date, $format, $toformat = '') {
		$regs = array();
		$startreg = $this->get_regular_expression($format);
		if (!empty($toformat)) {
			$otherreg = $this->get_regular_expression($toformat);
			//if the other format has the same regular expression then maybe it is shifting month and day position or something similar let it go for formating
			if ($startreg['format'] == $otherreg['format']) {
				return false;
			}
		}

		 preg_match('@'.$startreg['format'].'@', $date, $regs);
		if (empty($regs)) {
			return false;
		}
		return true;
	}

	/**
	 * @deprecated for public use
	 * Build a true PHP format string from a user supplied format string
	 *
	 * @param string $format
	 * @return string
	 * @access private
	 * @see $time_token_map
	 */
	function build_format($format)
	{
		$format = str_split($format, 1);
		$return = '';
		foreach ($format as $char) {
			$return .= (isset($this->time_token_map[$char])) ?
				$this->time_token_map[$char] :
				$char;
		}
		return $return;
	}

    /**
     * Convert date from one format to another
     *
     * @param string $date
     * @param string $from
     * @param string $to
     * @return string
     */
	function swap_formats($date, $startFormat, $endFormat) {
		$startreg = $this->get_regular_expression($startFormat);
		preg_match('@'.$startreg['format'].'@', $date, $regs);
		$newDate = $endFormat;
		//handle 12 to 24 hour conversion
		if (isset($startreg['positions']['h']) && !isset($startreg['positions']['H']) && !empty($regs[$startreg['positions']['h']]) && $regs[$startreg['positions']['h']] !== '' && strpos($endFormat, 'H') > -1) {
			$startreg['positions']['H'] = sizeof($startreg['positions']) + 1;
			$regs[$startreg['positions']['H']] = $regs[$startreg['positions']['h']];
			if ((isset($startreg['positions']['A']) && isset($regs[$startreg['positions']['A']]) && $regs[$startreg['positions']['A']] == 'PM') || (isset($startreg['positions']['a']) && isset($regs[$startreg['positions']['a']]) && $regs[$startreg['positions']['a']] == 'pm')) {
				if ($regs[$startreg['positions']['h']] != 12) {
					$regs[$startreg['positions']['H']] = $regs[$startreg['positions']['h']] + 12;
				}
			}
			if ((isset($startreg['positions']['A']) && isset($regs[$startreg['positions']['A']])&& $regs[$startreg['positions']['A']] == 'AM') || (isset($startreg['positions']['a']) && isset($regs[$startreg['positions']['a']]) && $regs[$startreg['positions']['a']] == 'am')) {
				if ($regs[$startreg['positions']['h']] == 12) {
					$regs[$startreg['positions']['H']] = 0;
				}
			}
		}
		if (!empty($startreg['positions']['H']) && !empty($regs[$startreg['positions']['H']]) && !isset($startreg['positions']['h']) && strpos($endFormat, 'h') > -1) {
			$startreg['positions']['h'] = sizeof($startreg['positions']) + 1;
			$regs[$startreg['positions']['h']] = $regs[$startreg['positions']['H']];
			if (!isset($startreg['positions']['A'])) {
				$startreg['positions']['A'] = sizeof($startreg['positions']) + 1;
				$regs[$startreg['positions']['A']] = 'AM';
			}
			if (!isset($startreg['positions']['a'])) {
				$startreg['positions']['a'] = sizeof($startreg['positions']) + 1;
				$regs[$startreg['positions']['a']] = 'am';
			}
			if ($regs[$startreg['positions']['H']] > 11) {
				$regs[$startreg['positions']['h']] = $regs[$startreg['positions']['H']] - 12;
				if ($regs[$startreg['positions']['h']] == 0) {
					$regs[$startreg['positions']['h']] = 12;
				}
				$regs[$startreg['positions']['a']] = 'pm';
				$regs[$startreg['positions']['A']] = 'PM';
			}
			if ($regs[$startreg['positions']['H']] == 0) {
				$regs[$startreg['positions']['h']] = 12;
				$regs[$startreg['positions']['a']] = 'am';
				$regs[$startreg['positions']['A']] = 'AM';
			}
		}
		if (!empty($startreg['positions']['h'])) {
			if (!isset($regs[$startreg['positions']['h']])) {
				$regs[$startreg['positions']['h']] = '12';
			} else if (strlen($regs[$startreg['positions']['h']]) < 2)
				$regs[$startreg['positions']['h']] = '0'.$regs[$startreg['positions']['h']];
		}
		if (!empty($startreg['positions']['H'])) {
			// if no hour is set or it is equal to 0, set it explicitly to "00"
			if (empty($regs[$startreg['positions']['H']])) {
				$regs[$startreg['positions']['H']] = '00';
			} else if (strlen($regs[$startreg['positions']['H']]) < 2)
				$regs[$startreg['positions']['H']] = '0'.$regs[$startreg['positions']['H']];
		}
		if (!empty($startreg['positions']['d'])) {
			if (!isset($regs[$startreg['positions']['d']])) {
				$regs[$startreg['positions']['d']] = '01';
			} else if (strlen($regs[$startreg['positions']['d']]) < 2)
				$regs[$startreg['positions']['d']] = '0'.$regs[$startreg['positions']['d']];
		}
		if (!empty($startreg['positions']['i'])) {
			// if no minute is set or it is equal to 0, set it explicitly to "00"
			if (empty($regs[$startreg['positions']['i']])) {
				$regs[$startreg['positions']['i']] = '00';
			} else if (strlen($regs[$startreg['positions']['i']]) < 2)
				$regs[$startreg['positions']['i']] = '0'.$regs[$startreg['positions']['i']];
		} else {
			$startreg['positions']['i'] = count($startreg['positions']) + 1;
			$regs[$startreg['positions']['i']] = '00';

		}
		if (!empty($startreg['positions']['m'])) {
			if (!isset($regs[$startreg['positions']['m']])) {
				$regs[$startreg['positions']['m']] = '01';
			} elseif(strlen($regs[$startreg['positions']['m']]) < 2)
				$regs[$startreg['positions']['m']] = '0'.$regs[$startreg['positions']['m']];
		}
		if (!empty($startreg['positions']['Y'])) {
			if (!isset($regs[$startreg['positions']['Y']])) {
				$regs[$startreg['positions']['Y']] = '2000';
			}
		}
		if (!empty($startreg['positions']['s'])) {
			if (!isset($regs[$startreg['positions']['s']])) {
				$regs[$startreg['positions']['s']] = '00';
			} else if (strlen($regs[$startreg['positions']['s']]) < 2)
				$regs[$startreg['positions']['s']] = '0'.$regs[$startreg['positions']['s']];
		} else {
			$startreg['positions']['s'] = sizeof($startreg['positions']) + 1;
			$regs[$startreg['positions']['s']] = '00';
		}
		foreach($startreg['positions'] as $key=>$val) {
			if (isset($regs[$val])) {
				$newDate = str_replace($key, $regs[$val], $newDate);
			}
		}
		return $newDate;

	}
	/**
	 * Converts DB time string to local time string
	 *
	 * TZ conversion depends on offset parameter
	 *
	 * @param string $date Time in DB format
	 * @param bool $meridiem
	 * @param bool $offset Perform TZ conversion?
	 * @return string Time in user-defined format
	 */
	function to_display_time($date, $meridiem = true, $offset = true) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		if ($offset) {
			$date = $this->handle_offset($date, $this->get_db_date_time_format(), true);
		}
		return $this->to_display($date, $this->dbTimeFormat, $this->get_time_format($meridiem));
	}

	/**
	 * Converts DB date string to local date string
	 *
	 * TZ conversion depens on offset parameter
	 *
	 * @param string $date Date in DB format
	 * @param bool $use_offset Perform TZ conversion?
	 * @return string Date in user-defined format
	 */
	function to_display_date($date, $use_offset = true) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		if ($use_offset)
			 $date = $this->handle_offset($date, $this->get_db_date_time_format(), true);

		return $this->to_display($date, $this->dbDayFormat, $this->get_date_format());
	}

	/**
	 * Convert DB datetime to local datetime
	 *
	 * TZ conversion is controlled by $offset
	 *
	 * @param string $date Original date in DB format
	 * @param bool $meridiem
	 * @param bool $offset Perform TZ conversion?
	 * @param User $user User owning the conversion formats
	 * @return string Date in display format
	 */
	function to_display_date_time($date, $meridiem = true, $offset = true, $user = null) {
		$date = trim($date);

		if (empty($date)) {
			return $date;
		}

		if($this->allow_cache) {
			$args = array(
			    'time' => $date,
			    'meridiem' => $meridiem,
			    'offset' => $offset,
			    'user' => is_null($user)?null:$user->id,
			);

			// todo use __METHOD__ once PHP5 minimum verison is required
			$cache_key = md5('TimeDate::to_display_date_time_' . serialize($args));
			$cached_value = sugar_cache_retrieve($cache_key);
			if (!is_null($cached_value)) {
			    return $cached_value;
			}
		}

		if ($offset) {
			$date = $this->handle_offset($date, $this->get_db_date_time_format(), true, $user);
		}

		$return_value = $this->to_display($date, $this->get_db_date_time_format(), $this->get_date_time_format($meridiem, $user));

		if($this->allow_cache) {
			sugar_cache_put($cache_key, $return_value);
		}
		return $return_value;
	}

	/**
	 * Convert date from format to format
	 *
	 * No TZ conversion is performed!
	 *
	 * @param string $date
	 * @param string $fromformat Source format
	 * @param string $toformat Target format
	 * @return string Converted date
	 */
	function to_display($date, $fromformat, $toformat) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		return $this->swap_formats($date, $fromformat, $toformat);
	}

	/**
	 * Convert date from local datetime to GMT-based DB datetime
	 *
	 * Includes TZ conversion.
	 *
	 * @param string $date
	 * @return string Datetime in DB format
	 */
	public function to_db($date) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		if (strlen($date) <= 10) {
			$date = $this->merge_date_time($date, $this->get_default_midnight());
		}

		$date = $this->swap_formats($date, $this->get_date_time_format(), $this->get_db_date_time_format());
		return $this->handle_offset($date, $this->get_db_date_time_format(), false, $GLOBALS['current_user']);
	}


	/*
	 * @todo This should return the raw text to be included within a <script> tag.
	 *	   Having this display it's own <script> keeps it from being able to be embedded
	 *	   in another Javascript file to allow for better caching
	 */
	/**
	 * Get Javascript variables setup for user date format validation
	 * @todo: move to separate utility class?
	 *
	 * @return string JS code
	 */
	function get_javascript_validation() {
		$cal_date_format = $this->get_cal_date_format();
		$timereg = $this->get_regular_expression($this->get_time_format());
		$datereg = $this->get_regular_expression($this->get_date_format());
		$date_pos = '';
		foreach($datereg['positions'] as $type=>$pos) {
			if (empty($date_pos)) {
				$date_pos.= "'$type': $pos";
			} else {
				$date_pos.= ",'$type': $pos";
			}

		}
		$date_pos = '{'.$date_pos.'}';
		if (preg_match('/\)([^\d])\(/', $timereg['format'], $match)) {
			$time_separator = $match[1];
		} else {
			$time_separator = ":";
		}
		$hour_offset = $this->get_hour_offset() * 60 * 60;

        // Add in the number formatting styles here as well, we have been handling this with individual modules.
        require_once('modules/Currencies/Currency.php');
        list($num_grp_sep, $dec_sep) = get_number_seperators();

		$the_script = "<script type=\"text/javascript\">\n"
			."\tvar time_reg_format = '".$timereg['format']."';\n"
			."\tvar date_reg_format = '".$datereg['format']."';\n"
			."\tvar date_reg_positions = $date_pos;\n"
			."\tvar time_separator = '$time_separator';\n"
			."\tvar cal_date_format = '$cal_date_format';\n"
			."\tvar time_offset = $hour_offset;\n"
            ."\tvar num_grp_sep = '$num_grp_sep';\n"
            ."\tvar dec_sep = '$dec_sep';\n"
			."</script>";

		return $the_script;

	}

	/**
	 * Convert local datetime to DB date
	 *
	 * TZ conversion depends on $use_offset. If false, only format conversion is performed.
	 *
	 * @param string $date Local date
	 * @param bool $use_offset Should time and TZ be taken into account?
	 * @return string Date in DB format
	 */
	public function to_db_date($date, $use_offset = true) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
        if (!$use_offset && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$date) ) {
            // Already in system format
            return $date;
        }
		if ($use_offset) {
			$date = $this->to_db($date);
			$date = $this->swap_formats($date, $this->dbDayFormat, $this->dbDayFormat);
		} else {
			$date = $this->swap_formats($date, $this->get_date_format(), $this->dbDayFormat);
		}

		return $date;
	}

	/**
	 * Convert local datetime to DB time
	 *
	 * TZ conversion depends on $use_offset. If false, only format conversion is performed.
	 *
	 * @param string $date Local date
	 * @param bool $use_offset Should time and TZ be taken into account?
	 * @return string Time in DB format
	 */
	public function to_db_time($date, $use_offset = true) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		if ($use_offset){
			$date =$this->to_db($date, $use_offset);
		 	$date = $this->swap_formats($date, $this->get_db_date_time_format(), $this->dbTimeFormat);
		}else{
		 	$date = $this->swap_formats($date, $this->get_time_format(), $this->dbTimeFormat);
		}
		return $date;


	}

	/**
	 * Takes a Date & Time value in local format and converts them to DB format
	 * No TZ conversion!
	 *
	 * @param string $date
	 * @param string $time
	 * @return array Date & time in DB format
	 **/
	public function to_db_date_time($date, $time) {
		global $current_user;
		if(is_object($current_user)) {
			$timeFormat = $current_user->getUserDateTimePreferences();
		} else {
			$timeFormat['date'] = $this->dbDayFormat;
			$timeFormat['time'] = $this->dbTimeFormat;
		}
		$dt = '';
		$newDate = '';
		$retDateTime = array();

		// concat: ('.' breaks strtotime())
		$time = str_replace('.',':',$time);
		$dt = $date.' '.$time;
        if ( preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/',$dt) ) {
            // Already in system time format
            return array($date, $time);
        }
		$newDate = $this->swap_formats($dt, $timeFormat['date'].' '.$timeFormat['time'] , $this->dbDateTimeFormat);
		return $this->split_date_time($newDate);
	}

	/**
     * @deprecated for public use
	 * Get DST offset between user & event
	 * @param $user_in_dst
	 * @param $event_in_dst
	 */
	function getUserEventOffset($user_in_dst, $event_in_dst){
		if($user_in_dst && !$event_in_dst ){
			return -3600;
		}
		if(!$user_in_dst && $event_in_dst ){
			return 3600;
		}
		return 0;
	}

/**************************************************************
U	S	E	Time	GMT	Delta Server Client	U/E	Delta Server GMT
USER IN LA and server in NY
D	D	D	12	19	-3	0	-4
D	D	S	12	20	-3	-1	-4
D	S	D	12	19	-2	0	-5
D	S	S	12	20	-2	-1	-5
S	D	D	12	19	-4	1	-4
S	D	S	12	20	-4	0	-4
S	S	D	12	19	-3	1	-5
S	S	S	12	20	-3	0	-5


User in LA and server in gmt there are no DST for server
D	S	D	12	19	-7	0	0
D	S	S	12	20	-7	-1	0

S	S	D	12	19	-8	1	0
S	S	S	12	20	-8	0	0

***************************************************************/

	/**
	 * handles offset values for Timezones and DST
	 * @param	$date	     string		date/time formatted in user's selected format
	 * @param	$format	     string		destination format value as passed to PHP's date() funtion
	 * @param	$to		     boolean
	 * @param	$user	     object		user object from which Timezone and DST
     * @param	$usetimezone string		timezone name as it appears in timezones.php values will be derived
	 * @return 	 string		date formatted and adjusted for TZ and DST
	 */
	function handle_offset($date, $format, $to = true, $user = null, $usetimezone = null) {
		global $sugar_config;
		$date = trim($date);
		// Samir Gandhi
		// This has been commented out because it is going through the wrong code path
		// Email module was broken and thats why its commented
		//if($this->use_old_gmt()){
			//return $this->handle_offset_depricated($date, $format, $to);
		//}
		if (empty($date)) {
			return $date;
		}
		if($this->allow_cache) {
			$args = array(
			    'date' => $date,
			    'format' => $format,
			    'to' => $to,
			    'user' => is_null($user)?null:$user->id,
			    'usetimezone' => $usetimezone,
			);
			$cache_key = md5('TimeDate::handle_offset_' . serialize($args));
			$cached_result = sugar_cache_retrieve($cache_key);
			if (!is_null($cached_result)) {
			    return $cached_result;
			}
		}
		if (strtotime($date) == -1) {
			return $date;
		}
		$deltaServerGMT = date('Z');

		if ( !empty($usetimezone) )
			$timezone = $GLOBALS['timezones'][$usetimezone];
		else
			$timezone = $this->getUserTimeZone($user);
		$deltaServerUser = $this->get_hour_offset($to, $timezone);
		$event_in_ds = $this->inDST($date,$timezone );
		$user_in_ds = $this->inDST(date('Y-m-d H:i:s'),$timezone );
		$server_in_ds = date('I');
		$ue = $this->getUserEventOffset($user_in_ds, $event_in_ds);
		$zone = 1;
		if (!$to) {
			$zone = -1;
		}
		$result = date($format, strtotime($date) + $deltaServerUser * 3600 + ($ue + $deltaServerGMT) * $zone);
		if($this->allow_cache) {
			sugar_cache_put($cache_key, $result);
		}
		return $result;
	}

	/**
     * @deprecated for public use
	 */
	function use_old_gmt()
	{
		if(isset($_SESSION['GMTO'])){
			return $_SESSION['GMTO'];
		}
		$db = DBManagerFactory::getInstance();
		$fix_name = 'DST Fix';
		$result =$db->query("Select * from  versions  where  name='$fix_name'");
		$valid = $db->fetchByAssoc($result);
		if($valid){
			$_SESSION['GMTO'] = false;
		}else{
			$_SESSION['GMTO'] = true;
		}
		return $_SESSION['GMTO'];
	}

	/**
     * @deprecated for public use
	 *This function is depricated don't use it. It is only for backwards compatibility until the admin runs the upgrade script
	 *
	 * @param unknown_type $date
	 * @param unknown_type $format
	 * @param unknown_type $to
	 * @return unknown
	 */
	private function handle_offset_depricated($date, $format, $to = true)
	{
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		if (strtotime($date) == -1) {
			return $date;
		}
		$zone = date('Z');
		if (!$to) {
			$zone *= -1;
		}
		return date($format, strtotime($date) + $this->get_hour_offset($to) * 60 * 60 + $zone);
	}

	/**
	 * 	this method will take an input $date variable (expecting Y-m-d format)
	 *	and get the GMT equivalent - with an hour-level granularity :
	 *	return the max value of a given locale's
	 *	date+time in GMT metrics (i.e., if in PDT, "2005-01-01 23:59:59" would be
	 *	"2005-01-02 06:59:59" in GMT metrics)
	 */
	function handleOffsetMax($date, $format = '', $to = true)
	{
		global $current_user;
		$gmtDateTime = array($date); // for errors
		/* check for bad date formatting */
		$date = trim($date);

		if (empty($date)) {
			return $gmtDateTime;
		}

		if (strtotime($date) == -1) {
			return $gmtDateTime;
		}

		/*	cn: passed $date var will be a "MAX" value, which we need to return
			as a GMT date/time pair to provide for hour-level granularity */
		/* this ridiculousness b/c PHP returns current time when passing "today"
			or "tomorrow" as strtotime() args */
		$dateNoTime = date('Y-m-d', strtotime($date));

		/* handle timezone and daylight savings */
		$dateWithTimeMin = $dateNoTime.' 00:00:00';
		$dateWithTimeMax = $dateNoTime.' 23:59:59';

		$offsetDateMin = $this->handle_offset($dateWithTimeMin, $this->dbDateTimeFormat, false);
		$offsetDateMax = $this->handle_offset($dateWithTimeMax, $this->dbDateTimeFormat, false);


		$exOffsetDateMax = $this->split_date_time($offsetDateMax);
		$gmtDateTime['date'] = $exOffsetDateMax[0];
		$gmtDateTime['time'] = $exOffsetDateMax[1];
		$gmtDateTime['min'] = $offsetDateMin;
		$gmtDateTime['max'] = $offsetDateMax;

		return $gmtDateTime;
	}


	/**
	 * Get current GMT datetime in DB format
	 * @return string
	 */
	function get_gmt_db_datetime() {
		return gmdate($this->get_db_date_time_format());
	}

	/**
	 * Get current GMT date in DB format
	 * @return string
	 */
	function get_gmt_db_date() {
		return gmdate($this->get_db_date_format());
	}

	/*
	 * @deprecated for public use
	 * Convert time in strtotime format into Y-m-d H:i:s format
	 */
	function convert_to_gmt_datetime($olddatetime) {
		if (!empty($olddatetime)) {
			return date('Y-m-d H:i:s', strtotime($olddatetime) - date('Z'));
		}
	}

	/**
	 * makes one datetime string from date string and time string
	 *
	 * @param string $date
	 * @param string $time
	 * @return string Datetime string
	 */
	function merge_date_time($date, $time) {
		return $date.' '.$time;
	}

	/**
	 * Merge time without am/pm with am/pm string
	 *
	 * @param string $date
	 * @param string $format User time format
	 * @param string $mer
	 * @return string
	 */
	function merge_time_meridiem($date, $format, $mer) {
		$date = trim($date);
		if (empty($date)) {
			return $date;
		}
		$fakeMerFormat = str_replace(array('a', 'A'), array('!@!', '!@!'), $format);
		$noMerFormat = str_replace(array('a', 'A'), array('', ''), $format);
		$newDate = $this->swap_formats($date, $noMerFormat, $fakeMerFormat);
		return str_replace('!@!', $mer, $newDate);
	}
	
	/**
	 * Returns the time portion of a datetime string
	 *
	 * @param string $datetime
	 * @return string
	 */
	public function getTimePart($datetime)
	{
	    return trim(array_pop($this->split_date_time($datetime)));
	}
	
	/**
	 * Returns the date portion of a datetime string
	 *
	 * @param string $datetime
	 * @return string
	 */
	public function getDatePart($datetime)
	{
	    return trim(array_shift($this->split_date_time($datetime)));
	}

	/**
	 * @deprecated for public use
	 * AMPMMenu
	 * This method renders a <select> HTML form element based on the
	 * user's time format preferences, with give date's value highlighted.
	 *
	 * If user's prefs have no AM/PM string, returns empty string.
	 *
	 * @todo: There is hardcoded HTML in here that does not allow for localization
	 * of the AM/PM am/pm Strings in this drop down menu.  Also, perhaps
	 * change to the substr_count function calls to strpos
	 * @todo: move to separate utility class?
	 *
	 * @param string $prefix Prefix for SELECT
	 * @param string $date Date in display format
	 * @param string $attrs Additional attributes for SELECT
	 * @return string SELECT HTML
	 */
	function AMPMMenu($prefix, $date, $attrs = '') {

		if (substr_count($this->get_time_format(), 'a') == 0 && substr_count($this->get_time_format(), 'A') == 0) {
			return '';
		}
		$menu = "<select name='".$prefix."meridiem' ".$attrs.">";

		if (strpos($this->get_time_format(), 'a') > -1) {

			if (substr_count($date, 'am') > 0)
				$menu.= "<option value='am' selected>am";
			else
				$menu.= "<option value='am'>am";
			if (substr_count($date, 'pm') > 0)
				$menu.= "<option value='pm' selected>pm";
			else
				$menu.= "<option value='pm'>pm";

		} else {

			if (substr_count($date, 'AM') > 0)
				$menu.= "<option value='AM' selected>AM";
			else
				$menu.= "<option value='AM'>AM";
			if (substr_count($date, 'PM') > 0) {
				$menu.= "<option value='PM' selected>PM";
			} else
				$menu.= "<option value='PM'>PM";

		}

		return $menu.'</select>';
	}

	/**
     * @deprecated for public use
	 * Get timezone offset in hours between server and timezone
	 *
	 * @param bool $to To this timezone or from this timezone?
	 * @param
	 * @return float
	 */
	function get_hour_offset($to = true, $timezone = null) {
		$timeDelta = $this->adjustmentForUserTimeZone($timezone) /60.0;
		if ($to) {
			return -1.0 * $timeDelta;
		}
		return 1.0 * $timeDelta;
	}

	/**
	 * Get user-defined time format
	 *
	 * @param bool $meridiem Should we allow AM/PM?
	 * @param User $user
	 * @return string
	 */
	function get_time_format($meridiem = true, $user = null) {
		global $current_user;
		global $sugar_config;

		if(empty($user)) $user = $current_user;

		if ($user instanceof User && $user->getPreference('timef')) {
			$timeFormat = $user->getPreference('timef');
		} else {
			$timeFormat = $sugar_config['default_time_format'];
		}
		if (!$meridiem) {
			$timeFormat = str_replace(array('a', 'A'), array('', ''), $timeFormat);
		}
		return $timeFormat;
	}

	/**
	 * Get user-defined date format
	 *
	 * @param User $user
	 * @return string
	 */
	public function get_date_format($user = null) {
		global $current_user;
		global $sugar_config;

		if(empty($user)) $user = $current_user;

		if ($user instanceof User && $user->getPreference('datef')) {
			return $user->getPreference('datef');
		}
		if(!empty($sugar_config['default_date_format'])){
			return $sugar_config['default_date_format'];
		}else{
			return '';
		}
	}

	/**
	 * Get user-defined datetime format
	 *
	 * @param bool $meridiem Should we allow AM/PM?
	 * @param User $user
	 * @return string
	 */
	public function get_date_time_format($meridiem = true, $user = null) {
		return $this->merge_date_time($this->get_date_format($user), $this->get_time_format($meridiem, $user));
	}

	public function get_db_date_time_format() {
		return $this->merge_date_time($this->dbDayFormat, $this->dbTimeFormat);
	}

	public function get_db_date_format()
	{
		return $this->dbDayFormat;
	}

	public function get_db_time_format()
	{
		return $this->dbTimeFormat;
	}

	/**
	 * Get user date format it strftime terms
	 */
	function get_cal_date_format() {
		return str_replace(
			array('Y',  'm',  'd'),
			array('%Y', '%m', '%d'),
			$this->get_date_format());
	}

	/**
	 * Get user time format it strftime terms
	 */
	function get_cal_time_format() {
		return str_replace(
			array('a',  'A',  'h',  'H',  'i',  's'),
			array('%P', '%p', '%I', '%H', '%M', '%S'),
			$this->get_time_format());
	}

	/**
	 * Get user date & time format it strftime terms
	 */
	function get_cal_date_time_format() {
		return $this->merge_date_time($this->get_cal_date_format(), $this->get_cal_time_format());
	}

	/**
	 * Return current date format for user display
	 *
	 * Displays format as an example for the user (i.e. something like dd/mm/yyyy)
	 */
	function get_user_date_format() {
		return str_replace(
			array('Y',    'm',  'd'),
			array('yyyy', 'mm', 'dd'),
			$this->get_date_format());
	}

	/**
	 * Return current time format for user display (e.g. as example under select box)
	 * FIXME: should we be using current format as get_user_date_format() or config?
	 */
	function get_user_time_format() {
		global $sugar_config;
		$time_pref = $this->get_time_format();

		if(!empty($time_pref) && !empty($sugar_config['time_formats'][$time_pref])) {
		   return $sugar_config['time_formats'][$time_pref];
		}

		return '23:00'; //default
		/*
		// Commented out by Collin (doesn't seem to work properly)
		return $this->to_display_time('23:00:00', true, false);
		*/
	}

	/**
	 * @deprecated for public use
	 * @todo: move to separate utility class?
	 */
	function get_microtime_string() {
		return sugar_microtime();
	}

	/**
	 * Get midnight (start of the day) in local time format
	 *
	 * @param bool $refresh Should cached value be discarded?
	 * @return Time string
	 */
	function get_default_midnight($refresh = false)
	{
		if (is_null($this->default_midnight) || !$this->allow_cache || $refresh) {
			$time_mapping = array(
				'H' => '00',
				'h' => '12',
				'i' => '00',
				's' => '00',
				'a' => 'am',
				'A' => 'AM',
			);
			$this->default_midnight = str_replace(
				array_keys($time_mapping),
				$time_mapping,
				$this->get_time_format()
			);
		}
		return $this->default_midnight;
	}

	/*
	 * Splits datetime string into date & time parts
	 * @param string $date Datetime string
	 * @return array date,time
	 */
	protected function split_date_time($date)
	{
		return explode(' ', $date);
	}

   /**
     * Returns start and end of a certain local date in GMT
     * Example: for May 19 in PDT start would be 2010-05-19 07:00:00, end would be 2010-05-20 06:59:59
     * @param string $date Date in user format
     * @return array Start & end date in start, end
     */
    public function getDayStartEndGMT($date)
    {
		$datetime = $this->split_date_time($date);

		$dates = $this->handleOffsetMax($this->to_db_date($datetime[0], false));

        $result['start'] = $dates['min'];
        $result['end'] = $dates['max'];

        return $result;
    }

}
