<?php

class Bug42862Test extends Sugar_PHPUnit_Framework_TestCase  {

public function testDefaultPublishDate()
{
	global $timedate;
	$doc = new Document();
	$nowDate = $timedate->nowDbDate();
	$docPublishDate = $timedate->to_db_date($doc->active_date, true);
	$this->assertEquals($nowDate, $docPublishDate, "Assert that active_date field in new Document defaults to current date");
}

}

?>