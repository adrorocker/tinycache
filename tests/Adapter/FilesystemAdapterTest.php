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
use TinyCache\Item;
use TinyCache\Collection;
use TinyCache\Adapter\FilesystemAdapter;

class FilesystemAdapterTest extends TestCase
{
    public function testFilesystemAdapter()
    {
        $dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tiny-cache';
        if (file_exists($dir)) {
            @rmdir($dir);
        }
        $adapter = new FilesystemAdapter;
        $this->assertInstanceOf('TinyCache\Adapter\FilesystemAdapter', $adapter);

        $item1 = new Item('hi1','Hola');
        $item2 = new Item('hi2','Hola');
        $item3 = new Item('hi3','Hola');

        $adapter->saveDeferred($item1);

        $adapter->commit();

        $adapter->save($item2);
        $adapter->save($item3);

        $items = $adapter->getItems(['hi1','hi2']);
        $this->assertInstanceOf('TinyCache\Collection', $items);

        $getItem = $adapter->getItem('hi1');
        $this->assertInstanceOf('TinyCache\Item', $getItem);
        $getItem = $adapter->getItem('hi');

        $adapter->hasItem('hi1');
        $adapter->deleteItem('hi');
        $adapter->deleteItem('hi1');
        $adapter->deleteItems(['hi2']);
        $adapter->clear();
    }
}