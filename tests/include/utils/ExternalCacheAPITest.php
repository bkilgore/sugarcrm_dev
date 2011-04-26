<?php
require_once 'include/utils/external_cache.php';

class ExternalCacheAPITest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        $this->_cacheKey1   = 'test cache key 1 '.date("YmdHis");
        $this->_cacheValue1 = 'test cache value 1'.date("YmdHis");
        $this->_cacheKey2   = 'test cache key 2 '.date("YmdHis");
        $this->_cacheValue2 = 'test cache value 2 '.date("YmdHis");
    }

    public function tearDown() 
    {
       // clear out the test cache if we haven't already
       if ( sugar_cache_retrieve($this->_cacheKey1) )
           sugar_cache_clear($this->_cacheKey1);
       if ( sugar_cache_retrieve($this->_cacheKey2) )
           sugar_cache_clear($this->_cacheKey2);
    }

    public function testSugarCacheValidate()
    {
        $this->assertTrue(sugar_cache_validate());
        $this->assertTrue($GLOBALS['external_cache_checked']);
    }
    
    public function testStoreAndRetrieve()
    {
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2);
        $this->assertEquals(
            sugar_cache_retrieve($this->_cacheKey1),
            $this->_cacheValue1);
        $this->assertEquals(
            sugar_cache_retrieve($this->_cacheKey2),
            $this->_cacheValue2);
    }

    public function testStoreClearCacheKeyAndRetrieve()
    {
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2);
        sugar_cache_clear($this->_cacheKey1);
        $this->assertNotEquals(
            sugar_cache_retrieve($this->_cacheKey1),
            $this->_cacheValue1);
        $this->assertEquals(
            sugar_cache_retrieve($this->_cacheKey2),
            $this->_cacheValue2);
    }
    
    public function testStoreResetCacheAndRetrieve()
    {
        $this->markTestSkipped('Not working as expected');
        
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2);
        sugar_cache_reset();
        $this->assertNotEquals(
            sugar_cache_retrieve($this->_cacheKey1),
            $this->_cacheValue1);
        $this->assertNotEquals(
            sugar_cache_retrieve($this->_cacheKey2),
            $this->_cacheValue2);
    }
}

