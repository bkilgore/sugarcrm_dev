<?php
require_once 'modules/Accounts/Account.php';

class SugarTestAccountUtilities
{
    private static $_createdAccounts = array();

    private function __construct() {}

    public static function createAccount($id = '') 
    {
        $time = mt_rand();
    	$name = 'SugarAccount';
    	$email1 = 'account@sugar.com';
    	$account = new Account();
        $account->name = $name . $time;
        $account->email1 = 'account@'. $time. 'sugar.com';
        if(!empty($id))
        {
            $account->new_with_id = true;
            $account->id = $id;
        }
        $account->save();
        self::$_createdAccounts[] = $account;
        return $account;
    }

    public static function setCreatedAccount($account_ids) {
    	foreach($account_ids as $account_id) {
    		$account = new Account();
    		$account->id = $account_id;
        	self::$_createdAccounts[] = $account;
    	} // foreach
    } // fn
    
    public static function removeAllCreatedAccounts() 
    {
        $account_ids = self::getCreatedAccountIds();
        $GLOBALS['db']->query('DELETE FROM accounts WHERE id IN (\'' . implode("', '", $account_ids) . '\')');
    }
        
    public static function getCreatedAccountIds() 
    {
        $account_ids = array();
        foreach (self::$_createdAccounts as $account) {
            $account_ids[] = $account->id;
        }
        return $account_ids;
    }
}
?>