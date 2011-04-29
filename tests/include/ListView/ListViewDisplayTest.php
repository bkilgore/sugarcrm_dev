<?php
require_once 'include/ListView/ListViewDisplay.php';

class ListViewDisplayTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_lvd = new ListViewDisplayMock();
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
    }

    public function tearDown()
    {
    	SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    	unset($GLOBALS['current_user']);
    	unset($GLOBALS['app_strings']);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('ListViewData',$this->_lvd->lvd);
        $this->assertInternalType('array',$this->_lvd->searchColumns);
    }

    public function testShouldProcessWhenConfigSaveQueryIsNotSet()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = null;

        $this->assertTrue($this->_lvd->shouldProcess('foo'));
        $this->assertTrue($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenConfigSaveQueryIsNotPopulateOnly()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_always';

        $this->assertTrue($this->_lvd->shouldProcess('foo'));
        $this->assertTrue($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsTrue()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = true;

        $this->assertTrue($this->_lvd->shouldProcess('foo'));
        $this->assertTrue($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsTrue()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = true;
        $_REQUEST['module'] = 'foo';

        $this->assertFalse($this->_lvd->shouldProcess('foo'));
        $this->assertFalse($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsFalseAndModulesDoNotEqual()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = false;
        $_REQUEST['module'] = 'bar';

        $this->assertTrue($this->_lvd->shouldProcess('foo'));
        $this->assertTrue($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsFalseAndModulesDoEqualAndQueryIsEmpty()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = false;
        $_REQUEST['module'] = 'foo';
        $_REQUEST['query'] = '';
        $_SESSION['last_search_mod'] = '';

        $this->assertFalse($this->_lvd->shouldProcess('foo'));
        $this->assertFalse($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsFalseAndModulesDoEqualAndQueryEqualsMsi()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = false;
        $_REQUEST['module'] = 'foo';
        $_REQUEST['query'] = 'MSI';
        $_SESSION['last_search_mod'] = '';

        $this->assertFalse($this->_lvd->shouldProcess('foo'));
        $this->assertFalse($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsFalseAndModulesDoNotEqualAndQueryDoesNotEqualsMsi()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = false;
        $_REQUEST['module'] = 'foo';
        $_REQUEST['query'] = 'xMSI';
        $_SESSION['last_search_mod'] = '';

        $this->assertTrue($this->_lvd->shouldProcess('foo'));
        $this->assertTrue($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsFalseAndModulesDoEqualAndLastSearchModEqualsModule()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = false;
        $_REQUEST['module'] = 'foo';
        $_REQUEST['query'] = '';
        $_SESSION['last_search_mod'] = 'foo';

        $this->assertTrue($this->_lvd->shouldProcess('foo'));
        $this->assertTrue($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testShouldProcessWhenGlobalDisplayListViewIsFalseAndRequestClearQueryIsFalseAndModulesDoEqualAndLastSearchModDoesNotEqualsModule()
    {
        if ( isset($GLOBALS['sugar_config']['save_query']) ) {
            $oldsavequery = $GLOBALS['sugar_config']['save_query'];
        }
        $GLOBALS['sugar_config']['save_query'] = 'populate_only';
        $GLOBALS['displayListView'] = false;
        $_REQUEST['clear_query'] = false;
        $_REQUEST['module'] = 'foo';
        $_REQUEST['query'] = '';
        $_SESSION['last_search_mod'] = 'bar';

        $this->assertFalse($this->_lvd->shouldProcess('foo'));
        $this->assertFalse($this->_lvd->should_process);

        if ( isset($oldsavequery) ) {
            $GLOBALS['sugar_config']['save_query'] = $oldsavequery;
        }
    }

    public function testProcess()
    {
        $data = array(
            'data' => array(1,2,3),
            'pageData' => array('bean' => array('moduleDir'=>'testmoduledir'))
            );
        $this->_lvd->process('foo',$data,'testmetestme');

        $this->assertEquals(3,$this->_lvd->rowCount);
        $this->assertEquals('testmoduledir2_TESTMETESTME_offset',$this->_lvd->moduleString);
    }

    public function testDisplayIfShouldNotProcess()
    {
        $this->_lvd->should_process = false;

        $this->assertEmpty($this->_lvd->display());
    }

    public function testDisplayIfMultiSelectFalse()
    {
        $this->_lvd->should_process = true;
        $this->_lvd->multiSelect = false;

        $this->assertEmpty($this->_lvd->display());
    }

    public function testDisplayIfShowMassUpdateFormFalse()
    {
        $this->_lvd->should_process = true;
        $this->_lvd->show_mass_update_form = false;

        $this->assertEmpty($this->_lvd->display());
    }

    public function testDisplayIfShowMassUpdateFormTrueAndMultiSelectTrue()
    {
        $this->_lvd->should_process = true;
        $this->_lvd->show_mass_update_form = true;
        $this->_lvd->multiSelect = true;
        $this->_lvd->multi_select_popup = true;
        $this->_lvd->mass = $this->getMock('MassUpdate');
        $this->_lvd->mass->expects($this->any())
                         ->method('getDisplayMassUpdateForm')
                         ->will($this->returnValue('foo'));
        $this->_lvd->mass->expects($this->any())
                         ->method('getMassUpdateFormHeader')
                         ->will($this->returnValue('bar'));

        $this->assertEquals('foobar',$this->_lvd->display());
    }

    public function testBuildSelectLink()
    {
        $output = $this->_lvd->buildSelectLink();

        $this->assertContains("<a id='select_link' onclick='return select_overlib();'",$output);
        $this->assertContains("sListView.check_all(document.MassUpdate, \"mass[]\", true, 0)",$output);
        $this->assertContains("sListView.check_entire_list(document.MassUpdate, \"mass[]\",true,0);",$output);
    }

    public function testBuildSelectLinkWithParameters()
    {
        $output = $this->_lvd->buildSelectLink('testtest',1,2);

        $this->assertContains("<a id='testtest' onclick='return select_overlib();'",$output);
        $this->assertContains("sListView.check_all(document.MassUpdate, \"mass[]\", true, 2)",$output);
        $this->assertContains("sListView.check_entire_list(document.MassUpdate, \"mass[]\",true,1);",$output);
    }

    public function testBuildSelectLinkWithPageTotalLessThanZero()
    {
        $output = $this->_lvd->buildSelectLink('testtest',1,-1);

        $this->assertContains("<a id='testtest' onclick='return select_overlib();'",$output);
        $this->assertContains("sListView.check_all(document.MassUpdate, \"mass[]\", true, 1)",$output);
        $this->assertContains("sListView.check_entire_list(document.MassUpdate, \"mass[]\",true,1);",$output);
    }

    public function testBuildExportLink()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->module_dir = 'testtest';
        $output = $this->_lvd->buildExportLink();

        $this->assertContains("return sListView.send_form(true, 'testtest', 'index.php?entryPoint=export',",$output);
    }

    public function testBuildMassUpdateLink()
    {
        $output = $this->_lvd->buildMassUpdateLink();

        $this->assertContains("<a href='#massupdate_form'",$output);
    }

    public function testComposeEmailIfFieldDefsNotAnArray()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->field_defs = false;
        
        $this->assertEmpty($this->_lvd->buildComposeEmailLink(0));
    }

    public function testComposeEmailIfFieldDefsAreAnEmptyArray()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->field_defs = array();

        $this->assertEmpty($this->_lvd->buildComposeEmailLink(0));
    }

    public function testComposeEmailIfFieldDefsDoNotHaveAnEmailAddressesRelationship()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->object_name = 'foobar';
        $this->_lvd->seed->field_defs = array(
            'field1' => array(
                'type' => 'text',
                ),
            );

        $this->assertEmpty($this->_lvd->buildComposeEmailLink(0));
    }

    public function testComposeEmailIfFieldDefsIfUsingSugarEmailClient()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->object_name = 'foobar';
        $this->_lvd->seed->module_dir = 'foobarfoobar';
        $this->_lvd->seed->field_defs = array(
            'field1' => array(
                'type' => 'link',
                'relationship' => 'foobar_emailaddresses',
                ),
            );
        $GLOBALS['dictionary']['foobar']['relationships']['foobar_emailaddresses']['rhs_module'] = 'EmailAddresses';
        $GLOBALS['current_user']->setPreference('email_link_type','sugar');

        $output = $this->_lvd->buildComposeEmailLink(5);

        $this->assertContains(', \'foobarfoobar\', \'5\', ',$output);

        unset($GLOBALS['dictionary']['foobar']);
    }

    public function testComposeEmailIfFieldDefsIfUsingExternalEmailClient()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->object_name = 'foobar';
        $this->_lvd->seed->module_dir = 'foobarfoobar';
        $this->_lvd->seed->field_defs = array(
            'field1' => array(
                'type' => 'link',
                'relationship' => 'foobar_emailaddresses',
                ),
            );
        $GLOBALS['dictionary']['foobar']['relationships']['foobar_emailaddresses']['rhs_module'] = 'EmailAddresses';
        $GLOBALS['current_user']->setPreference('email_link_type','mailto');

        $output = $this->_lvd->buildComposeEmailLink(5);

        $this->assertContains('sListView.use_external_mail_client',$output);

        unset($GLOBALS['dictionary']['foobar']);
    }

    public function testBuildDeleteLink()
    {
        $output = $this->_lvd->buildDeleteLink();

        $this->assertContains("return sListView.send_mass_update",$output);
    }

    public function testBuildSelectedObjectsSpan()
    {
        $output = $this->_lvd->buildSelectedObjectsSpan(1,1);

        $this->assertContains("<input  style='border: 0px; background: transparent; font-size: inherit; color: inherit' type='text' id='selectCountTop' readonly name='selectCount[]' value='1' />",$output);
    }

    public function testBuildMergeDuplicatesLinkWithNoAccess()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testBuildMergeDuplicatesLinkWhenModuleDoesNotHaveItEnabled()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->object_name = 'foobar';
        $this->_lvd->seed->module_dir = 'foobarfoobar';
        $GLOBALS['dictionary']['foobar']['duplicate_merge'] = false;
        $GLOBALS['current_user']->is_admin = 1;

        $this->assertEmpty($this->_lvd->buildMergeDuplicatesLink());
    }

    public function testBuildMergeDuplicatesLink()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->object_name = 'foobar';
        $this->_lvd->seed->module_dir = 'foobarfoobar';
        $GLOBALS['dictionary']['foobar']['duplicate_merge'] = true;
        $GLOBALS['current_user']->is_admin = 1;

        $output = $this->_lvd->buildMergeDuplicatesLink();

        $this->assertContains("\"foobarfoobar\",\"\");}",$output);
    }

    public function testBuildMergeDuplicatesLinkBuildsReturnString()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->object_name = 'foobar';
        $this->_lvd->seed->module_dir = 'foobarfoobar';
        $GLOBALS['dictionary']['foobar']['duplicate_merge'] = true;
        $GLOBALS['current_user']->is_admin = 1;
        $_REQUEST['module'] = 'foo';
        $_REQUEST['action'] = 'bar';
        $_REQUEST['record'] = '1';

        $output = $this->_lvd->buildMergeDuplicatesLink();

        $this->assertContains("\"foobarfoobar\",\"&return_module=foo&return_action=bar&return_id=1\");}",$output);
    }
    public function testBuildMergeLinkWhenUserDisabledMailMerge()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->module_dir = 'foobarfoobar';
        $GLOBALS['current_user']->setPreference('mailmerge_on','off');

        $this->assertEmpty($this->_lvd->buildMergeLink());
    }

    public function testBuildMergeLinkWhenSystemDisabledMailMerge()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->module_dir = 'foobarfoobar';

        $GLOBALS['current_user']->setPreference('mailmerge_on','on');

        $settings_cache = sugar_cache_retrieve('admin_settings_cache');
        if ( empty($settings_cache) ) {
            $settings_cache = array();
        }
        $settings_cache['system_mailmerge_on'] = false;
        sugar_cache_put('admin_settings_cache',$settings_cache);

        $this->assertEmpty($this->_lvd->buildMergeLink());

        sugar_cache_clear('admin_settings_cache');
    }

    public function testBuildMergeLinkWhenModuleNotInModulesArray()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->module_dir = 'foobarfoobar';

        $GLOBALS['current_user']->setPreference('mailmerge_on','on');

        $settings_cache = sugar_cache_retrieve('admin_settings_cache');
        if ( empty($settings_cache) ) {
            $settings_cache = array();
        }
        $settings_cache['system_mailmerge_on'] = true;
        sugar_cache_put('admin_settings_cache',$settings_cache);

        $this->assertEmpty($this->_lvd->buildMergeLink(array('foobar' => 'foobar')));

        sugar_cache_clear('admin_settings_cache');
    }

    public function testBuildMergeLink()
    {
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->module_dir = 'foobarfoobar';

        $GLOBALS['current_user']->setPreference('mailmerge_on','on');

        $settings_cache = sugar_cache_retrieve('admin_settings_cache');
        if ( empty($settings_cache) ) {
            $settings_cache = array();
        }
        $settings_cache['system_mailmerge_on'] = true;
        sugar_cache_put('admin_settings_cache',$settings_cache);

        $output = $this->_lvd->buildMergeLink(array('foobarfoobar' => 'foobarfoobar'));
        $this->assertContains("index.php?action=index&module=MailMerge&entire=true",$output);

        sugar_cache_clear('admin_settings_cache');
    }

    public function testBuildTargetLink()
    {
        $_POST['module'] = 'foobar';
        $this->_lvd->seed = new stdClass;
        $this->_lvd->seed->module_dir = 'foobarfoobar';

        $output = $this->_lvd->buildTargetList();

        $this->assertContains("input.setAttribute ( 'name' , 'module' );			    input.setAttribute ( 'value' , 'foobarfoobar' );",$output);
        $this->assertContains("input.setAttribute ( 'name' , 'current_query_by_page' );			    input.setAttribute ( 'value', '".base64_encode(serialize($_POST))."' );",$output);
    }

    public function testDisplayEndWhenNotShowingMassUpdateForm()
    {
        $this->_lvd->show_mass_update_form = false;

        $this->assertEmpty($this->_lvd->displayEnd());
    }

    public function testDisplayEndWhenShowingMassUpdateForm()
    {
        $this->_lvd->show_mass_update_form = true;
        $this->_lvd->mass = $this->getMock('MassUpdate');
        $this->_lvd->mass->expects($this->any())
                         ->method('getMassUpdateForm')
                         ->will($this->returnValue('foo'));
        $this->_lvd->mass->expects($this->any())
                         ->method('endMassUpdateForm')
                         ->will($this->returnValue('bar'));

        $this->assertEquals('foobar',$this->_lvd->displayEnd());
    }

    public function testGetMultiSelectData()
    {
        $this->_lvd->moduleString = 'foobar';

        $output = $this->_lvd->getMultiSelectData();

        $this->assertEquals($output, "<script>YAHOO.util.Event.addListener(window, \"load\", sListView.check_boxes);</script>\n".
				"<textarea style='display: none' name='uid'></textarea>\n" .
				"<input type='hidden' name='select_entire_list' value='0'>\n".
				"<input type='hidden' name='foobar' value='0'>\n".
                "<input type='hidden' name='show_plus' value=''>\n",$output);
    }

    public function testGetMultiSelectDataWithRequestParameterUidSet()
    {
        $this->_lvd->moduleString = 'foobar';
        $_REQUEST['uid'] = '1234';

        $output = $this->_lvd->getMultiSelectData();

        $this->assertEquals("<script>YAHOO.util.Event.addListener(window, \"load\", sListView.check_boxes);</script>\n".
				"<textarea style='display: none' name='uid'>1234</textarea>\n" .
				"<input type='hidden' name='select_entire_list' value='0'>\n".
				"<input type='hidden' name='foobar' value='0'>\n" .
                "<input type='hidden' name='show_plus' value=''>\n",$output);        
    }

    public function testGetMultiSelectDataWithRequestParameterSelectEntireListSet()
    {
        $this->_lvd->moduleString = 'foobar';
        $_REQUEST['select_entire_list'] = '1234';

        $output = $this->_lvd->getMultiSelectData();

        $this->assertEquals("<script>YAHOO.util.Event.addListener(window, \"load\", sListView.check_boxes);</script>\n".
				"<textarea style='display: none' name='uid'></textarea>\n" .
				"<input type='hidden' name='select_entire_list' value='1234'>\n".
				"<input type='hidden' name='foobar' value='0'>\n" .
                "<input type='hidden' name='show_plus' value=''>\n",$output);        
    }

    public function testGetMultiSelectDataWithRequestParameterMassupdateSet()
    {
        $this->_lvd->moduleString = 'foobar';
        $_REQUEST['uid'] = '1234';
        $_REQUEST['select_entire_list'] = '5678';
        $_REQUEST['massupdate'] = 'true';

        $output = $this->_lvd->getMultiSelectData();

        $this->assertEquals("<script>YAHOO.util.Event.addListener(window, \"load\", sListView.check_boxes);</script>\n".
				"<textarea style='display: none' name='uid'></textarea>\n" .
				"<input type='hidden' name='select_entire_list' value='0'>\n".
				"<input type='hidden' name='foobar' value='0'>\n".
                "<input type='hidden' name='show_plus' value=''>\n",$output);        
    }
}

class ListViewDisplayMock extends ListViewDisplay
{
    public function buildExportLink()
    {
        return parent::buildExportLink();
    }

    public function buildMassUpdateLink()
    {
        return parent::buildMassUpdateLink();
    }

    public function buildComposeEmailLink($totalCount)
    {
        return parent::buildComposeEmailLink($totalCount);
    }

    public function buildDeleteLink()
    {
        return parent::buildDeleteLink();
    }

    public function buildMergeDuplicatesLink()
    {
        return parent::buildMergeDuplicatesLink();
    }

    public function buildMergeLink(array $modules_array = null)
    {
        return parent::buildMergeLink($modules_array);
    }

    public function buildTargetList()
    {
        return parent::buildTargetList();
    }
}
