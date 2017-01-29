<?php
/**
 * TinyCache
 *
 * @link      https://github.com/adrorocker/tinycache
 * @copyright Copyright (c) 2017 Adro Rocker
 * @author    Adro Rocker <alejandro.morelos@jarwebdev.com>
 */
namespace TinyCache;

use PHPUnit\Framework\TestCase;
use TinyCache\Cache;
use TinyCache\Item;
use TinyCache\Adapter\FilesystemAdapter;

class CacheTest extends TestCase
{
    public function testTinyCache()
    {
        $cache = new Cache(new FilesystemAdapter(dirname(__DIR__).DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'cache'));

        $item1 = new Item('hi1','Hola');
        $item2 = new Item('hi2','Hola');

        $cache->saveDeferred($item1)->saveDeferred($item2);

        $cache->commit();

        $items = $cache->getItems(['hi1','hi2']);

        $cache->clear();
    }
}