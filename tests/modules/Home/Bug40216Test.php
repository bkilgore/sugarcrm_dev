<?php
require_once('modules/Home/views/view.additionaldetailsretrieve.php');

/**
 * @ticket bug40216
 */
class Bug40216Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $moduleName;
    
    public function setUp() 
    {
    	   $this->moduleName = 'TestModule'.mt_rand();
        
        sugar_mkdir("modules/{$this->moduleName}/metadata",null,true);
        sugar_mkdir("custom/modules/{$this->moduleName}/metadata",null,true);
    }
    
    public function tearDown() 
    {
        rmdir_recursive("modules/{$this->moduleName}");
        rmdir_recursive("custom/modules/{$this->moduleName}");
    }
	
    public function testAdditionalDetailsMetadataFileIsFound()
    {
    	   sugar_touch("modules/{$this->moduleName}/metadata/additionalDetails.php");
    	
    	   $viewObject = new Bug40216Mock;
    	
    	   $this->assertEquals(
    	       "modules/{$this->moduleName}/metadata/additionalDetails.php",
    	       $viewObject->getAdditionalDetailsMetadataFile($this->moduleName)
    	       );
    }
    
    public function testCustomAdditionalDetailsMetadataFileIsFound()
    {
    	   sugar_touch("custom/modules/{$this->moduleName}/metadata/additionalDetails.php");
    	
    	   $viewObject = new Bug40216Mock;
    	
    	   $this->assertEquals(
    	       "custom/modules/{$this->moduleName}/metadata/additionalDetails.php",
    	       $viewObject->getAdditionalDetailsMetadataFile($this->moduleName)
    	       );
    }
    
    public function testCustomAdditionalDetailsMetadataFileIsUsedBeforeNonCustomOne()
    {
    	   sugar_touch("modules/{$this->moduleName}/metadata/additionalDetails.php");
    	   sugar_touch("custom/modules/{$this->moduleName}/metadata/additionalDetails.php");
    	
    	   $viewObject = new Bug40216Mock;
    	
    	   $this->assertEquals(
    	       "custom/modules/{$this->moduleName}/metadata/additionalDetails.php",
    	       $viewObject->getAdditionalDetailsMetadataFile($this->moduleName)
    	       );
    }
}

class Bug40216Mock extends HomeViewAdditionaldetailsretrieve
{
    public function getAdditionalDetailsMetadataFile(
        $moduleName
        )
    {
        return parent::getAdditionalDetailsMetadataFile($moduleName);
    }
}