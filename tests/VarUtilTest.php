<?php
/**
 * Utilized - Miscellaneous utility library
 *
 * @link      https://github.com/aaphp/utilized
 * @copyright Copyright (c) 2016 Kosit Supanyo
 * @license   https://github.com/aaphp/utilized/blob/v1.x/LICENSE.md (MIT License)
 */
namespace aaphp\Utilized\Tests;

use aaphp\Utilized\VarUtil;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @coversDefaultClass aaphp\Utilized\VarUtil
 */
class VarUtilTest extends TestCase
{
    /**
     * @covers ::getType
     */
    public function testGetType()
    {
        $this->assertSame(VarUtil::getType(null), 'null');
        $this->assertSame(VarUtil::getType(true), 'bool');
        $this->assertSame(VarUtil::getType(false), 'bool');
        $this->assertSame(VarUtil::getType(1), 'int');
        $this->assertSame(VarUtil::getType(1.0), 'float');
        $this->assertSame(VarUtil::getType('Hello'), 'string');
        $this->assertSame(VarUtil::getType([]), 'array');
        $this->assertSame(VarUtil::getType(new \stdClass), 'stdClass');
        $fp = fopen('php://temp', 'r+');
        $this->assertSame(VarUtil::getType($fp), 'resource');
        fclose($fp);
        $this->assertSame(VarUtil::getType($fp), 'resource');
    }

    /**
     * @covers ::setType
     */
    public function testSetType()
    {
        $this->assertSame(VarUtil::setType('1', 'bool'), true);
        $this->assertSame(VarUtil::setType('on', 'bool'), true);
        $this->assertSame(VarUtil::setType('yes', 'bool'), true);
        $this->assertSame(VarUtil::setType('true', 'bool'), true);

        $this->assertSame(VarUtil::setType('0', 'bool'), false);
        $this->assertSame(VarUtil::setType('off', 'bool'), false);
        $this->assertSame(VarUtil::setType('no', 'bool'), false);
        $this->assertSame(VarUtil::setType('false', 'bool'), false);

        $this->assertTrue(VarUtil::setType(true, 'bool'), true);
        $this->assertFalse(VarUtil::setType('ok', 'bool'));

        $this->assertSame(VarUtil::setType(1, 'int'), 1);
        $this->assertSame(VarUtil::setType('1', 'int'), 1);
        $this->assertSame(VarUtil::setType('01', 'int'), 1);
        $this->assertSame(VarUtil::setType('0x1', 'int'), 1);
        $this->assertNull(VarUtil::setType('Hello', 'int'));

        $this->assertSame(VarUtil::setType(1.0, 'float'), 1.0);
        $this->assertSame(VarUtil::setType('1.0', 'float'), 1.0);
        $this->assertSame(VarUtil::setType('1.0e3', 'float'), 1000.0);
        $this->assertSame(VarUtil::setType('1,000', 'float'), 1000.0);
        $this->assertNull(VarUtil::setType('Hello', 'float'));

        $this->assertSame(VarUtil::setType('Hello', 'string'), 'Hello');
        $this->assertSame(VarUtil::setType(null, 'string'), '');
        $this->assertSame(VarUtil::setType(true, 'string'), '1');
        $this->assertSame(VarUtil::setType(false, 'string'), '');
        $this->assertSame(VarUtil::setType(1, 'string'), '1');
        $this->assertSame(VarUtil::setType(1.5, 'string'), '1.5');

        $tmp = new \Exception('Hello World');
        $this->assertTrue(is_string(VarUtil::setType($tmp, 'string')));

        $tmp = new \stdClass;
        $this->assertTrue(is_null(VarUtil::setType($tmp, 'string')));

        $this->assertSame(VarUtil::setType([], 'array'), []);
        $tmp = new \ArrayObject();
        $this->assertTrue(is_array(VarUtil::setType($tmp, 'array')));
        $tmp = new \stdClass();
        $this->assertTrue(is_array(VarUtil::setType($tmp, 'array')));
        $this->assertNull(VarUtil::setType(1, 'array'));

        $this->assertTrue(is_callable(VarUtil::setType('strlen', 'callable')));
        $this->assertNull(VarUtil::setType([0, 1], 'callable'));

        $this->assertSame(VarUtil::setType([], 'iterable'), []);
        $tmp = new \ArrayObject();
        $this->assertSame(VarUtil::setType($tmp, 'iterable'), $tmp);
        $tmp = new \stdClass();
        $this->assertNull(VarUtil::setType($tmp, 'iterable'));

        $this->assertSame(VarUtil::setType($tmp, 'stdClass'), $tmp);
        $this->assertNull(VarUtil::setType(1, 'stdClass'));
    }

    /**
     * @covers ::matchType
     */
    public function testMatchType()
    {
        $this->assertTrue(VarUtil::matchType(true, 'bool'));
        $this->assertTrue(VarUtil::matchType(1, 'int'));
        $this->assertTrue(VarUtil::matchType(1.0, 'float'));
        $this->assertTrue(VarUtil::matchType('Hello', 'string'));
        $this->assertTrue(VarUtil::matchType([], 'array'));
        $this->assertTrue(VarUtil::matchType('strlen', 'callable'));
        $this->assertTrue(VarUtil::matchType([], 'iterable'));
        $this->assertTrue(VarUtil::matchType(new \ArrayObject(), 'iterable'));
        $this->assertTrue(VarUtil::matchType(new \ArrayObject(), 'ArrayObject'));
        $this->assertFalse(VarUtil::matchType(new \stdClass(), 'ArrayObject'));
        $fp = fopen('php://temp', 'r+');
        $this->assertTrue(VarUtil::matchType($fp, 'resource'));
        fclose($fp);
        $this->assertTrue(VarUtil::matchType($fp, 'resource'));
    }
    
    /**
     * @covers ::stringConvertible
     */
    public function testStringConvertible()
    {
        $this->assertTrue(VarUtil::stringConvertible(null));
        $this->assertTrue(VarUtil::stringConvertible(true));
        $this->assertTrue(VarUtil::stringConvertible(false));
        $this->assertTrue(VarUtil::stringConvertible(1));
        $this->assertTrue(VarUtil::stringConvertible(1.0));
        $this->assertTrue(VarUtil::stringConvertible('Hello'));
        $this->assertTrue(VarUtil::stringConvertible(new \Exception()));
        $this->assertFalse(VarUtil::stringConvertible([]));
    }
}
