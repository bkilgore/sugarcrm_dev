<?php
class QuotesSeedDataTest extends Sugar_PHPUnit_Framework_TestCase
{

	var $quote_name;
	
	public function setUp()
	{
		global $sugar_demodata;
		$sugar_demodata['company_name_array'] = array();
		$query = 'SELECT * FROM ACCOUNTS';
		$results = $GLOBALS['db']->limitQuery($query,0,10,true,"Error retrieving Accounts");
        while($row = $GLOBALS['db']->fetchByAssoc($results)) {
        	$sugar_demodata['company_name_array'][] = $row['name'];
        }
        
        $this->quote_name = 'Test Quote ' . mktime();
        
		$sugar_demodata['quotes_seed_data']['quotes'][0] = array(
			'name' => $this->quote_name,
			'quote_stage' => 'Draft',
			'date_quote_expected_closed' => '04/30/2012',
		    'description' => 'This is a test that should contain one product group with two products and a total of three items',
		         
		
		    'bundle_data' => array(
				0 => array (
				    'bundle_name' => 'Group 1',
				    'bundle_stage' => 'Draft',
				    'comment' => 'Three Computers',
				    'products' => array (
						1 => array('name'=>'TK 1000 Desktop', 'quantity'=>'1'),
						2 => array('name'=>'TK m30 Desktop', 'quantity'=>'2'),
					),
				),
			),
		);
	}

	public function tearDown() {
		$sql = "SELECT * FROM quotes WHERE name = '{$this->quote_name}'";
		$results = $GLOBALS['db']->query($sql);
		$quote_id = '';
		
        while($row = $GLOBALS['db']->fetchByAssoc($results)) {
        	  $quote_id = $row['id'];
        }		
        
        $sql = "DELETE FROM quotes WHERE id = '{$quote_id}'";
        $GLOBALS['db']->query($sql);

        $sql = "DELETE FROM products WHERE quote_id = '{$quote_id}'";
        $GLOBALS['db']->query($sql);           
        
        $bundle_id = '';
        $sql = "SELECT bundle_id FROM product_bundle_quote WHERE quote_id = '{$quote_id}";

        $results = $GLOBALS['db']->query($sql);
        while($row = $GLOBALS['db']->fetchByAssoc($results)) {
        	  $bundle_id = $row['bundle_id'];
        	  
        	  $sql = "DELETE FROM product_bundle_product WHERE bundle_id = '{$bundle_id}'";
        	  $GLOBALS['db']->query($sql);
        	  
        	  $sql = "DELETE FROM product_bundle_quote WHERE bundle_id = '{$bundle_id}'";
        	  $GLOBALS['db']->query($sql);
        }	        
        
        if(!empty($bundle_id)) {
        	$sql = "SELECT note_id FROM product_bundle_note WHERE bundle_id = '{$bundle_id}";
	        $results = $GLOBALS['db']->query($sql);
	        while($row = $GLOBALS['db']->fetchByAssoc($results)) {  
	        	$note_id = $row['note_id'];
	        	
	        	$sql = "DELETE FROM product_bundle_notes WHERE id = '{$note_id}'";
        	    $GLOBALS['db']->query($sql);
	        }      	
	        
	        $sql = "DELETE FROM product_bundle_note WHERE bundle_id = '{$bundle_id}'";
	        $GLOBALS['db']->query($sql);
        }
        
	}
	
	public function test_create_seed_quotes() {
        require_once('install/seed_data/quotes_SeedData.php');
		$sql = "SELECT * FROM quotes WHERE name = '{$this->quote_name}'";
		$results = $GLOBALS['db']->query($sql); 
		$quote_created = false;   
        while($row = $GLOBALS['db']->fetchByAssoc($results)) {
        	  $quote_created = true;
        }	
        
        $this->assertTrue($quote_created);
	}
}
?>