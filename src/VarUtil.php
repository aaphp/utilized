<?php
/**
 * Utilized - Miscellaneous utilities
 *
 * @link      https://github.com/aaphp/utilized
 * @copyright Copyright (c) 2016 Kosit Supanyo
 * @license   https://github.com/aaphp/utilized/blob/v1.x/LICENSE.md (MIT License)
 */
namespace aaphp\Utilized;

use Traversable;

final class VarUtil
{
    public static function getType($value)
    {
        if (is_null($value)) {
            return 'null';
        }
        if (is_bool($value)) {
            return 'bool';
        }
        if (is_int($value)) {
            return 'int';
        }
        if (is_float($value)) {
            return 'float';
        }
        if (is_string($value)) {
            return 'string';
        }
        if (is_array($value)) {
            return 'array';
        }
        if (is_object($value)) {
            return get_class($value);
        }
        return 'resource';
    }

    public static function setType($value, $type)
    {
        switch ($type) {
            case 'bool':
                if (is_bool($value)) {
                    return $value;
                }
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'int':
                if (is_int($value)) {
                    return $value;
                }
                $value = filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX
                );
                if ($value !== false) {
                    return $value;
                }
                return;
            case 'float':
                if (is_float($value)) {
                    return $value;
                }
                $value = filter_var(
                    $value,
                    FILTER_VALIDATE_FLOAT,
                    FILTER_FLAG_ALLOW_THOUSAND
                );
                if ($value !== false) {
                    return $value;
                }
                return;
            case 'string':
                if (is_string($value)) {
                    return $value;
                }
                if (is_scalar($value)) {
                    return (string)$value;
                }
                if (is_null($value)) {
                    return '';
                }
                if (method_exists($value, '__toString')) {
                    return (string)$value;
                }
                return;
            case 'array':
                if (is_array($value)) {
                    return $value;
                }
                if (is_object($value)) {
                    if ($value instanceof Traversable) {
                        return iterator_to_array($value);
                    }
                    return (array)$value;
                }
                return;
            case 'callable':
                if (is_callable($value)) {
                    return $value;
                }
                return;
            case 'iterable':
                if (is_array($value)) {
                    return $value;
                }
                if ($value instanceof Traversable) {
                    return $value;
                }
                return;
            default:
                break;
        }
        if ($value instanceof $type) {
            return $value;
        }
    }

    public static function matchType($value, $type)
    {
        switch ($type) {
            case 'bool':
                return is_bool($value);
            case 'int':
                return is_int($value);
            case 'float':
                return is_float($value);
            case 'string':
                return is_string($value);
            case 'array':
                return is_array($value);
            case 'callable':
                return is_callable($value);
            case 'iterable':
                if (is_array($value)) {
                    return true;
                }
                return $value instanceof Traversable;
            case 'resource':
                if (is_resource($value)) {
                    return true;
                }
                return gettype($value) === 'unknown type';
            default:
                break;
        }
        if ($value instanceof $type) {
            return true;
        }
        return false;
    }

    public static function stringConvertible($value)
    {
        if (is_scalar($value)) {
            return true;
        }
        if (is_null($value)) {
            return true;
        }
        if (method_exists($value, '__toString')) {
            return true;
        }
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
}
