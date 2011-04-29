<?php
require_once 'include/Dashlets/DashletGenericChart.php';

class DashletGenericChartTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testLazyLoadSmartyObject() 
    {
        $dgc = new DashletGenericChartTestMock('unit_test_run');
        
        $smarty = $dgc->getConfigureSmartyInstance();
        
        $this->assertInstanceOf('Sugar_Smarty',$smarty);
        
        $smarty->assign('dog','cat');
        
        $smarty2 = $dgc->getConfigureSmartyInstance();
        
        $this->assertEquals('cat',$smarty2->get_template_vars('dog'));
    }
    
    public function testLazyLoadSeedBean() 
    {
        $dgc = new DashletGenericChartTestMock('unit_test_run');
        
        $focus = $dgc->getSeedBean();
        
        $this->assertInstanceOf('User',$focus);
        
        $focus->user_name = 'foobar';
        
        $focus2 = $dgc->getSeedBean();
        
        $this->assertEquals('foobar',$focus2->user_name);
    }
    
    public function testDisplay()
    {
        $dashlet = $this->getMock('DashletGenericChartTestMock',
                                    array('processAutoRefresh'),
                                    array('unit_test_run')
                                    );
        $dashlet->expects($this->any())
                ->method('processAutoRefresh')
                ->will($this->returnValue('successautorefresh'));
                
        $this->assertEquals('successautorefresh',$dashlet->display());
    }
    
    public function testSetRefreshIconIfRefreshable()
    {
        $dashlet = new DashletGenericChartTestMock('unit_test_run');
        $dashlet->isRefreshable = true;
        
        $this->assertContains('SUGAR.mySugar.retrieveDashlet(\'unit_test_run\',\'predefined_chart\');',$dashlet->setRefreshIcon());
    }
    
    public function testSetRefreshIconIfNotRefreshable()
    {
        $dashlet = new DashletGenericChartTestMock('unit_test_run');
        $dashlet->isRefreshable = false;
        
        $this->assertNotContains('SUGAR.mySugar.retrieveDashlet(\'unit_test_run\',\'predefined_chart\');',$dashlet->setRefreshIcon());
    }
    
    public function testConstructQueryReturnsNothing()
    {
        $dashlet = new DashletGenericChartTestMock('unit_test_run');
        
        $this->assertEmpty($dashlet->constructQuery());
    }
    
    public function testConstructGroupByReturnsNothing()
    {
        $dashlet = new DashletGenericChartTestMock('unit_test_run');
        
        $this->assertEquals(array(),$dashlet->constructGroupBy());
    }
}

class DashletGenericChartTestMock extends DashletGenericChart
{
    protected $_seedName = 'Users';
    
    public function getConfigureSmartyInstance()
    {
        return parent::getConfigureSmartyInstance();
    }
    
    public function getSeedBean()
    {
        return parent::getSeedBean();
    }
    
    public function constructQuery()
    {
        return parent::constructQuery();
    }
    
    public function constructGroupBy()
    {
        return parent::constructGroupBy();
    }
}
