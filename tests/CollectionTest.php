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
use TinyCache\Collection;

class CollectionTest extends TestCase
{
    public function testCollection()
    {
        $collection = new Collection(['key'=>'value']);
        $this->assertInstanceOf('TinyCache\Collection', $collection);

        $value = $collection->get('key');
        $this->assertSame('value', $value);

        $value = $collection->keys();
        $this->assertSame(['key'], $value);

        $collection->remove('key');
        $collection->offsetExists('key');
        $collection->offsetGet('key');
        $collection->remove('key');
        $collection->offsetSet('key','value');
        $one = $collection->count();
        $this->assertSame(1, $one);

        $iterator = $collection->getIterator();
        $this->assertInstanceOf('ArrayIterator', $iterator);

        $collection->offsetUnset('key');
        $collection->clear();
    }
}