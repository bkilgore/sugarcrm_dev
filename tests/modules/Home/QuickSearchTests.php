<?php
class QuickSearchTest extends Sugar_PHPUnit_Framework_TestCase
{
	private $quickSearch;
	
	public function setUp() 
    {
    	
    }
    
    public function tearDown() 
    {
        unset($_REQUEST['data']);
        unset($_REQUEST['query']);
        $q = "delete from product_templates where name = 'MasonUnitTest';";
        $GLOBALS['db']->query($q);
    }
	
    public function testFormatResults()
    {
    	$tempPT = new ProductTemplate();
    	$tempPT->name = 'MasonUnitTest';
    	$tempPT->description = "Unit'test";
    	$tempPT->cost_price = 1000;
    	$tempPT->discount_price = 800;
    	$tempPT->list_price = 1100;
    	$tempPT->save();
    	
    	$_REQUEST['data'] = '{"conditions":[{"end":"%","name":"name","op":"like_custom","value":""}],"field_list":["name","id","type_id","mft_part_num","cost_price","list_price","discount_price","pricing_factor","description","cost_usdollar","list_usdollar","discount_usdollar","tax_class_name"],"form":"EditView","group":"or","id":"EditView_product_name[1]","limit":"30","method":"query","modules":["ProductTemplates"],"no_match_text":"No Match","order":"name","populate_list":["name_1","product_template_id_1"],"post_onblur_function":"set_after_sqs"}';
        $_REQUEST['query'] = 'MasonUnitTest';
        require_once 'modules/home/quicksearchQuery.php';
        
        $json = getJSONobj();
		$data = $json->decode(html_entity_decode($_REQUEST['data']));
		if(isset($_REQUEST['query']) && !empty($_REQUEST['query'])){
    		foreach($data['conditions'] as $k=>$v){
    			if(empty($data['conditions'][$k]['value'])){
       				$data['conditions'][$k]['value']=$_REQUEST['query'];
    			}
    		}
		}
 		$this->quickSearch = new quicksearchQuery();
		$result = $this->quickSearch->query($data);
		$resultBean = $json->decodeReal($result);
		$this->assertEquals($resultBean['fields'][0]['description'], $tempPT->description);
    }
}
?>