<?php


require_once 'modules/SchedulersJobs/SchedulersJob.php';

class Bug27344Test extends Sugar_PHPUnit_Framework_TestCase 
{
	private $_url;
    private $_initial_server_port;	
    private $_has_initial_server_port;
    private $_cron_test_file = 'cronUnitTestBug27344.php';

    public function setUp() 
    {        

        $this->_has_initial_server_port = isset($_SERVER['SERVER_PORT']);
        if ($this->_has_initial_server_port) {
            $this->_initial_server_port = $_SERVER['SERVER_PORT'];
        }

        sugar_file_put_contents($this->_cron_test_file, "<?php echo 'Hello World!';");        
    }

    public function tearDown() 
    {
        unlink($this->_cron_test_file);

        if ($this->_has_initial_server_port) {
            $_SERVER['SERVER_PORT'] = $this->_initial_server_port;
        } else {
            unset($_SERVER['SERVER_PORT']);
        }
    }
        
    public function testLocalServerPortNotUsed() 
    {

        $url = $GLOBALS['sugar_config']['site_url'] . '/' . $this->_cron_test_file;

        $_SERVER['SERVER_PORT'] = '9090';                
        $sJob = new SchedulersJob(FALSE);
        $this->assertTrue($sJob->fireUrl($url));                
    }

}
