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

class HashTest extends TestCase
{
    public function testHash()
    {
        $hash = Hash::string('hola');
        $hash = Hash::css('<p>Hola</p>');
        $hash = Hash::html('.hola{}');

        $dir = sys_get_temp_dir();
        $file = $dir.DIRECTORY_SEPARATOR.'hola.txt';

        file_put_contents($dir.DIRECTORY_SEPARATOR.'hola.txt', 'hola');

        $hash = Hash::fileSha1($dir.DIRECTORY_SEPARATOR.'hola.txt');

        @unlink($file);
    }
}
