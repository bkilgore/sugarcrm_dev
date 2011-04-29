<?php

//Helper functions used by both SOAP and REST Unit Test Calls.

class APIv3Helper
{
    
    function populateSeedDataForSearchTest($user_id)
    {
        $results = array();
        $a1_id = create_guid();
        $a1 = new Account();
        $a1->id = $a1_id;
        $a1->new_with_id = TRUE;
        $a1->name = "UNIT TEST $a1_id";
        $a1->assigned_user_id = $user_id;
        $a1->save();
        $results[] = array('id' => $a1_id, 'fieldName' => 'name', 'fieldValue' => "UNIT TEST $a1_id");
        
        $a2_id = create_guid();
        $a2 = new Account();
        $a2->new_with_id = TRUE;
        $a2->id = $a2_id;
        $a2->name = "UNIT TEST $a2_id";
        $a2->assigned_user_id = 'unittest';
        $a2->save();
        $results[] = array('id' => $a2_id, 'fieldName' => 'name', 'fieldValue' => "UNIT TEST $a2_id");
        
        $c1_id = create_guid();
        $c1 = new Contact();
        $c1->id = $c1_id;
        $c1->new_with_id = TRUE;
        $c1->first_name = "UNIT TEST";
        $c1->last_name = "UNIT_TEST";
        $c1->assigned_user_id = $user_id;
        $c1->save();
        $results[] = array('id' => $c1_id, 'fieldName' => 'name', 'fieldValue' => $c1->first_name .' ' . $c1->last_name);
        
        $op1_id = create_guid();
        $op1 = new Opportunity();
        $op1->new_with_id = TRUE;
        $op1->id = $op1_id;
        $op1->name = "UNIT TEST $op1_id";
        $op1->assigned_user_id = $user_id;
        $op1->save();
        $results[] = array('id' => $op1_id, 'fieldName' => 'name', 'fieldValue' => "UNIT TEST $op1_id");
        
        $op2_id = create_guid();
        $op2 = new Opportunity();
        $op2->new_with_id = TRUE;
        $op2->id = $op2_id;
        $op2->name = "UNIT TEST $op2_id";
        $op2->assigned_user_id = 'unittest';
        $op2->save();
        $results[] = array('id' => $op2_id, 'fieldName' => 'name', 'fieldValue' => "UNIT TEST $op2_id");
        
        return $results;
    }    
    
    /**
     * Linear search function used to find a bean id in an entry list array.
     *
     * @param array $list
     * @param string $bean_id
     */
    function findBeanIdFromEntryList($list,$bean_id,$module)
    {
        $found = FALSE;
        foreach ($list as $moduleEntry)
        {
            if($moduleEntry['name'] == $module)
            {
                foreach ($moduleEntry['records'] as $entry)
                {
                    foreach ($entry as $fieldEntry)
                    {
                        if($fieldEntry['name'] == 'id' && $fieldEntry['value'] == $bean_id )
                            return TRUE;
                    }
                }
            }
        }
        
        return $found;
    }
    
    /**
     * Linear search function used to find a particular field in an entry list array.
     *
     * @param array $list
     * @param string $bean_id
     */
    function findFieldByNameFromEntryList($list,$bean_id,$module,$fieldName)
    {
        $found = FALSE;

        foreach ($list as $moduleEntry)
        {
            if($moduleEntry['name'] == $module)
            {
                foreach ($moduleEntry['records'] as $entry)
                {
                    $value = $this->_retrieveFieldValueByFieldName($entry, $fieldName,$bean_id);
                    if($value !== FALSE)
                        return $value;
                }
            }
        }
        
        return $found;
    }
    
    function _retrieveFieldValueByFieldName($entry, $fieldName, $beanId)
    {
        $found = FALSE;
        $fieldValue = FALSE;
        foreach ($entry as $fieldEntry)
        {
            if($fieldEntry['name'] == 'id' && $fieldEntry['value'] == $beanId )
                $found = TRUE;
                
            if($fieldEntry['name'] == $fieldName )
                $fieldValue = $fieldEntry['value'];
        }
        
        if($found)
            return $fieldValue;
        else 
            return FALSE;
    }
}