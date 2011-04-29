<?php
require_once 'include/Dashlets/Dashlet.php';

class DashletTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testConstructor() 
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertEquals('unit_test_run',$dashlet->id);
    }

    public function testSetConfigureIconIfConfigurable()
    {
        $dashlet = new Dashlet('unit_test_run');
        $dashlet->isConfigurable = true;
        
        $this->assertContains('SUGAR.mySugar.configureDashlet',$dashlet->setConfigureIcon());
    }
    
    public function testSetConfigureIconIfNotConfigurable()
    {
        $dashlet = new Dashlet('unit_test_run');
        $dashlet->isConfigurable = false;
        
        $this->assertNotContains('SUGAR.mySugar.configureDashlet',$dashlet->setConfigureIcon());
    }
    
    public function testSetRefreshIconIfRefreshable()
    {
        $dashlet = new Dashlet('unit_test_run');
        $dashlet->isRefreshable = true;
        
        $this->assertContains('SUGAR.mySugar.retrieveDashlet',$dashlet->setRefreshIcon());
    }
    
    public function testSetRefreshIconIfNotRefreshable()
    {
        $dashlet = new Dashlet('unit_test_run');
        $dashlet->isRefreshable = false;
        
        $this->assertNotContains('SUGAR.mySugar.retrieveDashlet',$dashlet->setRefreshIcon());
    }
    
    public function testSetDeleteIconIfHomepageNotLocked()
    {
        $dashlet = new Dashlet('unit_test_run');
        if ( isset($GLOBALS['sugar_config']['lock_homepage']) ) {
            $oldlock_homepage = $GLOBALS['sugar_config']['lock_homepage'];
        }
        $GLOBALS['sugar_config']['lock_homepage'] = false;
        
        $result = $dashlet->setDeleteIcon();
        
        if ( isset($oldlock_homepage) ) {
            $GLOBALS['sugar_config']['lock_homepage'] = $oldlock_homepage;
        }
        
        $this->assertContains('SUGAR.mySugar.deleteDashlet',$result);
    }
    
    public function testSetDeleteIconIfHomepageLocked()
    {
        $dashlet = new Dashlet('unit_test_run');
        if ( isset($GLOBALS['sugar_config']['lock_homepage']) ) {
            $oldlock_homepage = $GLOBALS['sugar_config']['lock_homepage'];
        }
        $GLOBALS['sugar_config']['lock_homepage'] = true;
        
        $result = $dashlet->setDeleteIcon();
        
        if ( isset($oldlock_homepage) ) {
            $GLOBALS['sugar_config']['lock_homepage'] = $oldlock_homepage;
        }
        
        $this->assertNotContains('SUGAR.mySugar.deleteDashlet',$result);
    }
    
    public function testGetTitleDoesNothing()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertEmpty($dashlet->getTitle('foo'));
    }
    
    public function testGetHeaderIfHomePageIsNotLocked()
    {
        $dashlet = $this->getMock('Dashlet',
                                    array('setConfigureIcon','setRefreshIcon','setDeleteIcon'),
                                    array('unit_test_run')
                                    );
        $dashlet->expects($this->any())
                ->method('setConfigureIcon')
                ->will($this->returnValue('successconfigure'));
        $dashlet->expects($this->any())
                ->method('setRefreshIcon')
                ->will($this->returnValue('successrefresh'));
        $dashlet->expects($this->any())
                ->method('setDeleteIcon')
                ->will($this->returnValue('successdelete'));
        if ( isset($GLOBALS['sugar_config']['lock_homepage']) ) {
            $oldlock_homepage = $GLOBALS['sugar_config']['lock_homepage'];
        }
        $GLOBALS['sugar_config']['lock_homepage'] = false;
        
        $result = $dashlet->getHeader('sometext');
        
        if ( isset($oldlock_homepage) ) {
            $GLOBALS['sugar_config']['lock_homepage'] = $oldlock_homepage;
        }
        
        $this->assertContains(
            '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">sometext</td>'
                . 'successconfiguresuccessrefreshsuccessdelete',
            $result
            );
        
        $this->assertContains(
            '<div onmouseover="this.style.cursor = \'move\';" id="dashlet_header_unit_test_run"',
            $result
            );
    }
    
    public function testGetHeaderIfHomePageIsLocked()
    {
        $dashlet = $this->getMock('Dashlet',
                                    array('setConfigureIcon','setRefreshIcon','setDeleteIcon'),
                                    array('unit_test_run')
                                    );
        $dashlet->expects($this->any())
                ->method('setConfigureIcon')
                ->will($this->returnValue('successconfigure'));
        $dashlet->expects($this->any())
                ->method('setRefreshIcon')
                ->will($this->returnValue('successrefresh'));
        $dashlet->expects($this->any())
                ->method('setDeleteIcon')
                ->will($this->returnValue('successdelete'));
        if ( isset($GLOBALS['sugar_config']['lock_homepage']) ) {
            $oldlock_homepage = $GLOBALS['sugar_config']['lock_homepage'];
        }
        $GLOBALS['sugar_config']['lock_homepage'] = true;
        
        $result = $dashlet->getHeader('sometext');
        
        if ( isset($oldlock_homepage) ) {
            $GLOBALS['sugar_config']['lock_homepage'] = $oldlock_homepage;
        }
        
        $this->assertContains(
            '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">sometext</td>'
                . 'successconfiguresuccessrefreshsuccessdelete',
            $result
            );
        $this->assertContains(
            '<div id="dashlet_header_unit_test_run"',
            $result
            );
    }
    
    public function testGetFooter()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertEquals(
            '</div><div class="mr"></div></div><div class="ft"><div class="bl"></div><div class="ft-center"></div><div class="br"></div></div>',
            $dashlet->getFooter()
            );
    }
    
    public function testDisplayReturnsNothing()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertEmpty($dashlet->display('foo'));
    }
    
    public function testHasAccessReturnsTrue()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertTrue($dashlet->hasAccess());
    }
    
    public function testDisplayOptionsDoesNothing()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertNull($dashlet->displayOptions());
    }
    
    public function testProcessDoesNothing()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertNull($dashlet->process());
    }
    
    public function testSaveOptionsDoesNothing()
    {
        $dashlet = new Dashlet('unit_test_run');
        
        $this->assertNull($dashlet->saveOptions(array()));
    }
}
