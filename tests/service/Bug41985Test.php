<?php
require_once 'modules/DynamicFields/FieldCases.php';
require_once 'service/v4/SugarWebServiceImplv4.php';

class Bug41985Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_contact;
    protected $_account;

    public function setUp()
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;

        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->status = 'Active';
        $GLOBALS['current_user']->is_admin = 1;
        $GLOBALS['current_user']->save();

        $this->field = get_widget('varchar');
        $this->field->id = 'Accountstest_custom_c';
        $this->field->name = 'test_custom_c';
        $this->field->vanme = 'LBL_TEST_CUSTOM_C';
        $this->field->comments = NULL;
        $this->field->help = NULL;
        $this->field->custom_module = 'Accounts';
        $this->field->type = 'varchar';
        $this->field->label = 'LBL_TEST_CUSTOM_C';
        $this->field->len = 255;
        $this->field->required = 0;
        $this->field->default_value = NULL;
        $this->field->date_modified = '2009-09-14 02:23:23';
        $this->field->deleted = 0;
        $this->field->audited = 0;
        $this->field->massupdate = 0;
        $this->field->duplicate_merge = 0;
        $this->field->reportable = 1;
        $this->field->importable = 'true';
        $this->field->ext1 = NULL;
        $this->field->ext2 = NULL;
        $this->field->ext3 = NULL;
        $this->field->ext4 = NULL;

        $this->df = new DynamicField('Accounts');
        $this->mod = new Account();
        $this->df->setup($this->mod);
        $this->df->addFieldObject($this->field);
        $this->df->buildCache('Accounts');
        VardefManager::clearVardef();
        VardefManager::refreshVardefs('Accounts', 'Account');
        $this->mod->field_defs = $GLOBALS['dictionary']['Account']['fields'];

        $this->_contact = SugarTestContactUtilities::createContact();
        $this->_account = SugarTestAccountUtilities::createAccount();

        $this->_contact->load_relationship('accounts');
        $this->_contact->accounts->add($this->_account->id);

        $this->_account->test_custom_c = 'Custom Field';
        $this->_account->save();
    }

    public function tearDown()
    {
        $this->df->deleteField($this->field);

        $account_ids = SugarTestAccountUtilities::getCreatedAccountIds();
        $contact_ids = SugarTestContactUtilities::getCreatedContactIds();
        $GLOBALS['db']->query('DELETE FROM accounts_contacts WHERE contact_id IN (\'' . implode("', '", $contact_ids) . '\') OR  account_id IN (\'' . implode("', '", $account_ids) . '\')');

        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestAccountUtilities::removeAllCreatedAccounts();

        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);

        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['beanList']);
    }

    public function testGetRelationshipsWithCustomFields()
    {
        $web_service_util = new SugarWebServiceUtilv4();

        $result = $web_service_util->getRelationshipResults($this->_contact, 'accounts', array('id', 'name', 'test_custom_c'));

        $this->assertTrue(isset($result['rows'][0]));
        $this->assertTrue(isset($result['rows'][0]['test_custom_c']));
        $this->assertEquals($result['rows'][0]['test_custom_c'], 'Custom Field');
    }
}
