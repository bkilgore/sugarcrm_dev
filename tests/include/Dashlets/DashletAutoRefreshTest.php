<?php
require_once 'include/Dashlets/Dashlet.php';

/**
 * @ticket 33948
 */
class DashletAutoRefreshTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setup()
    {
        if ( isset($GLOBALS['sugar_config']['dashlet_auto_refresh_min']) ) {
            $this->backup_dashlet_auto_refresh_min = $GLOBALS['sugar_config']['dashlet_auto_refresh_min'];
        }
        unset($GLOBALS['sugar_config']['dashlet_auto_refresh_min']);
    }
    
    public function tearDown()
    {
        if ( isset($this->backup_dashlet_auto_refresh_min) ) {
            $GLOBALS['sugar_config']['dashlet_auto_refresh_min'] = $this->backup_dashlet_auto_refresh_min;
        }
    }
    
    public function testIsAutoRefreshableIfRefreshable() 
    {
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $dashlet->isRefreshable = true;
        
        $this->assertTrue($dashlet->isAutoRefreshable());
    }
    
    public function testIsNotAutoRefreshableIfNotRefreshable() 
    {
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $dashlet->isRefreshable = false;
        
        $this->assertFalse($dashlet->isAutoRefreshable());
    }
  
    public function testReturnCorrectAutoRefreshOptionsWhenMinIsSet() 
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('dashlet_auto_refresh_options',
            array(
                '-1' 	=> 'Never',
                '30' 	=> 'Every 30 seconds',
                '60' 	=> 'Every 1 minute',
                '180' 	=> 'Every 3 minutes',
                '300' 	=> 'Every 5 minutes',
                '600' 	=> 'Every 10 minutes',
                )
            );
        $langpack->save();
    
        $GLOBALS['sugar_config']['dashlet_auto_refresh_min'] = 60;
        
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $options = $dashlet->getAutoRefreshOptions();
        $this->assertEquals(
            array(
                '-1' 	=> 'Never',
                '60' 	=> 'Every 1 minute',
                '180' 	=> 'Every 3 minutes',
                '300' 	=> 'Every 5 minutes',
                '600' 	=> 'Every 10 minutes',
                ),
            $options
            );
        
        unset($langpack);
    }
    
    public function testReturnCorrectAutoRefreshOptionsWhenMinIsNotSet() 
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('dashlet_auto_refresh_options',
            array(
                '-1' 	=> 'Never',
                '30' 	=> 'Every 30 seconds',
                '60' 	=> 'Every 1 minute',
                '180' 	=> 'Every 3 minutes',
                '300' 	=> 'Every 5 minutes',
                '600' 	=> 'Every 10 minutes',
                )
            );
        $langpack->save();
    
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $options = $dashlet->getAutoRefreshOptions();
        $this->assertEquals(
            array(
                '-1' 	=> 'Never',
                '30' 	=> 'Every 30 seconds',
                '60' 	=> 'Every 1 minute',
                '180' 	=> 'Every 3 minutes',
                '300' 	=> 'Every 5 minutes',
                '600' 	=> 'Every 10 minutes',
                ),
            $options
            );
        
        unset($langpack);
    }
    
    public function testProcessAutoRefreshReturnsAutoRefreshTemplateNormally()
    {
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $dashlet->isRefreshable = true;
        $_REQUEST['module'] = 'unit_test';
        $_REQUEST['action'] = 'unit_test';
        $dashlet->seedBean = new stdClass;
        $dashlet->seedBean->object_name = 'unit_test';
        
        $this->assertNotEmpty($dashlet->processAutoRefresh());
    }
    
    public function testProcessAutoRefreshReturnsNothingIfDashletIsNotRefreshable()
    {
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $dashlet->isRefreshable = false;
        $_REQUEST['module'] = 'unit_test';
        $_REQUEST['action'] = 'unit_test';
        $dashlet->seedBean = new stdClass;
        $dashlet->seedBean->object_name = 'unit_test';
        
        $this->assertEmpty($dashlet->processAutoRefresh());
    }
    
    public function testProcessAutoRefreshReturnsNothingIfAutoRefreshingIsDisabled()
    {
        $dashlet = new DashletAutoRefreshTestMock('unit_test_run');
        $GLOBALS['sugar_config']['dashlet_auto_refresh_min'] = -1;
        $_REQUEST['module'] = 'unit_test';
        $_REQUEST['action'] = 'unit_test';
        $dashlet->seedBean = new stdClass;
        $dashlet->seedBean->object_name = 'unit_test';
        
        $this->assertEmpty($dashlet->processAutoRefresh());
    }
}

class DashletAutoRefreshTestMock extends Dashlet
{
    public function isAutoRefreshable() 
    {
        return parent::isAutoRefreshable();
    }
    
    public function getAutoRefreshOptions() 
    {
        return parent::getAutoRefreshOptions();
    }
    
    public function processAutoRefresh() 
    {
        return parent::processAutoRefresh();
    }
}
