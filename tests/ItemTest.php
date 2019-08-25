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

class ItemTest extends TestCase
{
    public function testItem()
    {
        $item = new Item('key1', 'Hola');
        $this->assertInstanceOf(Item::class, $item);

        $key = $item->getKey();
        $this->assertSame('key1', $key);

        $value = $item->get();
        $this->assertSame('Hola', $value);

        $true = $item->isHit();
        $this->assertTrue($true);

        $item->expiresAt(1);
        $item->expiresAfter(1);
    }
}
