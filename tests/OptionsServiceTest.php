<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 *
 * Copyright (c) 2014 RawPHP.org
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   RawPHP/LaravelOptions/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\LaravelOptions\Tests;

use Illuminate\Support\Facades\DB;
use RawPHP\LaravelOptions\OptionsService;
use TestCase;

class OptionsServiceTest extends TestCase
{
    /**
     * @var OptionsService
     */
    protected $options;

    /**
     * Setup before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->options = new OptionsService( DB::getFacadeRoot(), 'options' );
    }

    /**
     * Cleanup after each test.
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->options = NULL;

        DB::table( 'options' )->truncate();
    }

    /**
     * Test options service instantiated correctly.
     */
    public function testOptionsServiceInstantiatedCorrectly()
    {
        $this->assertNotNull( $this->options );
    }

    /**
     * Test has is false on empty table.
     */
    public function testHasIsFalse()
    {
        $this->assertFalse( $this->options->has( 'nonexistent_key' ) );
    }

    /**
     * Test adding key value.
     */
    public function testAddingKeyValue()
    {
        $this->assertTrue( $this->options->add( 'my_key', 'my_value' ) );

        $this->assertTrue( $this->options->has( 'my_key' ) );

        $this->assertEquals( 'my_value', $this->options->get( 'my_key' ) );
    }

    /**
     * Test adding a duplicate key throws exception.
     *
     * @expectedException RawPHP\LaravelOptions\Exceptions\DuplicateKeyException
     */
    public function testAddingDuplicateKey()
    {
        $this->assertTrue( $this->options->add( 'my_key', 'my_value' ) );
        $this->assertTrue( $this->options->add( 'my_key', 'my_value' ) );
    }

    /**
     * Test updating an option value.
     */
    public function testUpdateOption()
    {
        $this->assertTrue( $this->options->add( 'my_key', 'my_value' ) );

        $this->assertTrue( $this->options->update( 'my_key', 'new_value' ) );
        $this->assertEquals( 'new_value', $this->options->get( 'my_key' ) );
    }

    /**
     * Test updating a non-existent option.
     *
     * @expectedException RawPHP\LaravelOptions\Exceptions\NonExistentOptionException
     */
    public function testUpdateNonExistentOption()
    {
        $this->assertFalse( $this->options->update( 'my_key', 'new_value' ) );
    }

    /**
     * Test deleting an option.
     */
    public function testDeleteOption()
    {
        $this->assertTrue( $this->options->add( 'my_key', 'my_value' ) );
        $this->assertTrue( $this->options->has( 'my_key' ) );
        $this->assertTrue( $this->options->delete( 'my_key' ) );
        $this->assertFalse( $this->options->has( 'my_key' ) );
    }

    /**
     * Test deleting a non-existent option.
     *
     * @expectedException RawPHP\LaravelOptions\Exceptions\NonExistentOptionException
     */
    public function testDeleteNonExistentOption()
    {
        $this->assertFalse( $this->options->delete( 'my_key' ) );
    }
}
