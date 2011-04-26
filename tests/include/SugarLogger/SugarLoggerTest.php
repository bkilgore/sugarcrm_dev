<?php

class SugarLoggerTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        // reset the logger level
        $level = SugarConfig::getInstance()->get('logger.level');
        if (!empty($level))
            $GLOBALS['log']->setLevel($level);
    }
    
    public function providerWriteLogEntries()
    {
        return array(
            array('debug','debug','foo1',true,'[DEBUG] foo1'),
            array('debug','info','foo2',true,'[INFO] foo2'),
            array('debug','warn','foo3',true,'[WARN] foo3'),
            array('debug','error','foo4',true,'[ERROR] foo4'),
            array('debug','fatal','foo5',true,'[FATAL] foo5'),
            array('debug','security','foo6',true,'[SECURITY] foo6'),
            array('fatal','warn','foo7',false,'[WARN] foo7'),
            );
    }
    
    /**
     * @dataProvider providerWriteLogEntries
     */
    public function testWriteLogEntries(
        $currentLevel,
        $logLevel,
        $logMessage,
        $shouldMessageBeWritten,
        $messageWritten
        ) 
    {
        $GLOBALS['log']->setLevel($currentLevel);
        $GLOBALS['log']->$logLevel($logMessage);
        
        $config = SugarConfig::getInstance();
        $ext = $config->get('logger.file.ext');
        $logfile = $config->get('logger.file.name');
        $log_dir = $config->get('log_dir'); 
        $log_dir = $log_dir . (empty($log_dir)?'':'/');
        
        $logFile = file_get_contents($log_dir . $logfile . $ext);
        
        if ( $shouldMessageBeWritten )
            $this->assertContains($messageWritten,$logFile);
        else
            $this->assertNotContains($messageWritten,$logFile);
    }
    
    public function testAssertLogging()
    {
        $GLOBALS['log']->setLevel('debug');
        $GLOBALS['log']->assert('this was asserted true',true);
        $GLOBALS['log']->assert('this was asserted false',false);
        
        $config = SugarConfig::getInstance();
        $ext = $config->get('logger.file.ext');
        $logfile = $config->get('logger.file.name');
        $log_dir = $config->get('log_dir'); 
        $log_dir = $log_dir . (empty($log_dir)?'':'/');
        
        $logFile = file_get_contents($log_dir . $logfile . $ext);
        
        $this->assertContains('[DEBUG] this was asserted false',$logFile);
        $this->assertNotContains('[DEBUG] this was asserted true',$logFile);
    }
}
