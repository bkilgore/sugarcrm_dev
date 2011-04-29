<?php
require_once("modules/Accounts/Account.php");

/**
 * @ticket 34670
 */
class Bug34670Test extends Sugar_PHPUnit_Framework_TestCase
{  
    public function testSelectButtonMissingOnOppoortunitiesSubpanel()
    {
        if(file_exists('modules/Accounts/metadata/subpaneldefs.php')){
			require ('modules/Accounts/metadata/subpaneldefs.php') ;
			if(!empty($layout_defs['Accounts']['subpanel_setup']['opportunities'])){
				if(!empty($layout_defs['Accounts']['subpanel_setup']['opportunities']['top_buttons'])){
					$topButtons = $layout_defs['Accounts']['subpanel_setup']['opportunities']['top_buttons'];
					foreach($topButtons as $button){
						if(!empty($button['mode'])){
							$this->assertNotEquals('MultiSelect', $button['mode'], 'Ensuring that we do not have the Select button on the Opportunities subpanel in Accounts.');
						}
					}
				}
			}
        }
    }
}
