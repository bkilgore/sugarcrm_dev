<?php
require_once 'include/TimeDate.php';

class TimeDateTest extends Sugar_PHPUnit_Framework_TestCase
{
	/**
	 * @var TimeDate
	 */
	protected $time_date;

	const DEFAULT_TIME_FORMAT = 'H:i';

	protected $date_tests = array(
		array("db" => '2005-10-25 07:00:00', "df" => 'd-m-Y', 'tz' => 'America/Los_Angeles', "display" => '25-10-2005', "dbdate" => "2005-10-25 00:00:00"),
		// add times
		array("db" => '2005-10-26 06:42:00', "df" => 'd-m-Y', "tf" => 'h.iA', 'tz' => 'America/Los_Angeles', "display" => '25-10-2005 11.42PM', "dbdate" => "2005-10-25 23:42:00"),
		// GMT+0 timezone
		array("db" => '2005-11-25 00:00:00', "df" => 'd-m-Y', 'tz' => 'Europe/London', "display" => '25-11-2005', "dbdate" => "2005-11-25 00:00:00"),
		// GMT+1
		array("db" => '2005-11-24 23:00:00', "dbdate" => "2005-11-25", "df" => 'd;m;Y', 'tz' => 'Europe/Oslo', "display" => '25;11;2005', "dbdate" => "2005-11-25 00:00:00"),
		// DST in effect
		array("db" => '2005-10-24 23:00:00', "dbdate" => "2005-10-25", "df" => 'd-m-Y', 'tz' => 'Europe/London', "display" => '25-10-2005', "dbdate" => "2005-10-25 00:00:00"),
		// different format
		array("db" => '1997-10-25 07:00:00', "df" => 'Y-m-d', 'tz' => 'America/Los_Angeles', "display" => '1997-10-25', "dbdate" => "1997-10-25 00:00:00"),
		array("db" => '1997-01-25 00:00:00', "df" => 'm-d-Y', 'tz' => 'Europe/London', "display" => '01-25-1997', "dbdate" => "1997-01-25 00:00:00"),
		// with times
		array("db" => '2005-10-25 10:42:24', "df" => 'd/m/Y', "tf" => "H:i:s", 'tz' => 'America/Los_Angeles', "display" => '25/10/2005 03:42:24', "dbdate" => "2005-10-25 03:42:24"),
		array("db" => '2005-10-25 02:42:24', "df" => 'd/m/Y', "tf" => "H:i:s", 'tz' => 'Europe/London', "display" => '25/10/2005 03:42:24', "dbdate" => "2005-10-25 03:42:24"),
		array("db" => '2005-10-25 01:42:24', "df" => 'd/m/Y', "tf" => "H:i:s", 'tz' => 'Asia/Jerusalem', "display" => '25/10/2005 03:42:24', "dbdate" => "2005-10-25 03:42:24"),
		// FAIL! FIXME: same format leads to no TZ conversion
		array("db" => '2005-10-25 10:42:24', "df" => 'Y-m-d', "tf" => "H:i:s", 'tz' => 'America/Los_Angeles', "display" => '2005-10-25 03:42:24', "dbdate" => "2005-10-25 03:42:24"),
		// short times
		array("db" => '2005-10-25 10:42:00', "df" => 'd/m/Y', "tf" => "H:i", 'tz' => 'America/Los_Angeles', "display" => '25/10/2005 03:42', "dbdate" => "2005-10-25 03:42:00"),
		array("db" => '2005-10-25 22:00:00', "df" => 'd/m/Y', "tf" => "ha", 'tz' => 'America/Los_Angeles', "display" => '25/10/2005 03pm', "dbdate" => "2005-10-25 15:00:00"),
		array("db" => '2005-10-25 10:00:00', "df" => 'd/m/Y', "tf" => "h", 'tz' => 'America/Los_Angeles', "display" => '25/10/2005 03', "dbdate" => "2005-10-25 03:00:00"),
		array("db" => '2005-10-25 20:00:00', "df" => 'd/m/Y', "tf" => "H", 'tz' => 'America/Los_Angeles', "display" => '25/10/2005 13', "dbdate" => "2005-10-25 13:00:00"),
	);

