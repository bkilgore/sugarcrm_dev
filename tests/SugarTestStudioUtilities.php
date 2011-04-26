<?php

require_once('include/MVC/Controller/SugarController.php');
require_once('modules/ModuleBuilder/controller.php');
require_once('modules/ModuleBuilder/parsers/ParserFactory.php');

class SugarTestStudioUtilities
{
    private static $_fieldsAdded = array();

    private function __construct() {}
    
    /*
     * $module_name should be the module name (Contacts, Leads, etc)
     * $view should be the layout (editview, detailview, etc)
     * $field_name should be the name of the field being added
     */
    public static function addFieldToLayout($module_name, $view, $field_name) 
    {
        $parser = ParserFactory::getParser($view, $module_name);
        $parser->addField(array('name' => $field_name));
        //$parser->writeWorkingFile();
        $parser->handleSave(false);
        unset($parser);
        
        self::$_fieldsAdded[$module_name][$view][$field_name] = $field_name;
    }
    
    public static function removeAllCreatedFields()
    {
        foreach(self::$_fieldsAdded as $module_name => $views)
        {
            foreach($views as $view => $fields)
            {
                $parser = ParserFactory::getParser($view, $module_name);
                foreach($fields as $field_name)
                {
                    $parser->removeField($field_name);
                }
                $parser->handleSave(false);
                unset($parser);
            }
        }
    }

}
?>