<?php
require_once 'modules/Import/ImportMap.php';

class ImportMapTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_importMap;
    
    public function setUp() 
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->is_admin = '1';
        $this->_importMap = new ImportMap();
    }
    
    public function tearDown() 
    {
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
        $GLOBALS['db']->query(
            'DELETE FROM import_maps 
                WHERE assigned_user_id IN (\'' . 
                    implode("','",SugarTestUserUtilities::getCreatedUserIds()) . '\')');
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    private function _addMapping(
        $name      = 'test mapping for importmaptest',
        $enclosure = '"'
        )
    {
        $this->_importMap->save(
            $GLOBALS['current_user']->id,
            $name,
            'TEST',
            'other',
            '1',
            ',',
            $enclosure);
    }
    
    public function testSave()
    {
        $this->_addMapping();
        $query = "SELECT * FROM import_maps 
                    WHERE assigned_user_id = '{$GLOBALS['current_user']->id}'
                        AND name = 'test mapping'
                        AND module = 'TEST'
                        AND source = 'other'
                        AND has_header = '1'
                        AND delimiter = ','
                        AND enclosure = '\"'";
        
        $result = $GLOBALS['db']->query($query);
        
        $this->assertNull($GLOBALS['db']->fetchByAssoc($result),'Row not added');
    }
    
    public function testSaveEmptyEnclosure()
    {
        $this->_addMapping('test mapping','');
        $query = "SELECT * FROM import_maps 
                    WHERE assigned_user_id = '{$GLOBALS['current_user']->id}'
                        AND name = 'test mapping'
                        AND module = 'TEST'
                        AND source = 'other'
                        AND has_header = '1'
                        AND delimiter = ','
                        AND enclosure = ' '";
        
        $result = $GLOBALS['db']->query($query);
        
        $this->assertNotNull($GLOBALS['db']->fetchByAssoc($result),'Row not added');
    }
    
    public function testSetAndGetMapping()
    {
        $mapping = array(
            'field1' => 'value1',
            'field2' => 'value2',
            );
        
        $this->_importMap->setMapping($mapping);
        $this->_addMapping();
        $id = $this->_importMap->id;
        
        $importMapRetrieve = new ImportMap();
        $importMapRetrieve->retrieve($id, false);
        
        $this->assertEquals($importMapRetrieve->getMapping(),$mapping);
    }
    
    public function testSetAndGetDefaultFields()
    {
        $mapping = array(
            'field1' => 'value1',
            'field2' => 'value2',
            );
        
        $this->_importMap->setDefaultValues($mapping);
        $this->_addMapping();
        $id = $this->_importMap->id;
        
        $importMapRetrieve = new ImportMap();
        $importMapRetrieve->retrieve($id, false);
        
        $this->assertEquals($importMapRetrieve->getDefaultValues(),$mapping);
    }
    
    public function testMarkPublished()
    {
        $this->_addMapping();
        $this->assertTrue($this->_importMap->mark_published(
            $GLOBALS['current_user']->id,true));
        $id = $this->_importMap->id;
        
        $query = "SELECT * FROM import_maps 
                    WHERE id = '$id'";
        
        $result = $GLOBALS['db']->query($query);
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        
        $this->assertEquals($row['is_published'],'yes');
    }
    
    public function testMarkPublishedNameConflict()
    {
        $this->_addMapping();
        $this->_importMap->mark_published(
            $GLOBALS['current_user']->id,true);
        
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->_importMap = new ImportMap();
        $this->_addMapping();
        $this->assertFalse($this->_importMap->mark_published(
            $GLOBALS['current_user']->id,true));
        
        $query = "SELECT * FROM import_maps 
                    WHERE id = '{$this->_importMap->id}'";
        
        $result = $GLOBALS['db']->query($query);
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        
        $this->assertEquals($row['is_published'],'no');
    }
    
    public function testMarkPublishedNameNotAdmin()
    {
        $GLOBALS['current_user']->is_admin = '0';
        
        $this->_addMapping();
        $this->assertFalse($this->_importMap->mark_published(
            $GLOBALS['current_user']->id,true));
    }
    
    public function testMarkUnpublished()
    {
        $this->_addMapping();
        $this->_importMap->mark_published(
            $GLOBALS['current_user']->id,true);
        $id = $this->_importMap->id;
        
        $importMapRetrieve = new ImportMap();
        $importMapRetrieve->retrieve($id, false);
        $this->assertTrue($this->_importMap->mark_published(
            $GLOBALS['current_user']->id,false));
        
        $query = "SELECT * FROM import_maps 
                    WHERE id = '$id'";
        
        $result = $GLOBALS['db']->query($query);
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        
        $this->assertEquals($row['is_published'],'no');
    }
    
    public function testMarkUnpublishedNameConflict()
    {
        $this->_addMapping();
        $this->_importMap->mark_published(
            $GLOBALS['current_user']->id,true);
        $id = $this->_importMap->id;
        
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->_importMap = new ImportMap();
        $this->_addMapping();
        
        $importMapRetrieve = new ImportMap();
        $importMapRetrieve->retrieve($id, false);
        $this->assertFalse($this->_importMap->mark_published(
            $GLOBALS['current_user']->id,false));
        
        $query = "SELECT * FROM import_maps 
                    WHERE id = '$id'";
        
        $result = $GLOBALS['db']->query($query);
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        
        $this->assertEquals($row['is_published'],'yes');
    }
    
    public function testMarkDeleted()
    {
        $this->_addMapping();
        $id = $this->_importMap->id;
        
        $this->_importMap = new ImportMap();
        $this->_importMap->mark_deleted($id);
        
        $query = "SELECT * FROM import_maps 
                    WHERE id = '$id'";
        
        $result = $GLOBALS['db']->query($query);
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        
        $this->assertEquals($row['deleted'],'1');
    }
    
    public function testMarkDeletedAdminDifferentUser()
    {
        $this->_addMapping();
        $id = $this->_importMap->id;
        
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->is_admin = '1';
        $this->_importMap = new ImportMap();
        $this->_importMap->mark_deleted($id);
        
        $query = "SELECT * FROM import_maps 
                    WHERE id = '$id'";
        
        $result = $GLOBALS['db']->query($query);
        
        $row = $GLOBALS['db']->fetchByAssoc($result);
        
        $this->assertEquals($row['deleted'],'1');
    }
    
    public function testMarkDeletedNotAdminDifferentUser()
    {
        $this->_addMapping();
        $id = $this->_importMap->id;
        
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->is_admin = '0';
        $this->_importMap = new ImportMap();
        $this->assertFalse($this->_importMap->mark_deleted($id),'Record should not be allowed to be deleted');
    }
    
    public function testRetrieveAllByStringFields()
    {
        $this->_addMapping();
        $this->_importMap = new ImportMap();
        $this->_addMapping('test mapping 2');
        $this->_importMap = new ImportMap();
        $this->_addMapping('test mapping 3');
        
        $objarr = $this->_importMap->retrieve_all_by_string_fields(
            array('assigned_user_id' => $GLOBALS['current_user']->id)
            );
        
        $this->assertEquals(count($objarr),3);
        
        $this->assertEquals($objarr[0]->assigned_user_id,
            $GLOBALS['current_user']->id);
        $this->assertEquals($objarr[1]->assigned_user_id,
            $GLOBALS['current_user']->id);
        $this->assertEquals($objarr[2]->assigned_user_id,
            $GLOBALS['current_user']->id);
    }
}
