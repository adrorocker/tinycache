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
    protected $file;

    /**
     * @dataProvider providerFileSha1
     */
    public function testFileSha1($contents, $expected)
    {
        $dir = sys_get_temp_dir();
        $this->file = $dir.DIRECTORY_SEPARATOR.'hola.txt';

        file_put_contents($dir.DIRECTORY_SEPARATOR.'hola.txt', $contents);

        $hash = Hash::fileSha1($dir.DIRECTORY_SEPARATOR.'hola.txt');

        $this->assertSame($hash, $expected);
    }

    public function providerFileSha1()
    {
        return [
            ['hola', '99800b85d3383e3a2fb45eb7d0066a4879a9dad0'],
            ['<p>Hola</p>', '253629c104c29914e69213ba58318a0de1ea51e7'],
            ['.hola{}', '62a23c48400389fc01d912381a01eb84265cfd36'],
        ];
    }

    public function testString()
    {
        $this->assertSame('99800b85d3383e3a2fb45eb7d0066a4879a9dad0', Hash::string('hola'));
    }

    public function testHtml()
    {
        $this->assertSame('253629c104c29914e69213ba58318a0de1ea51e7', Hash::html('<p>Hola</p>'));
    }

    public function testCss()
    {
        $this->assertSame('62a23c48400389fc01d912381a01eb84265cfd36', Hash::css('.hola{}'));
    }

    protected function tearDown()
    {
        @unlink($this->file);
    }
}
