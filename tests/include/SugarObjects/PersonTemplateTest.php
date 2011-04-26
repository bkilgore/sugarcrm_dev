<?php
require_once 'include/SugarObjects/templates/person/Person.php';

class PersonTemplateTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_bean;
    private $_user;
    
    public function setUp()
    {
        $this->_bean = new Person;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    }
    
    public function tearDown()
    {
        unset($this->_bean);
        unset($GLOBALS['current_user']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }
    
    public function testNameIsReturnedAsSummaryText()
    {
        $GLOBALS['current_user']->setPreference('default_locale_name_format', 'l f');
        
        $this->_bean->first_name = 'Test';
        $this->_bean->last_name = 'Contact';
        $this->_bean->title = '';
        $this->_bean->salutation = '';
        $this->assertEquals($this->_bean->get_summary_text(),'Contact Test');
    }
    
    /**
     * @group bug38648
     */
    public function testNameIsReturnedAsSummaryTextWhenSalutationIsInvalid()
    {
        $GLOBALS['current_user']->setPreference('default_locale_name_format', 's l f');
        
        $this->_bean->salutation = 'Tester';
        $this->_bean->first_name = 'Test';
        $this->_bean->last_name = 'Contact';
        $this->_bean->title = '';
        $this->assertEquals($this->_bean->get_summary_text(),'Contact Test');
    }
}
