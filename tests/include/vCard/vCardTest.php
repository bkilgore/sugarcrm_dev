<?php
require_once 'include/vCard.php';

class vCardTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['beanList']['vCardMockModule'] = 'vCardMockModule';
        $GLOBALS['beanFiles']['vCardMockModule'] = 'tests/include/vCard/vCardTest.php';
    }

    public function tearDown()
    {
        unset($GLOBALS['current_user']);
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    /**
     * @ticket 10419
     */
	public function testImportedVcardWithDifferentCharsetIsTranslatedToTheDefaultCharset()
    {
        $filename  = dirname(__FILE__)."/ISO88591SampleFile.vcf";
        $module = "vCardMockModule";

        $vcard = new vCard();
        $record = $vcard->importVCard($filename,$module);

        $bean = new vCardMockModule;
        $bean = $bean->retrieve($record);

        $this->assertEquals('Hans Müster',$bean->first_name.' '.$bean->last_name);
    }

    public function testImportedVcardWithSameCharsetIsNotTranslated()
    {
        $filename  = dirname(__FILE__)."/UTF8SampleFile.vcf";
        $module = "vCardMockModule";

        $vcard = new vCard();
        $record = $vcard->importVCard($filename,$module);

        $bean = new vCardMockModule;
        $bean = $bean->retrieve($record);

        $this->assertEquals('Hans Müster',$bean->first_name.' '.$bean->last_name);
    }

    public function vCardNames()
    {
        return array(
            array('', "Last Name"),
            array('First Name', "Last Name"),
            array("Иван", "Č, Ć ŐŐŐ Lastname"),
        );
    }

    /**
     * @ticket 24487
	 * @dataProvider vCardNames
     */
    public function testExportVcard($fname, $lname)
    {
        $vcard = new vCard();

        $data = new vCardMockModule();
        $data->first_name = $fname;
        $data->last_name = $lname;
        $GLOBALS['current_user']->setPreference('default_export_charset', 'UTF-8');
        $id = $data->save();

        $vcard->loadContact($id, 'vCardMockModule');
        $cardtext = $vcard->toString();

        $this->assertContains("N;CHARSET=utf-8:$lname;$fname", $cardtext, "Cannot find N name", true);
        $this->assertContains("FN;CHARSET=utf-8: $fname $lname", $cardtext, "Cannot find FN name", true);
    }
    
    public function testClear()
    {
        $vcard = new vCard();
        $vcard->setProperty('dog','cat');
        $vcard->clear();
        
        $this->assertNull($vcard->getProperty('dog'));
    }
    
    public function testSetProperty()
    {
        $vcard = new vCard();
        $vcard->setProperty('dog','cat');
        
        $this->assertEquals('cat',$vcard->getProperty('dog'));
    }
    
    public function testGetPropertyThatDoesNotExist()
    {
        $vcard = new vCard();
        
        $this->assertNull($vcard->getProperty('dog'));
    }
    
    public function testSetTitle()
    {
        $vcard = new vCard();
        $vcard->setTitle('cat');
        
        $this->assertEquals('cat',$vcard->getProperty('TITLE'));
    }
    
    public function testSetORG()
    {
        $vcard = new vCard();
        $vcard->setORG('foo','bar');
        
        $this->assertEquals('foo;bar',$vcard->getProperty('ORG'));
    }
}

class vCardMockModule extends Person
{
    public static $_savedObjects = array();
    
    public $first_name;
    public $last_name;
    public $salutation;
    public $phone_fax;
    public $phone_home;
    public $phone_mobile;
    public $phone_work;
    public $email1;
    public $primary_address_street;
    public $primary_address_city;
    public $primary_address_state;
    public $primary_address_postalcode;
    public $primary_address_country;
    public $department;
    public $title;

    public function save()
    {
        $this->id = create_guid();

        self::$_savedObjects[$this->id] = $this;

        return $this->id;
    }

    public function retrieve($id = -1, $encode=true,$deleted=true)
	{
        if ( isset(self::$_savedObjects[$id]) ) {
            foreach(get_object_vars(self::$_savedObjects[$id]) as $var => $val) {
                $this->$var = $val;
            }
            return self::$_savedObjects[$id];
        }

        return null;
    }

    public function ACLFilterFields()
    {
    }
}