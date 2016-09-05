<?php
/**
 * Utilized - Miscellaneous utilities
 *
 * @link      https://github.com/aaphp/utilized
 * @copyright Copyright (c) 2016 Kosit Supanyo
 * @license   https://github.com/aaphp/utilized/blob/v1.x/LICENSE.md (MIT License)
 */
namespace aaphp\Utilized;

final class Base64Url
{
    public static function encode($value)
    {
        return strtr(rtrim(base64_encode($value), '='), '+/', '-_');
    }

    public static function decode($value)
    {
        return base64_decode(strtr($value, '-_', '+/'));
    }

    public static function hash($value, $algo = 'md5')
    {
        return strtr(
            rtrim(base64_encode(hash($algo, $value, true)), '='),
            '+/',
            '-_'
        );
    }

    public static function uuid()
    {
        $bytes = openssl_random_pseudo_bytes(16);
        $bytes[6] = chr(64 + ord($bytes[6]) % 16);
        $bytes[8] = chr(128 + ord($bytes[8]) % 64);
        return strtr(rtrim(base64_encode($bytes), '='), '+/', '-_');
    }
    
    private function __construct()
    {

    }
}
