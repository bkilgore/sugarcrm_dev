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

/*********************************************************************************

* Description: This file handles the Data base functionality for the application.
* It acts as the DB abstraction layer for the application. It depends on helper classes
* which generate the necessary SQL. This sql is then passed to PEAR DB classes.
* The helper class is chosen in DBManagerFactory, which is driven by 'db_type' in 'dbconfig' under config.php.
*
* All the functions in this class will work with any bean which implements the meta interface.
* The passed bean is passed to helper class which uses these functions to generate correct sql.
*
* The meta interface has the following functions:
* getTableName()	        	Returns table name of the object.
* getFieldDefinitions()	    	Returns a collection of field definitions in order.
* getFieldDefintion(name)		Return field definition for the field.
* getFieldValue(name)	    	Returns the value of the field identified by name.
*                           	If the field is not set, the function will return boolean FALSE.
* getPrimaryFieldDefinition()	Returns the field definition for primary key
*
* The field definition is an array with the following keys:
*
* name 		This represents name of the field. This is a required field.
* type 		This represents type of the field. This is a required field and valid values are:
*      		int
*      		long
*      		varchar
*      		text
*      		date
*      		datetime
*      		double
*      		float
*      		uint
*      		ulong
*      		time
*      		short
*      		enum
* length	This is used only when the type is varchar and denotes the length of the string.
*  			The max value is 255.
* enumvals  This is a list of valid values for an enum separated by "|".
*			It is used only if the type is ?enum?;
* required	This field dictates whether it is a required value.
*			The default value is ?FALSE?.
* isPrimary	This field identifies the primary key of the table.
*			If none of the fields have this flag set to ?TRUE?,
*			the first field definition is assume to be the primary key.
*			Default value for this field is ?FALSE?.
* default	This field sets the default value for the field definition.
*
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

include_once('include/database/MssqlManager.php');

class SqlsrvManager extends MssqlManager
{
    /**
     * @see DBManager::$backendFunctions
     */
    protected $backendFunctions = array(
        'free_result' => 'sqlsrv_free_stmt',
        'close'       => 'sqlsrv_close',
        );

	/**
     * cache of the results sets as they are fetched
     */
    protected $_resultsCache;

    /**
     * cache of the results sets as they are fetched
     */
    protected $_lastResultsCacheKey = 0;


    public function __construct()
    {
    	parent::__construct();
    	$this->_resultsCache = new ArrayObject;
    }

	/**
     * @see DBManager::connect()
     */
    public function connect(
        array $configOptions = null,
        $dieOnError = false
        )
    {
        global $sugar_config;

        if (is_null($configOptions))
            $configOptions = $sugar_config['dbconfig'];

        //set the connections parameters
        $connect_param = '';
        $configOptions['db_host_instance'] = trim($configOptions['db_host_instance']);
        if (empty($configOptions['db_host_instance']))
            $connect_param = $configOptions['db_host_name'];
        else
            $connect_param = $configOptions['db_host_name']."\\".$configOptions['db_host_instance'];

        /*
         * Don't try to specifically use a persistent connection
         * since the driver will handle that for us
         */
        $this->database = sqlsrv_connect(
                $connect_param ,
                array(
                    "UID" => $configOptions['db_user_name'],
                    "PWD" => $configOptions['db_password'],
                    "Database" => $configOptions['db_name'],
                    "CharacterSet" => "UTF-8",
                    "ReturnDatesAsStrings" => true,
                    "MultipleActiveResultSets" => false,
                    )
                );
        if(empty($this->database)) {
            $GLOBALS['log']->fatal("Could not connect to server ".$configOptions['db_host_name']." as ".$configOptions['db_user_name'].".");
            sugar_die($GLOBALS['app_strings']['ERR_NO_DB']);
        }

        if($this->checkError('Could Not Connect:', $dieOnError))
            $GLOBALS['log']->info("connected to db");

        $GLOBALS['log']->info("Connect:".$this->database);
    }

	/**
     * @see DBManager::checkError()
     */
    public function checkError(
        $msg = '',
        $dieOnError = false
        )
    {
        if (DBManager::checkError($msg, $dieOnError))
            return true;

        $sqlmsg = $this->_getLastErrorMessages();
        $sqlpos = strpos($sqlmsg, 'Changed database context to');
        if ( $sqlpos !== false )
            $sqlmsg = '';  // empty out sqlmsg if its 'Changed database context to'
        else {
            global $app_strings;
            //ERR_MSSQL_DB_CONTEXT: localized version of 'Changed database context to' message
            if (empty($app_strings)
					or !isset($app_strings['ERR_MSSQL_DB_CONTEXT'])
					or !isset($app_strings['ERR_MSSQL_WARNING']) ) {
                //ignore the message from sql-server if $app_strings array is empty. This will happen
                //only if connection if made before languge is set.
                $GLOBALS['log']->debug("Ignoring this database message: " . $sqlmsg);
                $sqlmsg = '';
            }
            else {
                $sqlpos = strpos($sqlmsg, $app_strings['ERR_MSSQL_DB_CONTEXT']);
                $sqlpos2 = strpos($sqlmsg, $app_strings['ERR_MSSQL_WARNING']);
				if ( $sqlpos !== false || $sqlpos2 !== false)
                    $sqlmsg = '';
            }
        }

        if ( strlen($sqlmsg) > 2 ) {
            $GLOBALS['log']->fatal("SQL Server error: " . $sqlmsg);
            return true;
        }

        return false;
	}

	/**
     * @see DBManager::query()
	 */
	public function query(
        $sql,
        $dieOnError = false,
        $msg = '',
        $suppress = false
        )
    {
		global $app_strings;
		
		// Flag if there are odd number of single quotes
        if ((substr_count($sql, "'") & 1))
            $GLOBALS['log']->error("SQL statement[" . $sql . "] has odd number of single quotes.");

        $sql = $this->_appendN($sql);
        
        $this->countQuery($sql);
        $GLOBALS['log']->info('Query:' . $sql);
        $this->checkConnection();
        $this->query_time = microtime(true);

		if ($suppress) {
        }
        else {
            $result = @sqlsrv_query($this->database, $sql);
        }
		// the sqlsrv driver will sometimes return false from sqlsrv_query()
        // on delete queries, so we'll also check to see if we get an error
        // message as well.
        // see this forum post for more info
        // http://forums.microsoft.com/MSDN/ShowPost.aspx?PostID=3685918&SiteID=1
        if (!$result && ( $this->_getLastErrorMessages() != '' ) ) {
            // awu Bug 10657: ignoring mssql error message 'Changed database context to' - an intermittent
            // 				  and difficult to reproduce error. The message is only a warning, and does
            //				  not affect the functionality of the query

            $sqlmsg = $this->_getLastErrorMessages();
            $sqlpos = strpos($sqlmsg, 'Changed database context to');
			$sqlpos2 = strpos($sqlmsg, 'Warning:');

			if ($sqlpos !== false || $sqlpos2 !== false)		// if sqlmsg has 'Changed database context to', just log it
				$GLOBALS['log']->debug($sqlmsg . ": " . $sql );
			else {
				$GLOBALS['log']->fatal($sqlmsg . ": " . $sql );
				if($dieOnError)
					sugar_die('SQL Error : ' . $sqlmsg);
				else
					echo 'SQL Error : ' . $sqlmsg;
			}
        }
        $this->lastmysqlrow = -1;

        $this->query_time = microtime(true) - $this->query_time;
        $GLOBALS['log']->info('Query Execution Time:'.$this->query_time);


        $this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);
        
        // fetch all the returned rows into an the resultsCache
        if ( is_resource($result) ) {
			$i = 0;
			while ( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) )
				$this->_resultsCache[$this->_lastResultsCacheKey][$i++] = $row;
			
			sqlsrv_free_stmt($result);
			
			return $this->_lastResultsCacheKey++;
		}
		else
			return $result;
    }

	/**
     * @see DBManager::getFieldsArray()
     */
	public function getFieldsArray(
        &$result,
        $make_lower_case = false
        )
	{
        $field_array = array();

        if ( !is_int($result) || !isset($this->_resultsCache[$result]) )
        	return false;
        
        foreach ( $this->_resultsCache[$result][0] as $key => $value ) {
            if($make_lower_case==true)
                $key = strtolower($key);

            $field_array[] = $key;
        }

        return $field_array;
	}

    /**
     * @see DBManager::fetchByAssoc()
     */
    public function fetchByAssoc(
        &$result,
        $rowNum = -1,
        $encode = true
        )
    {
        static $last_returned_result = array();
		
        if ( !is_int($result) || !isset($this->_resultsCache[$result]) )
        	return false;
        
        if ( !isset($last_returned_result[$result]) )
            $last_returned_result[$result] = 0;
        
        if ( !isset($this->_resultsCache[$result][$last_returned_result[$result]]) ) {
            $this->_resultsCache[$result] = null;
            unset($this->_resultsCache[$result]);
            return false;
        }
        
        $row = $this->_resultsCache[$result][$last_returned_result[$result]];
        if ( $last_returned_result[$result] >= count($this->_resultsCache[$result]) ) {
            $this->_resultsCache[$result] = null;
            unset($this->_resultsCache[$result]);
        }
        $last_returned_result[$result]++;
        //MSSQL returns a space " " when a varchar column is empty ("") and not null.
        //We need to iterate through the returned row array and strip empty spaces
        if(!empty($row)){
            foreach($row as $key => $column) {
                //notice we only strip if one space is returned.  we do not want to strip
                //strings with intentional spaces (" foo ")
                if (!empty($column) && $column ==" ") {
                    $row[$key] = '';
                }
            }
        }

        if($encode && $this->encode&& is_array($row))
            return array_map('to_html', $row);
        
        return $row;
	}

    /**
     * @see DBManager::getRowCount()
     */
    public function getRowCount(
        &$result
        )
    {
        return $this->getOne('SELECT @@ROWCOUNT');
	}
    
	/**
     * Have this function always return true, since the result is already freed
     *
     * @see DBManager::freeResult()
     */
    protected function freeResult(
        $result = false
        )
    {
    	if ( is_int($result) && isset($this->_resultsCache[$result]) )
			unset($this->_resultsCache[$result]);
		
    	return true;
    }
    
    /**
     * Emulates old mssql_get_last_message() behavior, giving us any error messages from the previous
     * function call
     *
     * @return string error message(s)
     */
    private function _getLastErrorMessages()
    {
        $message = '';

        if ( ($errors = sqlsrv_errors()) != null)
            foreach ( $errors as $error )
                $message .= $error['message'] . '. ';

        return $message;
    }

    /**
     * @see DBManager::convert()
     */
    public function convert(
        $string,
        $type,
        array $additional_parameters = array(),
        array $additional_parameters_oracle_only = array()
        )
    {
        if ( $type == 'datetime')
            return "CONVERT(varchar(25)," . $string . ",120)";
        else
            return parent::convert($string, $type, $additional_parameters, $additional_parameters_oracle_only);
    }

    /**
     * This is a utility function to prepend the "N" character in front of SQL values that are
     * surrounded by single quotes.
     *
     * @param  $sql string SQL statement
     * @return string SQL statement with single quote values prepended with "N" character for nvarchar columns
     */
    private function _appendN(
       $sql
       )
    {
        // If there are no single quotes, don't bother, will just assume there is no character data
        if (strpos($sql, "'") === false)
            return $sql;

        // Flag if there are odd number of single quotes, just continue w/o trying to append N
        if ((substr_count($sql, "'") & 1)) {
            $GLOBALS['log']->error("SQL statement[" . $sql . "] has odd number of single quotes.");
            return $sql;
        }

        //The only location of three subsequent ' will be at the begning or end of a value.
        $sql = preg_replace('/(?<!\')(\'{3})(?!\')/', "'<@#@#@PAIR@#@#@>", $sql);

        // Remove any remaining '' and do not parse... replace later (hopefully we don't even have any)
        $pairs        = array();
        $regexp       = '/(\'{2})/';
        $pair_matches = array();
        preg_match_all($regexp, $sql, $pair_matches);
        if ($pair_matches) {
            foreach (array_unique($pair_matches[0]) as $key=>$value) {
                $pairs['<@PAIR-'.$key.'@>'] = $value;
            }
            if (!empty($pairs)) {
                $sql = str_replace($pairs, array_keys($pairs), $sql);
            }
        }

        $regexp  = "/(N?\'.+?\')/is";
        $matches = array();
        preg_match_all($regexp, $sql, $matches);
        $replace = array();
        if (!empty($matches)) {
            foreach ($matches[0] as $key=>$value) {
                // We are assuming that all nvarchar columns are no more than 200 characters in length
                // One problem we face is the image column type in reports which cannot accept nvarchar data
                if (!empty($value) && !is_numeric(trim(str_replace(array("'", ","), "", $value))) && !preg_match('/^\'[\,]\'$/', $value)) {
                    $replace[$value] = 'N' . trim($value, "N");
                }
            }
        }

        if (!empty($replace))
            $sql = str_replace(array_keys($replace), $replace, $sql);

        if (!empty($pairs))
            $sql = str_replace(array_keys($pairs), $pairs, $sql);

        if(strpos($sql, "<@#@#@PAIR@#@#@>"))
            $sql = str_replace(array('<@#@#@PAIR@#@#@>'), array("''"), $sql);

        return $sql;
    }

	/**
     * Compares two vardefs. Overriding 39098  due to bug: 39098 . IN 6.0 we changed the id columns to dbType = 'id'
     * for example emails_beans.  In 554 the field email_id was nvarchar but in 6.0 since it id dbType = 'id' we would want to alter
     * it to varchar. This code will prevent it.
     *
     * @param  array  $fielddef1
     * @param  array  $fielddef2
     * @return bool   true if they match, false if they don't
     */
    public function compareVarDefs($fielddef1,$fielddef2)
    {
        if((isset($fielddef2['dbType']) && $fielddef2['dbType'] == 'id') || preg_match('/(_id$|^id$)/', $fielddef2['name'])){
            if(isset($fielddef1['type']) && isset($fielddef2['type'])){
                $fielddef2['type'] = $fielddef1['type'];
            }
        }
        return parent::compareVarDefs($fielddef1, $fielddef2);
    }
}
