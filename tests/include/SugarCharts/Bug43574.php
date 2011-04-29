<?php
require_once('include/SugarCharts/SugarChartFactory.php');

class Bug43574 extends Sugar_PHPUnit_Framework_TestCase
{
    private $sugarChart;
	public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->sugarChart = SugarChartFactory::getInstance('Jit', 'Reports');
    }
    
    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }

    /**
     * @dataProvider _xmlChartNodes
     */
    public function testJitCharXMLFormat($dirty, $clean)
    {
        $this->assertEquals($clean, $this->sugarChart->tab($dirty,1) );
    }

    public function _xmlChartNodes()
    {
        return array(
          array('simple string',"\tsimple string\n"),
          array('12345',"\t12345\n"),
          array('<xml_node/>',"\t<xml_node/>\n"),
          array('<xml_node>No bad data</xml_node>',"\t<xml_node>No bad data</xml_node>\n"),
          array('<xml_node>5852 string</xml_node>',"\t<xml_node>5852 string</xml_node>\n"),
          array('<xml_node>with ampersand &</xml_node>',"\t<xml_node>with ampersand &amp;</xml_node>\n"),
          array('<xml_node>with less than <</xml_node>',"\t<xml_node>with less than &lt;</xml_node>\n"),
          array('<xml_node>with greater than ></xml_node>',"\t<xml_node>with greater than &gt;</xml_node>\n"),
          array('<xml_node>Multiple & < > \'</xml_node>',"\t<xml_node>Multiple &amp; &lt; &gt; '</xml_node>\n"),
        );
    }

}
