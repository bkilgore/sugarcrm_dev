<?php
class TrackerTestUtility {
	
static $trackerSettings;	
	
static function setUp() {
    	require('modules/Trackers/config.php');
		foreach($tracker_config as $entry) {
		   if(isset($entry['bean'])) {
		   	  $GLOBALS['tracker_' . $entry['name']] = false;
		   } //if
		} //foreach
		
		$result = $GLOBALS['db']->query("SELECT category, name, value from config WHERE category = 'tracker' and name != 'prune_interval'");
    	self::$trackerSettings = array();
		while($row = $GLOBALS['db']->fetchByAssoc($result)){
		      self::$trackerSettings[$row['name']] = $row['value'];
		      $GLOBALS['db']->query("DELETE from config where category = 'tracker' and name = '{$row['name']}'");
		}  		
}	

static function tearDown() {
        foreach(self::$trackerSettings as $name=>$value) {
    	   $GLOBALS['db']->query("INSERT into config (category, name, value) values ('tracker', '{$name}', '{$value}')");
    	}	
}

}
?>