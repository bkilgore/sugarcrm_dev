<?php
require_once('modules/DynamicFields/FieldCases.php');


class Bug39766Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @group bug35265
     */    
    public function testFloatPrecisionMapping()
    {
        $_REQUEST = array('precision' => 2, 'type' => 'float');
        require_once ('modules/DynamicFields/FieldCases.php') ;
        $field = get_widget ( $_REQUEST [ 'type' ] ) ;
        $field->populateFromPost () ;
        
        $this->assertEquals($field->ext1, 2, 'Asserting that the ext1 value was set to the proper precision');
        $this->assertEquals($field->precision, 2, 'Asserting that the precision value was set to the proper precision');
    }
}
