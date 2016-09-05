<?php
/**
 * Utilized - Miscellaneous utilities
 *
 * @link      https://github.com/aaphp/utilized
 * @copyright Copyright (c) 2016 Kosit Supanyo
 * @license   https://github.com/aaphp/utilized/blob/v1.x/LICENSE.md (MIT License)
 */
namespace aaphp\Utilized;

final class SignedJson
{
    public static function create(array $data, $secret, $separator = '.', $algo = 'SHA256')
    {
        $data = [
            'algorithm' => 'HMAC-' . strtoupper($algo),
            'issued_at' => time(),
        ] + $data;
        $data = Base64Url::encode(json_encode($data));
        $hash = Base64Url::encode(hash_hmac($algo, $data, $secret, true));
        return $hash . $separator . $data;
    }

    public static function parse($input, $secret, $separator = '.')
    {
        $parts = explode($separator, $input, 2);
        if (!isset($parts[1]) || isset($parts[2])) {
            return;
        }
        $data = json_decode(Base64Url::decode($parts[1]), true);
        if (!isset($data['algorithm'], $data['issued_at'])) {
            return;
        }
        if (!preg_match('/^HMAC\-(\S+)$/i', $data['algorithm'], $matches)) {
            return;
        }
        if (Base64Url::decode($parts[0]) !== hash_hmac($matches[1], $parts[1], $secret, true)) {
            return;
        }
        return $data;
    }

    private function __construct()
    {

    }
}
