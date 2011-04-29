<?php

/**
 * @ticket 42427
 */
class Bug42427Test extends Sugar_PHPUnit_Framework_TestCase
{    
    public function setUp()
    {
        sugar_cache_clear('app_list_strings.en_us');
        sugar_cache_clear('app_list_strings.fr_test');
        sugar_cache_clear('app_list_strings.de_test');
        
        if ( isset($sugar_config['default_language']) ) {
            $this->_backup_default_language = $sugar_config['default_language'];
        }
    }
    
    public function tearDown()
    {
        unlink('include/language/fr_test.lang.php');
        unlink('include/language/de_test.lang.php');
        
        sugar_cache_clear('app_list_strings.en_us');
        sugar_cache_clear('app_list_strings.fr_test');
        sugar_cache_clear('app_list_strings.de_test');
        
        if ( isset($this->_backup_default_language) ) {
            $sugar_config['default_language'] = $this->_backup_default_language;
        }
    }
    
    public function testWillLoadEnUsStringIfDefaultLanguageIsNotEnUs()
    {
        file_put_contents('include/language/fr_test.lang.php', '<?php $app_list_strings = array(); ?>');
        file_put_contents('include/language/de_test.lang.php', '<?php $app_list_strings = array(); ?>');
        
        $sugar_config['default_language'] = 'fr_test';
        
        $strings = return_app_list_strings_language('de_test');
        
        $this->assertArrayHasKey('lead_source_default_key',$strings);
    }
}
