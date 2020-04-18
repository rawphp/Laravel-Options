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
 * @package   RawPHP/LaravelOptions
 * @author    Tom Kaczocha <tom.kaczocha.code@gmail.com>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\LaravelOptions;

/**
 * The options interface.
 *
 * @category  PHP
 * @package   RawPHP/LaravelOptions
 * @author    Tom Kaczocha <tom.kaczocha.code@gmail.com>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IOptions
{
    /**
     * Gets the option value for a key.
     *
     * @param string $key the option key
     *
     * @return string the option value if exists or NULL
     */
    public function get( $key );

    /**
     * Adds an option record to the database.
     *
     * @param string $key   the option key
     * @param string $value the option value
     *
     * @return bool TRUE on success, FALSE on failure
     *
     * @throws DuplicateKeyException if the key already exists
     */
    public function add( $key, $value );

    /**
     * Updates an option value in the database.
     *
     * @param string $key   option name
     * @param string $value option value
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function update( $key, $value );

    /**
     * Deletes an option from the database.
     *
     * @param string $key option name
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete( $key );

    /**
     * Checks if a key exists in the database.
     *
     * @param $key the option name
     *
     * @return bool TRUE if key exists, otherwise FALSE
     */
    public function has( $key );
} 