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
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\LaravelOptions;

use Illuminate\Database\DatabaseManager;
use RawPHP\LaravelOptions\Exceptions\DuplicateKeyException;
use RawPHP\LaravelOptions\Exceptions\NonExistentOptionException;

/**
 * The options service class.
 *
 * @category  PHP
 * @package   RawPHP/LaravelOptions
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class OptionsService implements IOptions
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $optionsTable         = 'options';

    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * Constructs a new instance of OptionsService.
     */
    public function __construct( DatabaseManager $db, $table )
    {
        $this->db = $db;

        $this->optionsTable = $table;
    }

    /**
     * Gets the option value for a key.
     *
     * @param string $key the option key
     *
     * @return string the option value if exists or NULL
     */
    public function get( $key )
    {
        $result = $this->db->table( $this->optionsTable )->where( 'option_key', '=', $key )->get( );

        if ( !empty( $result ) )
        {
            return $result[ 0 ]->option_value;
        }

        return NULL;
    }

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
    public function add( $key, $value )
    {
        try
        {
            $result = $this->db->table( $this->optionsTable )->insert( [ 'option_key' => $key, 'option_value' => $value ] );

            return FALSE !== $result;
        }
        catch( \Exception $e )
        {
            if ( FALSE !== strstr( $e->getMessage( ), 'Duplicate entry' ) )
            {
                throw new DuplicateKeyException( 'The option key: "' . $key . '" already exists', $e->getCode( ), $e );
            }
        }
    }

    /**
     * Updates an option value in the database.
     *
     * @param string $key   option name
     * @param string $value option value
     *
     * @return bool TRUE on success, FALSE on failure
     *
     * @throws NonExistentOptionException
     */
    public function update( $key, $value )
    {
        $result = $this->db->table( $this->optionsTable )->update( [ 'option_key' => $key, 'option_value' => $value ] );

        if ( 0 === $result && !$this->has( $key ) )
        {
            throw new NonExistentOptionException( 'The option key: "' . $key . '" doesn\'t exists' );
        }

        return ( 1 === $result );
    }

    /**
     * Deletes an option from the database.
     *
     * @param string $key option name
     *
     * @return bool TRUE on success, FALSE on failure
     *
     * @throws NonExistentOptionException
     */
    public function delete( $key )
    {
        $result = $this->db->table( $this->optionsTable )->where( [ 'option_key' => $key ] )->delete( );

        if ( 0 === $result && !$this->has( $key ) )
        {
            throw new NonExistentOptionException( 'The option key: "' . $key . '" doesn\'t exists' );
        }

        return ( FALSE !== $result );
    }

    /**
     * Checks if a key exists in the database.
     *
     * @param string $key the option name
     *
     * @return bool TRUE if key exists, otherwise FALSE
     */
    public function has( $key )
    {
        $result = $this->db->table( $this->optionsTable )->where( 'option_key', $key )->count( );

        return ( 0 < $result );
    }
}
