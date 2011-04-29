<?php

require_once('service/v3/SugarWebServiceUtilv3.php');
require_once('tests/service/APIv3Helper.php');


class RESTAPIRSSTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $this->_user->status = 'Active';
        $this->_user->is_admin = 1;
        $this->_user->save();
        $this->_contact = SugarTestContactUtilities::createContact();
    }

    public function tearDown()
    {
        SugarTestContactUtilities::removeAllCreatedContacts();
        unset($GLOBALS['current_user']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    protected function _makeRESTCall($method,$parameters,$response_type = 'JSON',$api = 'v3_1')
    {
        // specify the REST web service to interact with
        $url = $GLOBALS['sugar_config']['site_url']."/service/$api/rest.php";
        // Open a curl session for making the call
        $curl = curl_init($url);
        // set URL and other appropriate options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
        // build the request URL
        $json = json_encode($parameters);
        $postArgs = "method=$method&input_type=JSON&response_type=$response_type&rest_data=$json";
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
        // Make the REST call, returning the result
        $response = curl_exec($curl);
        // Close the connection
        curl_close($curl);

        if ( $response_type == 'JSON' ) {
            return json_decode($response,true);
        }

        return $response;
    }

    protected function _login()
    {
        return $this->_makeRESTCall('login',
            array(
                'user_auth' =>
                    array(
                        'user_name' => $this->_user->user_name,
                        'password' => $this->_user->user_hash,
                        'version' => '.01',
                        ),
                'application_name' => 'SugarTestRunner',
                'name_value_list' => array(),
                )
            );
    }

    public function testGetEntryListReturnsRSScorrectly()
    {
        $result = $this->_login();
        $sessionId = $result['id'];

        $rss = $this->_makeRESTCall('get_entry_list',
                        array(
                            'session' => $sessionId,
                            'module' => 'Contacts',
                            'query' => "contacts.id = '{$this->_contact->id}'",
                            ),
                        'RSS'
                        );

        $this->assertContains('<description>1 record(s) found</description>',$rss);
        $this->assertContains("<title>{$this->_contact->name}</title>",$rss);
        $this->assertContains("<guid>{$this->_contact->id}</guid>",$rss);
    }

    public function testGetEntryReturnsRSScorrectly()
    {
        $result = $this->_login();
        $sessionId = $result['id'];

        $rss = $this->_makeRESTCall('get_entry',
                        array(
                            'session' => $sessionId,
                            'module' => 'Contacts',
                            'id' => $this->_contact->id,
                            ),
                        'RSS'
                        );

        $this->assertContains('<description>1 record(s) found</description>',$rss);
        $this->assertContains("<title>{$this->_contact->name}</title>",$rss);
        $this->assertContains("<guid>{$this->_contact->id}</guid>",$rss);
    }

    public function testGetEntriesReturnsRSScorrectly()
    {
        $result = $this->_login();
        $sessionId = $result['id'];

        $rss = $this->_makeRESTCall('get_entries',
                        array(
                            'session' => $sessionId,
                            'module' => 'Contacts',
                            'ids' => array($this->_contact->id),
                            ),
                        'RSS'
                        );

        $this->assertContains('<description>1 record(s) found</description>',$rss);
        $this->assertContains("<title>{$this->_contact->name}</title>",$rss);
        $this->assertContains("<guid>{$this->_contact->id}</guid>",$rss);
    }
}