	protected $time_tests = array(
		// full time
		array("db" => "11:45:00", "display" => "11:45"),
		array("db" => "05:17:28", "tf" => "H.i.s", "display" => "05.17.28"),
		// short ones
		array("db" => "17:34:00", "tf" => "H:i", "display" => "17:34"),
		array("db" => "11:42:00", "tf" => "h.iA", "display" => "11.42AM"),
		array("db" => "15:00:00", "tf" => "ha", "display" => "03pm"),
		array("db" => "15:00:00", "tf" => "H", "display" => "15"),
		// FIXME: is this a valid format? it doesn't allow roundtrip
		array("db" => "03:00:00", "tf" => "h", "display" => "03"),
		// weirdo
		array("db" => "16:42:34", "tf" => "s:i:H", "display" => "34:42:16"),
		);

	public function setUp()
	{
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
		$this->time_date = new TimeDate();
		$this->_noUserCache();
	}

	public function tearDown()
	{
		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($this->time_date);
	}

	protected function _noUserCache()
	{
		$this->time_date->allow_cache = false;
	}

	protected function _setPrefs($datef, $timef, $tz) {
			$GLOBALS['current_user']->setPreference('datef', $datef);
			$GLOBALS['current_user']->setPreference('timef', $timef);
			$GLOBALS['current_user']->setPreference('timezone', $tz);
			// new object to avoid TZ caching
			$this->time_date = new TimeDate();
			$this->_noUserCache();
	}

	protected function _dateOnly($datetime)
	{
		// FIXME: assumes dates have no spaces
		$dt = explode(' ', $datetime);
		return $dt[0];
	}

	protected function _timeOnly($datetime)
	{
		// FIXME: assumes dates have no spaces
		$dt = explode(' ', $datetime);
		if(count($dt) > 1) {
			return $dt[1];
		}
		return $datetime;
	}

