<?php
require_once 'modules/Users/User.php';

class SugarTestUserUtilities
{
    private static $_createdUsers = array();

    private function __construct() {}
    
    public function __destruct()
    {
        self::removeAllCreatedAnonymousUsers();
    }

    public static function createAnonymousUser($save = true)
    {
        if (isset($_REQUEST['action'])) { 
        unset($_REQUEST['action']);
        }
        
        $time = mt_rand();
    	$userId = 'SugarUser';
    	$user = new User();
        $user->user_name = $userId . $time;
        $user->user_hash = md5($userId.$time);
        $user->first_name = $userId;
        $user->last_name = $time;
        $user->status='Active';
        if ( $save ) {
            $user->save();
        }

        $user->fill_in_additional_detail_fields();
        self::$_createdUsers[] = $user;
        return $user;
    }
    
    public function removeAllCreatedAnonymousUsers() 
    {
        $user_ids = self::getCreatedUserIds();
        if ( count($user_ids) > 0 ) {
            $GLOBALS['db']->query('DELETE FROM users WHERE id IN (\'' . implode("', '", $user_ids) . '\')');
            $GLOBALS['db']->query('DELETE FROM user_preferences WHERE assigned_user_id IN (\'' . implode("', '", $user_ids) . '\')');
        }
        self::$_createdUsers = array();
    }
    
    public static function getCreatedUserIds() 
    {
        $user_ids = array();
        foreach (self::$_createdUsers as $user)
            if ( is_object($user) && $user instanceOf User )
                $user_ids[] = $user->id;
        
        return $user_ids;
    }
}