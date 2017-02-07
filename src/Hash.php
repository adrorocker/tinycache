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

class Hash
{
    public static function fileSha1($file)
    {
        return sha1_file($file);
    }

    public static function html($html = '')
    {
        return self::string($html);
    }

    public static function css($css = '')
    {
        return self::string($css);
    }

    public static function string($string = '')
    {
        return sha1($string);
    }
}
