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
    }
    
    /**
     * @group bug10419
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
}

class vCardMockModule extends Person
{
    private static $_savedObjects = array();
    
    public function save()
    {
        $this->id = create_guid();
        
        self::$_savedObjects[$this->id] = $this;
        
        return $this->id;
    }
    
    public function retrieve($id = -1, $encode=true,$deleted=true)
	{
        if ( isset(self::$_savedObjects[$id]) ) 
            return self::$_savedObjects[$id];
        
        return null;
    }
}