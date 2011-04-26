<?php
require_once 'modules/Campaigns/Campaign.php';

class SugarTestCampaignUtilities
{
    private static $_createdCampaigns = array();

    private function __construct() {}

    public static function createCampaign($id = '') 
    {
        $time = mt_rand();
    	$name = 'SugarCampaign';
    	$campaign = new Campaign();
        $campaign->name = $name . $time;
        $campaign->status = 'Active';
        $campaign->campaign_type = 'Email';
        $campaign->end_date = '2010-11-08';
        if(!empty($id))
        {
            $campaign->new_with_id = true;
            $campaign->id = $id;
        }
        $campaign->save();
        self::$_createdCampaigns[] = $campaign;
        return $campaign;
    }

    public static function removeAllCreatedCampaigns() 
    {
        $campaign_ids = self::getCreatedCampaignIds();
        $GLOBALS['db']->query('DELETE FROM campaigns WHERE id IN (\'' . implode("', '", $campaign_ids) . '\')');
    }
    
    public static function getCreatedCampaignIds() 
    {
        $campaign_ids = array();
        foreach (self::$_createdCampaigns as $campaign) {
            $campaign_ids[] = $campaign->id;
        }
        return $campaign_ids;
    }
}
?>