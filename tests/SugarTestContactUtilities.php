<?php
require_once 'modules/Contacts/Contact.php';

class SugarTestContactUtilities
{
    private static $_createdContacts = array();

    private function __construct() {}

    public static function createContact($id = '') 
    {
        $time = mt_rand();
    	$first_name = 'SugarContactFirst';
    	$last_name = 'SugarContactLast';
    	$email1 = 'contact@sugar.com';
    	$contact = new Contact();
        $contact->first_name = $first_name . $time;
        $contact->last_name = $last_name ;
        $contact->email1 = 'contact@'. $time. 'sugar.com';
        if(!empty($id))
        {
            $contact->new_with_id = true;
            $contact->id = $id;
        }
        $contact->save();
        self::$_createdContacts[] = $contact;
        return $contact;
    }

    public static function setCreatedContact($contact_ids) {
    	foreach($contact_ids as $contact_id) {
    		$contact = new Contact();
    		$contact->id = $contact_id;
        	self::$_createdContacts[] = $contact;
    	} // foreach
    } // fn
    
    public static function removeAllCreatedContacts() 
    {
        $contact_ids = self::getCreatedContactIds();
        $GLOBALS['db']->query('DELETE FROM contacts WHERE id IN (\'' . implode("', '", $contact_ids) . '\')');
    }
    
    public static function removeCreatedContactsUsersRelationships(){
    	$contact_ids = self::getCreatedContactIds();
        $GLOBALS['db']->query('DELETE FROM contacts_users WHERE contact_id IN (\'' . implode("', '", $contact_ids) . '\')');
    }
    
    public static function getCreatedContactIds() 
    {
        $contact_ids = array();
        foreach (self::$_createdContacts as $contact) {
            $contact_ids[] = $contact->id;
        }
        return $contact_ids;
    }
}
?>