<?php
/**
 * Utilized - Miscellaneous utilities
 *
 * @link      https://github.com/aaphp/utilized
 * @copyright Copyright (c) 2016 Kosit Supanyo
 * @license   https://github.com/aaphp/utilized/blob/v1.x/LICENSE.md (MIT License)
 */
namespace aaphp\Utilized;

final class EncryptedState
{
    const DEFAULT_METHOD = 'BF-CBC';

    public static function create($data, $password, $method = self::DEFAULT_METHOD)
    {
        $ivLength = openssl_cipher_iv_length($method);
        return Base64Url::encode(
            openssl_encrypt(
                serialize($data),
                $method,
                $password,
                OPENSSL_RAW_DATA,
                substr(hash('sha256', $password, true), 0, $ivLength)
            )
        );
    }

    public static function parse($data, $password, $method = self::DEFAULT_METHOD)
    {
        $ivLength = openssl_cipher_iv_length($method);
        return unserialize(
            openssl_decrypt(
                Base64Url::decode($data),
                $method,
                $password,
                OPENSSL_RAW_DATA,
                substr(hash('sha256', $password, true), 0, $ivLength)
            )
        );
    }

    private function __construct()
    {

    }
}
