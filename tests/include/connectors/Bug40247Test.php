<?php

class Bug40247Test extends Sugar_PHPUnit_Framework_TestCase 
{
    var $has_custom_connectors_file;
    var $has_custom_display_config_file;
    var $has_custom_accounts_detailviewdefs_file;
    var $has_custom_leads_detailviewdefs_file;
    var $has_custom_contacts_detailviewdefs_file;
    
    function setUp() {
        if(file_exists('custom/modules/connectors/metadata/connectors.php')) {
           $this->has_custom_connectors_file = true;
           copy('custom/modules/connectors/metadata/connectors.php', 'custom/modules/connectors/metadata/connectors.php.bak');
           unlink('custom/modules/connectors/metadata/connectors.php');
        }
        
        if(file_exists('custom/modules/connectors/metadata/display_config.php')) {
           $this->has_custom_display_config_file = true;
           copy('custom/modules/connectors/metadata/display_config.php', 'custom/modules/connectors/metadata/display_config.php.bak');
           unlink('custom/modules/connectors/metadata/display_config.php');
        }   

        if(file_exists('custom/modules/accounts/metadata/detailviewdefs.php')) {
           $this->has_custom_accounts_detailviewdefs_file = true;
           copy('custom/modules/accounts/metadata/detailviewdefs.php', 'custom/modules/accounts/metadata/detailviewdefs.php.bak');
           unlink('custom/modules/accounts/metadata/detailviewdefs.php');
        } 

        if(file_exists('custom/modules/contactss/metadata/detailviewdefs.php')) {
           $this->has_custom_contacts_detailviewdefs_file = true;
           copy('custom/modules/contacts/metadata/detailviewdefs.php', 'custom/modules/contacts/metadata/detailviewdefs.php.bak');
           unlink('custom/modules/contacts/metadata/detailviewdefs.php');
        } 

        if(file_exists('custom/modules/accounts/metadata/detailviewdefs.php')) {
           $this->has_custom_leads_detailviewdefs_file = true;
           copy('custom/modules/leads/metadata/detailviewdefs.php', 'custom/modules/leads/metadata/detailviewdefs.php.bak');
           unlink('custom/modules/leads/metadata/detailviewdefs.php');
        }         
        
        if(file_exists('custom/modules/Connectors/metadata/mergeviewdefs.php')) {
           unlink('custom/modules/Connectors/metadata/mergeviewdefs.php');
        }
        
        if(file_exists('custom/modules/Connectors/connectors/sources/ext/rest/linkedin/mapping.php')) {
           unlink('custom/modules/Connectors/connectors/sources/ext/rest/linkedin/mapping.php');
        }
    }
    
    function tearDown() {
        if($this->has_custom_connectors_file) {
           copy('custom/modules/connectors/metadata/connectors.php.bak', 'custom/modules/connectors/metadata/connectors.php');
           unlink('custom/modules/connectors/metadata/connectors.php.bak');
        }	
        
        if($this->has_custom_display_config_file) {
           copy('custom/modules/connectors/metadata/display_config.php.bak', 'custom/modules/connectors/metadata/display_config.php');
           unlink('custom/modules/connectors/metadata/display_config.php.bak');
        }
        
        if($this->has_custom_accounts_detailviewdefs_file) {
           copy('custom/modules/accounts/metadata/detailviewdefs.php.bak', 'custom/modules/accounts/metadata/detailviewdefs.php');
           unlink('custom/modules/accounts/metadata/detailviewdefs.php.bak');
        }  

        if($this->has_custom_contacts_detailviewdefs_file) {
           copy('custom/modules/contacts/metadata/detailviewdefs.php.bak', 'custom/modules/contacts/metadata/detailviewdefs.php');
           unlink('custom/modules/contacts/metadata/detailviewdefs.php.bak');
        }  

        if($this->has_custom_leads_detailviewdefs_file) {
           copy('custom/modules/leads/metadata/detailviewdefs.php.bak', 'custom/modules/leads/metadata/detailviewdefs.php');
           unlink('custom/modules/leads/metadata/detailviewdefs.php.bak');
        }          
    }
    
    
    function test_default_com_connectors() {
        $this->install_connectors();
        if(!file_exists('custom/modules/connectors/metadata/display_config.php')) {
           $this->markTestSkipped('Mark test skipped.  Likely no permission to write to custom directory.');
        }
        
        $viewdefs = array();
        
        require('modules/Accounts/metadata/detailviewdefs.php');
        $this->assertFalse(in_array('CONNECTOR', $viewdefs['Accounts']['DetailView']['templateMeta']['form']['buttons']), "Assert that the Get Data button is not added to Accounts detailviewdefs.php file.");
        
        $twitter_hover_link_set = false;
        $linkedin_hover_link_set = false;
        
        foreach($viewdefs['Accounts']['DetailView']['panels'] as $panels) {
        	foreach($panels as $panel) {
        		foreach($panel as $row=>$col) {
        		    if(is_array($col) && $col['name'] == 'name') {
        		       if(isset($col['displayParams']) && isset($col['displayParams']['connectors'])) {
                       	  foreach($col['displayParams']['connectors'] as $entry)
                       	  {
                       	  	   if($entry == 'ext_rest_linkedin')
                       	  	   {
                       	  	   	 $linkedin_hover_link_set = true;
                       	  	   } else if($entry == 'ext_rest_twitter') {
                       	  	   	 $twitter_hover_link_set = true;
                       	  	   }
                       	  }
        		       }
        		    }
        		}
        	}
        }
        
        $this->assertFalse($twitter_hover_link_set, "Assert that the Twitter hover link is not set for Accounts.");
        $this->assertTrue($linkedin_hover_link_set, "Assert that the LinkedIn hover link is set for Accounts.");
    	
    }
    
    private function install_connectors() {
    	require('modules/Connectors/InstallDefaultConnectors.php');
    }
    
}  
?>