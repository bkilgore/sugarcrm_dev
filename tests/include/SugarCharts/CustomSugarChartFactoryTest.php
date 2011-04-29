<?php
require_once('include/SugarCharts/SugarChartFactory.php');

class CustomSugarChartFactoryTest extends Sugar_PHPUnit_Framework_TestCase {

public static function setUpBeforeClass()
{
    $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
}

public static function tearDownAfterClass()
{
    SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    unset($GLOBALS['current_user']);
}


public function setUp()
{

mkdir_recursive('custom/include/SugarCharts/CustomSugarChartFactory');
	
$the_string = <<<EOQ
<?php

require_once("include/SugarCharts/JsChart.php");

class CustomSugarChartFactory extends JsChart {
	
	function __construct() {
		parent::__construct();
	}
	
	function getChartResources() {
		return '
		<link type="text/css" href="'.getJSPath('include/SugarCharts/Jit/css/base.css').'" rel="stylesheet" />
		<!--[if IE]><script language="javascript" type="text/javascript" src="'.getJSPath('include/SugarCharts/Jit/js/Jit/Extras/excanvas.js').'"></script><![endif]-->
		<script language="javascript" type="text/javascript" src="'.getJSPath('include/SugarCharts/Jit/js/Jit/jit.js').'"></script>
		<script language="javascript" type="text/javascript" src="'.getJSPath('include/SugarCharts/Jit/js/sugarCharts.js').'"></script>
		';
	}
	
	function getMySugarChartResources() {
		return '
		<script language="javascript" type="text/javascript" src="'.getJSPath('include/SugarCharts/Jit/js/mySugarCharts.js').'"></script>
		';
	}
	

	function display(\$name, \$xmlFile, \$width='320', \$height='480', \$resize=false) {
	
		parent::display(\$name, \$xmlFile, \$width, \$height, \$resize);

		return \$this->ss->fetch('include/SugarCharts/Jit/tpls/chart.tpl');	
	}
	

	function getDashletScript(\$id,\$xmlFile="") {
		
		parent::getDashletScript(\$id,\$xmlFile);
		return \$this->ss->fetch('include/SugarCharts/Jit/tpls/DashletGenericChartScript.tpl');
	}

}

?>
EOQ;

$fp = sugar_fopen('custom/include/SugarCharts/CustomSugarChartFactory/CustomSugarChartFactory.php', "w");
fwrite($fp, $the_string );
fclose($fp );

}

public function tearDown()
{
	rmdir_recursive('custom/include/SugarCharts/CustomSugarChartFactory');
}


public function testCustomFactory()
{
	$sugarChart = SugarChartFactory::getInstance('CustomSugarChartFactory');
	$name = get_class($sugarChart);
	$this->assertEquals('CustomSugarChartFactory', $name, 'Assert engine is CustomSugarChartFactory');
}

}
