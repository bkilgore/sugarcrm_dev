<?php
require_once 'modules/Calls/Call.php';

class CallTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var Call our call object
     */
    private $callid;

    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    }

    public function tearDown()
    {
        if(!empty($this->callid)) {
            $GLOBALS['db']->query("DELETE FROM calls WHERE id='{$this->callid}'");
            $GLOBALS['db']->query("DELETE FROM vcals WHERE user_id='{$GLOBALS['current_user']->id}'");
        }
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset( $GLOBALS['current_user']);
        unset( $GLOBALS['mod_strings']);
    }

    /**
     * @group bug40999
     */
    public function testCallStatus()
    {
         $call = new Call();
         $this->callid = $call->id = create_guid();
         $call->new_with_id = 1;
         $call->status = 'Test';
         $call->save();
         // then retrieve
         $call = new Call();
         $call->retrieve($this->callid);
         $this->assertEquals('Test', $call->status);
    }

    /**
     * @group bug40999
     */
    public function testCallEmptyStatus()
    {
         $call = new Call();
         $this->callid = $call->id = create_guid();
         $call->new_with_id = 1;
         $call->save();
         // then retrieve
         $call = new Call();
         $call->retrieve($this->callid);
         $this->assertEquals('Planned', $call->status);
    }

    /**
     * @group bug40999
     * Check if empty status is handled correctly
     */
    public function testCallEmptyStatusLang()
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setModString('LBL_DEFAULT_STATUS','FAILED!','Calls');
        $langpack->save();
        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Calls');         
        
         $call = new Call();
         $this->callid = $call->id = create_guid();
         $call->new_with_id = 1;
         $call->save();
         // then retrieve
         $call = new Call();
         $call->retrieve($this->callid);
         $this->assertEquals('Planned', $call->status);
    }

    /**
     * @group bug40999
     * Check if empty status is handled correctly
     */
    public function testCallEmptyStatusLangConfig()
    {
         $langpack = new SugarTestLangPackCreator();
         $langpack->setModString('LBL_DEFAULT_STATUS','FAILED!','Calls');
         $langpack->save();
         $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Calls');         
        
         $call = new Call();
         $call->field_defs['status']['default'] = 'My Call';
         $call = new Call();
         $this->callid = $call->id = create_guid();
         $call->new_with_id = 1;
         $call->save();
         // then retrieve
         $call = new Call();
         $call->retrieve($this->callid);
         $this->assertEquals('My Call', $call->status);
    }
}