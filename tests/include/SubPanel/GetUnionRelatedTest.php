<?php
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('include/SubPanel/SubPanelDefinitions.php');

/**
 * test get_union_related_list() with subpanels, functions, distinct clause
 */
class GetUnionRelatedTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * Bean to use for tests
     * @var SugarBean
     */
    protected $bean;

	public function setUp()
	{
	    global $moduleList, $beanList, $beanFiles;
        require('include/modules.php');
	    $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->bean = new Contact();
	}

	public function tearDown()
	{
		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
	}

    public function testGetUnionRelatedList()
    {
        $subpanel = array(
			'order' => 20,
			'sort_order' => 'desc',
			'sort_by' => 'date_entered',
			'type' => 'collection',
			'subpanel_name' => 'history',   //this values is not associated with a physical file.
			'top_buttons' => array(),
			'collection_list' => array(
				'meetings' => array(
					'module' => 'Meetings',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'meetings',
				),
				'emails' => array(
					'module' => 'Emails',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'emails',
				    'get_distinct_data' => true,
				),
				'linkedemails_contacts' => array(
	                'module' => 'Emails',
	                'subpanel_name' => 'ForHistory',
				    'generate_select'=>true,
				    'get_distinct_data' => true,
                    'get_subpanel_data' => 'function:GetUnionRelatedTest_get_select',
        			'function_parameters' => array('import_function_file' => __FILE__),
				),
			)
        );
        $subpanel_def = new aSubPanel("testpanel", $subpanel, $this->bean);
        $query = $this->bean->get_union_related_list($this->bean, "", '', "", 0, 5, -1, 0, $subpanel_def);
        $result = $this->bean->db->query($query["query"]);
        $this->assertTrue($result != false, "Bad query: {$query["query"]}");
    }
}

function GetUnionRelatedTest_get_select()
{
    $return_array['select']='SELECT DISTINCT emails.id';
    $return_array['from']='FROM emails ';
	$return_array['join'] = " JOIN emails_email_addr_rel eear ON eear.email_id = emails.id AND eear.deleted=0
		    	JOIN email_addr_bean_rel eabr ON eabr.email_address_id=eear.email_address_id AND eabr.bean_module = 'Contacts'
		    		AND eabr.deleted=0 AND eabr.bean_id = '1'";
    $return_array['where']="";
    $return_array['join_tables'] = array();
    return $return_array;
}