	/**
	 * test conversion from local datetime to DB datetime
	 */
	public function testToDbFormats()
	{
		foreach($this->date_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$this->assertEquals($datetest["db"],
				$this->time_date->to_db($datetest["display"]),
				"Broken conversion for '{$datetest["df"]} $tf' with date '{$datetest["display"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from full local datetime to DB date
	 */
	public function testToDbDateFormatsWithOffset()
	{
		foreach($this->date_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$this->assertEquals(
				$this->_dateOnly($datetest["db"]),
				$this->time_date->to_db_date($datetest["display"], true),
				"Broken conversion for '{$datetest["df"]} $tf' with date '{$datetest["display"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from local date to DB date, no TZ handling
	 */
	public function testToDbDateFormatsNoOffset()
	{
		foreach($this->date_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$this->assertEquals(
				$this->_dateOnly($datetest["dbdate"]),
				$this->time_date->to_db_date($this->_dateOnly($datetest["display"]), false),
				"Broken conversion for '{$datetest["df"]} $tf' with date '{$datetest["display"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from full local datetime to DB time
	 */
	public function testToDbTimeFormatsWithTz()
	{
		foreach($this->date_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$this->assertEquals(
				$this->_timeOnly($datetest["db"]),
				$this->time_date->to_db_time($datetest["display"], true),
				"Broken conversion for '{$datetest["df"]} $tf' with date '{$datetest["display"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from local time to DB time, no TZ handling
	 */
	public function testToDbTimeFormatsNoTz()
	{
		foreach($this->time_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs('Y-m-d', $tf, '');
			$this->assertEquals(
				$datetest["db"],
				$this->time_date->to_db_time($datetest["display"], false),
				"Broken conversion for '$tf' with date '{$datetest["display"]}'");
		}
	}

	/**
	 * test conversion from local date+time to DB date+time, no TZ handling
	 */
	public function testToDbDateTimeFormats()
	{
		foreach($this->date_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$dt = explode(' ', $datetest["display"]);
			if(count($dt) > 1) {
				list($date, $time) = $dt;
			} else {
				$date = $dt[0];
				$z = new DateTime("@0", new DateTimeZone("GMT"));
				$time = $z->format($tf);
			}
			$this->assertEquals(
				explode(' ',$datetest["dbdate"]),
				$this->time_date->to_db_date_time($date, $time),
				"Broken conversion for '{$datetest["df"]} $tf' with date '{$datetest["display"]}' and TZ {$datetest["tz"]}");
		}
	}


	/**
	 * test conversion from DB date+time to local date+time with TZ handling
	 */
	public function testToDisplayDateTimeFormats()
	{
		foreach($this->date_tests as $datetest) {
			if(!isset($datetest["tf"])) {
				$tf = null;
			} else {
				$tf = $datetest["tf"];
			}
			$df = $datetest["df"]." ".$tf;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$result = $this->time_date->to_display_date_time($datetest["db"], true, true, $GLOBALS['current_user']);
			if(!isset($datetest["tf"])) {
				$result = $this->_dateOnly($result);
			}
			$this->assertEquals(
				$datetest["display"],
				$result,
				"Broken conversion for '$df' with date '{$datetest["db"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from DB date+time to local date+time without TZ handling
	 */
	public function testToDisplayFormatsNoTz()
	{
		foreach($this->date_tests as $datetest) {
			if(!isset($datetest["tf"])) {
				$tf = null;
			} else {
				$tf = $datetest["tf"];
			}
			$df = $datetest["df"]." ".$tf;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$result = $this->time_date->to_display($datetest["dbdate"], $this->time_date->get_db_date_time_format(), $df);
			if(!isset($datetest["tf"])) {
				$result = $this->_dateOnly($result);
			}
			$this->assertEquals(
				$datetest["display"],
				$result,
				"Broken conversion for '$df' with date '{$datetest["db"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from DB time to local time without TZ conversion
	 */
	public function testToDisplayTimeFormatsNoTZ()
	{
		foreach($this->time_tests as $datetest) {
			$tf = isset($datetest["tf"]) ? $datetest["tf"] : self::DEFAULT_TIME_FORMAT;
			$this->_setPrefs('Y-m-d', $tf, '');
			$this->assertEquals(
				$datetest["display"],
				$this->time_date->to_display_time($datetest["db"], true, false),
				"Broken conversion for '$tf' with date '{$datetest["db"]}'");
		}
	}

	/**
	 * test conversion from DB time to local time with TZ conversion
	 */
	public function testToDisplayTimeFormatsWithTZ()
	{
		foreach($this->date_tests as $datetest) {
			if(!isset($datetest["tf"])) continue;
			$this->_setPrefs($datetest["df"], $datetest["tf"], $datetest["tz"]);
			$result = $this->time_date->to_display_time($datetest["db"], true, true);
			$result = $this->_timeOnly($result);
			$this->assertEquals(
				$this->_timeOnly($datetest["display"]),
				$result,
				"Broken conversion for '{$datetest["tf"]}' with date '{$datetest["db"]}' and TZ {$datetest["tz"]}");
		}
	}


	/**
	 * test conversion from DB date to local date without TZ handling
	 */
	public function testToDisplayDateFormatsNoTz()
	{
		foreach($this->date_tests as $datetest) {
			if(!isset($datetest["tf"])) {
				$tf = null;
			} else {
				$tf = $datetest["tf"];
			}
			$df = $datetest["df"]." ".$tf;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$result = $this->time_date->to_display_date($this->_dateOnly($datetest["dbdate"]), false);
			$this->assertEquals(
				$this->_dateOnly($datetest["display"]),
				$this->_dateOnly($result),
				"Broken conversion for '{$datetest["df"]}' with date '{$datetest["dbdate"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test conversion from DB date to local date with TZ handling
	 */
	public function testToDisplayDateFormatsWithTz()
	{
		foreach($this->date_tests as $datetest) {
			if(!isset($datetest["tf"])) {
				$tf = null;
			} else {
				$tf = $datetest["tf"];
			}
			$df = $datetest["df"]." ".$tf;
			$this->_setPrefs($datetest["df"], $tf, $datetest["tz"]);
			$result = $this->time_date->to_display_date($datetest["db"], true);
			$this->assertEquals(
				$this->_dateOnly($datetest["display"]),
				$this->_dateOnly($result),
				"Broken conversion for '{$datetest["df"]}' with date '{$datetest["dbdate"]}' and TZ {$datetest["tz"]}");
		}
	}

	/**
	 * test midnight formatting
	 */
	public function testGetMidnight()
	{
		if(!is_callable(array($this->time_date, "get_default_midnight"))) {
			$this->markTestSkipped("Method is no longer public");
		}
		$times = array(
			array("tf" => "H:i", "time" => "00:00"),
			array("tf" => "H:i:s", "time" => "00:00:00"),
			array("tf" => "h:i", "time" => "12:00"),
			array("tf" => "h:i:s", "time" => "12:00:00"),
			array("tf" => "h`iA", "time" => "12`00AM"),
			array("tf" => "h`i`sa", "time" => "12`00`00am"),
		);
		foreach($times as $timetest) {
			$this->_setPrefs('', $timetest["tf"], "America/Los_Angeles");
			$this->assertEquals($timetest["time"],  $this->time_date->get_default_midnight(true),
				"Bad midnight value for {$timetest["time"]} format {$timetest["tf"]}");
		}
	}

	public function testSwapFormatsWithTheSameDateFormat()
	{
		$original_date = '2005-12-25';
		$original_format = 'Y-m-d';
		$new_format = $original_format;
		$expected_new_date = $original_date;

		$new_date = $this->time_date->swap_formats($original_date,
			$original_format, $new_format);

		$this->assertEquals($expected_new_date, $new_date);
	}

	public function testSwapFormatsFromMdyFormatToDmyFormat()
	{
		$original_date = '12-25-2005';
		$original_format = 'm-d-Y';
		$new_format = 'd-m-Y';
		$expected_new_date = '25-12-2005';

		$new_date = $this->time_date->swap_formats($original_date,
			$original_format, $new_format);

		$this->assertEquals($expected_new_date, $new_date,
			"Convert from $original_format to $new_format failed.");
	}

	public function testSwapFormatsWithTheSameDatetimeFormat()
	{
		$original_date = '2005-12-25 12:55:35';
		$original_format = 'Y-m-d H:i:s';
		$new_format = $original_format;
		$expected_new_date = $original_date;

		$new_date = $this->time_date->swap_formats($original_date,
			$original_format, $new_format);

		$this->assertEquals($expected_new_date, $new_date,
			'Same datetime format not returned.');
	}

	public function testSwapFormatsFromYmdhiFormatToYmdhisFormat()
	{
		$original_date = '2005-12-25 12:55';
		$original_format = 'Y-m-d H:i';
		$new_format = 'Y-m-d H:i:s';
		$expected_new_date = '2005-12-25 12:55:00';

		$new_date = $this->time_date->swap_formats($original_date,
			$original_format, $new_format);

		$this->assertEquals($expected_new_date, $new_date);
	}

	public function testSwapFormatsFromYmdhiFormatToYmdhiaFormat()
	{
		$original = '2005-12-25 13:55';
		$original_format = 'Y-m-d H:i';
		$new_format = 'Y-m-d h:ia';
		$expected = '2005-12-25 01:55pm';

		$new = $this->time_date->swap_formats($original,
			$original_format, $new_format);

		$this->assertEquals($expected, $new);
	}

	public function testAllDateFormatSwappingCombinations()
	{
		$orig_formats_and_dates = array(
			'Y-m-d' => '2006-12-23',
			'm-d-Y' => '12-23-2006',
			'd-m-Y' => '23-12-2006',
			'Y/m/d' => '2006/12/23',
			'm/d/Y' => '12/23/2006',
			'd/m/Y' => '23/12/2006');

		$new_formats_and_dates = $orig_formats_and_dates;

		foreach($orig_formats_and_dates as $orig_format => $orig_date)
		{
			foreach($new_formats_and_dates as $new_format => $expected_date)
			{
				$new_date = $this->time_date->swap_formats($orig_date,
					$orig_format, $new_format);

				$this->assertEquals($expected_date, $new_date,
					"Convert from $orig_format to $new_format failed.");

				if($expected_date != $new_date)
				{
					return;
				}
			}
		}
	}

    /**
     * @group bug17528
     */
	public function testSwapDatetimeFormatToDbFormat()
	{
		$date = '10-25-2007 12:00am';
		$format = $this->time_date->get_date_time_format();
		$db_format = $this->time_date->get_db_date_time_format();

		$this->assertEquals(
			$this->time_date->swap_formats(
				$date,
				'm-d-Y h:ia',
				$this->time_date->get_db_date_time_format()
			),
			'2007-10-25 00:00:00'
		);
	}

	/**
     * @group bug17528
     */
	public function testTodbCanHandleDdmmyyyyFormats()
	{
		$old_pattern = $GLOBALS['current_user']->getPreference('datef');
		$GLOBALS['current_user']->setPreference('datef','d-m-Y');
		$db_date_pattern = '/2007-10-25 [0-9]{2}:[0-9]{2}:[0-9]{2}/';
		$this->assertRegExp(
			$db_date_pattern,
			$this->time_date->to_db('25-10-2007')
		);

		$this->_noUserCache();
		$GLOBALS['current_user']->setPreference('datef','m-d-Y');
		$this->assertRegExp(
			$db_date_pattern,
			$this->time_date->to_db('10-25-2007')
		);
		$GLOBALS['current_user']->setPreference('datef',$old_pattern);
	}

	/**
     * @group bug17528
     */
	public function testTodbCanHandleMmddyyyyFormats()
	{
		$old_date = $GLOBALS['current_user']->getPreference('datef');

		$GLOBALS['current_user']->setPreference('datef','m-d-Y');
		$db_date_pattern = '/2007-10-25 [0-9]{2}:[0-9]{2}:[0-9]{2}/';
		$this->assertRegExp(
			$db_date_pattern,
			$this->time_date->to_db('10-25-2007')
		);

		$GLOBALS['current_user']->setPreference('datef',$old_date);
	}

	/**
     * @group bug17528
     */
	public function testTodbdateCanHandleDdmmyyyyFormats()
	{
		$old_date = $GLOBALS['current_user']->getPreference('datef');

		$GLOBALS['current_user']->setPreference('datef','d-m-Y');
		$this->assertEquals(
			$this->time_date->to_db_date('25-10-2007'),
			'2007-10-25'
		);

		$GLOBALS['current_user']->setPreference('datef',$old_date);
	}

	/**
     * @group bug17528
     */
	public function testTodbdateCanHandleMmddyyyyFormats()
	{
		$old_date = $GLOBALS['current_user']->getPreference('datef');
		$GLOBALS['current_user']->setPreference('datef','m-d-Y');
		$this->assertEquals(
			'2007-10-25',
			$this->time_date->to_db_date('10-25-2007')
		);

		$GLOBALS['current_user']->setPreference('datef',$old_date);
	}

	public function testConvertMmddyyyyFormatToYyyymmdd()
	{
		$this->assertEquals(
			'2007-11-02',
			$this->time_date->swap_formats(
				'11-02-2007',
				'm-d-Y',
				'Y-m-d'
			)
		);
	}

	public function testGeneratingDefaultMidnight()
	{
		if(!is_callable(array($this->time_date, "get_default_midnight"))) {
			$this->markTestSkipped("Method is no longer public");
		}
		$old_time = $GLOBALS['current_user']->getPreference('timef');

		$GLOBALS['current_user']->setPreference('timef','H:i:s');
		$this->assertEquals(
			'00:00:00',
			$this->time_date->get_default_midnight(true)
		);

		$GLOBALS['current_user']->setPreference('timef','h:ia');
		$this->assertEquals(
			'12:00am',
			$this->time_date->get_default_midnight(true)
		);

		$GLOBALS['current_user']->setPreference('timef',$old_time);
	}

	public function providerGetDateFromRules()
	{
	    return array(
	        array('2009',10,1,0,7200,"2009-10-04 02:00:00"),
	        array('2009',4,1,0,7200,"2009-04-05 02:00:00"),
	        array('2010',3,24,5,7200,"2010-03-26 02:00:00"),
	        array('2010',9,12,0,7200,"2010-09-12 02:00:00"),
	        );
	}

	/**
	 * @dataProvider providerGetDateFromRules
	 */
	public function testGetDateFromRules(
	    $year,
	    $startMonth,
	    $startDate,
	    $weekday,
	    $startTime,
	    $returnValue
	    )
	{
		if(!is_callable(array($this->time_date, "getDateFromRules"))) {
			$this->markTestSkipped("Method is no longer public");
		}
		$this->assertEquals(
	        $this->time_date->getDateFromRules($year, $startMonth, $startDate, $weekday, $startTime),
	        $returnValue
	        );
	}

	/**
	 * tests for check_matching_format
	 */
	public function testCheckMatchingFormats()
	{
		foreach($this->date_tests as $datetest) {
			if(isset($datetest["tf"])) {
				$df = $this->time_date->merge_date_time($df = $datetest["df"], $datetest["tf"]);
			} else {
				$df = $datetest["df"];
			}
			$this->assertTrue($this->time_date->check_matching_format($datetest["display"], $df),
				"Broken match for '$df' with date '{$datetest["display"]}'");
		}

		// Some bad dates not detected by current code, it's too lenient
		$badtests = array(
			array("format" => "Y-m-d", "date" => ""),
			array("format" => "Y-m-d", "date" => "blah-blah-blah"),
			array("format" => "Y-m-d", "date" => "1-2"),
			array("format" => "Y-m-d", "date" => "2007-10"),
			//FIXME: array("format" => "Y-m-d", "date" => "200-10-25"),
			array("format" => "Y-m-d", "date" => "2007-101-25"),
			array("format" => "Y-m-d", "date" => "2007-Oct-25"),
			//FIXME: array("format" => "Y-m-d", "date" => "2007-10-250"),
			array("format" => "d-m-Y", "date" => "2007-10-25"),
			array("format" => "d-m-Y", "date" => "10/25/2007"),
			//FIXME: array("format" => "Y-m-d", "date" => "here: 2007-20-25"),
			//FIXME: array("format" => "Y-m-d", "date" => "2007-20-25 here"),
		);
		foreach($badtests as $datetest) {
			$this->assertFalse($this->time_date->check_matching_format($datetest["date"], $datetest["format"]),
			"Broken match for '{$datetest["format"]}' with date '{$datetest["date"]}'");
		}
	}

	/**
	 * test fetching user settings
	 */
	public function testGetUserSettings()
	{
		$this->_setPrefs('d/m/Y', 'h:i:sA', "America/Lima");
		$this->assertEquals('dd/mm/yyyy', $this->time_date->get_user_date_format());
		//FIXME: $this->assertEquals('11:00:00PM', $this->time_date->get_user_time_format());
		$tz = $this->time_date->getUserTimeZone();
		$this->assertEquals(-300, $tz["gmtOffset"]);
//		$this->assertEquals(60, $tz["dstOffset"]);
	}

	/**
	 * test getting GMT dates
	 */
	public function testGetGMT()
	{
		$gmt = $this->time_date->get_gmt_db_datetime();
		$dt = strptime($gmt, "%Y-%m-%d %H:%M:%S");
		$this->assertEquals($dt['tm_year']+1900, gmdate("Y"));
		$this->assertEquals($dt['tm_mon']+1, gmdate("m"));
		$this->assertEquals($dt['tm_mday'], gmdate("d"));

		$gmt = $this->time_date->get_gmt_db_date();
		$dt = strptime($gmt, "%Y-%m-%d");
		$this->assertEquals($dt['tm_year']+1900, gmdate("Y"));
		$this->assertEquals($dt['tm_mon']+1, gmdate("m"));
		$this->assertEquals($dt['tm_mday'], gmdate("d"));
	}

	/**
	 * test getting DB date formats indifferent ways
	 */
	public function testGetDB()
	{
		$this->assertEquals(gmdate($this->time_date->merge_date_time($this->time_date->get_db_date_format(),
																		$this->time_date->get_db_time_format())),
			 $this->time_date->get_gmt_db_datetime());
	}

	public function testGetCalFormats()
	{
		$cal_tests = array(
			array("df" => "Y-m-d", "caldf" => "%Y-%m-%d", "tf" => "H:i:s", "caltf" => "%H:%M:%S"),
			array("df" => "d/m/Y", "caldf" => "%d/%m/%Y", "tf" => "h:i:sa", "caltf" => "%I:%M:%S%P"),
			array("df" => "m/d/Y", "caldf" => "%m/%d/%Y", "tf" => "H:i", "caltf" => "%H:%M"),
			array("df" => "Y-m-d", "caldf" => "%Y-%m-%d", "tf" => "h:iA", "caltf" => "%I:%M%p"),
		);
		foreach($cal_tests as $datetest) {
			$this->_setPrefs($datetest["df"], $datetest["tf"], "America/Los_Angeles");
			$this->assertEquals(
				$datetest["caldf"],
				$this->time_date->get_cal_date_format(),
				"Bad cal date format for '{$datetest["df"]}'");
			$this->assertEquals(
				$datetest["caltf"],
				$this->time_date->get_cal_time_format(),
				"Bad cal time format for '{$datetest["tf"]}'");
			$this->assertEquals(
				$this->time_date->merge_date_time($datetest["caldf"], $datetest["caltf"]),
				$this->time_date->get_cal_date_time_format(),
				"Bad cal datetime format for '{$datetest["df"]} {$datetest["tf"]}'");
		}
	}

	/**
	 * test for handleOffsetMax
	 */
	public function testDayMinMax()
	{
		$day_tests = array(
			array("date" => "2010-05-19", "start" => "2010-05-19 07:00:00", "end" => "2010-05-20 06:59:59", 'tz' => 'America/Los_Angeles'),
			array("date" => "2010-01-19", "start" => "2010-01-19 08:00:00", "end" => "2010-01-20 07:59:59", 'tz' => 'America/Los_Angeles'),
			array("date" => "2010-05-19", "start" => "2010-05-18 23:00:00", "end" => "2010-05-19 22:59:59", 'tz' => 'Europe/London'),
			array("date" => "2010-01-19", "start" => "2010-01-19 00:00:00", "end" => "2010-01-19 23:59:59", 'tz' => 'Europe/London'),
			array("date" => "2010-05-19", "start" => "2010-05-18 22:00:00", "end" => "2010-05-19 21:59:59", 'tz' => 'Europe/Oslo'),
		);
		foreach($day_tests as $datetest) {
			$this->_setPrefs('', '', $datetest["tz"]);
			$dates = $this->time_date->handleOffsetMax($datetest["date"], '');
			$this->assertEquals($datetest["start"], $dates["min"],
				"Bad min result for {$datetest["date"]} tz {$datetest["tz"]}");
			$this->assertEquals($datetest["end"], $dates["max"],
				"Bad max result for {$datetest["date"]} tz {$datetest["tz"]}");
		}
	}

	/**
	 * test for getDayStartEndGMT
	 */
	public function testGetDayStartEnd()
	{
		$day_tests = array(
			array("date" => "05/19/2010", "start" => "2010-05-19 07:00:00", "end" => "2010-05-20 06:59:59", 'tz' => 'America/Los_Angeles'),
			array("date" => "01/19/2010", "start" => "2010-01-19 08:00:00", "end" => "2010-01-20 07:59:59", 'tz' => 'America/Los_Angeles'),
			array("date" => "05/19/2010", "start" => "2010-05-18 23:00:00", "end" => "2010-05-19 22:59:59", 'tz' => 'Europe/London'),
			array("date" => "01/19/2010", "start" => "2010-01-19 00:00:00", "end" => "2010-01-19 23:59:59", 'tz' => 'Europe/London'),
			array("date" => "05/19/2010", "start" => "2010-05-18 22:00:00", "end" => "2010-05-19 21:59:59", 'tz' => 'Europe/Oslo'),
		);
		foreach($day_tests as $datetest) {
			$this->_setPrefs('m/d/Y', '', $datetest["tz"]);
			$dates = $this->time_date->getDayStartEndGMT($datetest["date"], '');
			$this->assertEquals($datetest["start"], $dates["start"],
				"Bad min result for {$datetest["date"]} tz {$datetest["tz"]}");
			$this->assertEquals($datetest["end"], $dates["end"],
				"Bad max result for {$datetest["date"]} tz {$datetest["tz"]}");
		}
	}

	/**
	 * test for merge_time_meridiem
	 */
	public function testMergeAmPm()
	{
		$ampm_tests = array(
			array("date" => "05:17:28", "mer" => "am", "tf" => "H:i:s", "display" => "05:17:28"),
			array("date" => "05:17:28", "mer" => "am", "tf" => "h:i:sa", "display" => "05:17:28am"),
			// short ones
			array("date" => "17:34", "mer" => "pm", "tf" => "H:i", "display" => "17:34"),
			array("date" => "11:42", "mer" => "PM", "tf" => "h:iA", "display" => "11:42PM"),
			array("date" => "11:42", "mer" => "pm", "tf" => "h:iA", "display" => "11:42pm"),
			array("date" => "03", "mer" => "AM", "tf" => "ha", "display" => "03AM"),
			array("date" => "15", "mer" => "AM", "tf" => "H", "display" => "15"),
		);
	foreach($ampm_tests as $datetest) {
			$amdate = $this->time_date->merge_time_meridiem($datetest["date"], $datetest["tf"], $datetest["mer"]);
			$this->assertEquals($datetest["display"], $amdate,
				"Bad min result for {$datetest["date"]} format {$datetest["tf"]}");
		}
	}
	
	public function providerSplitDateTime()
	{
	    return array(
	        array("2009-10-04 02:00:00","2009-10-04","02:00:00"),
	        array("10/04/2010 2:00pm","10/04/2010","2:00pm"),
	        array("10-04-2010 2:00","10-04-2010","2:00"),
	        );
	}
	
	/**
	 * @dataProvider providerSplitDateTime
	 */
	public function testSplitDateTime(
	    $datetime,
	    $date,
	    $time
	    )
	{
	    $this->assertEquals($date,$this->time_date->getDatePart($datetime));
	    $this->assertEquals($time,$this->time_date->getTimePart($datetime));
	}
}
