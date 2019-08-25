<?php
/**
 * TinyCache.
 *
 * @link      https://github.com/adrorocker/tinycache
 *
 * @copyright Copyright (c) 2017 Adro Rocker
 * @author    Adro Rocker <alejandro.morelos@jarwebdev.com>
 */

namespace TinyCache;

use PHPUnit\Framework\TestCase;
use TinyCache\Adapter\FilesystemAdapter;

class CacheTest extends TestCase
{
    public function testTinyCache()
    {
        $adapter = new FilesystemAdapter();
        $this->assertInstanceOf(FilesystemAdapter::class, $adapter);

        $cache = new Cache($adapter);
        $this->assertInstanceOf(Cache::class, $cache);

        $item1 = new Item('hi1', 'Hola');
        $this->assertInstanceOf(Item::class, $item1);

        $item2 = new Item('hi2', 'Hola');
        $this->assertInstanceOf(Item::class, $item2);

        $cache->saveDeferred($item1);

        $cache->commit();

        $cache->save($item2);

        $items = $cache->getItems(['hi1', 'hi2']);
        $this->assertInstanceOf(Collection::class, $items);

        $getItem = $cache->getItem('hi1');
        $this->assertInstanceOf(Item::class, $getItem);

        $cache->hasItem('hi1');
        $cache->deleteItem('hi1');
        $cache->deleteItems(['hi2']);
        $cache->clear();
    }
}
