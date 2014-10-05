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

use Config;
use Illuminate\Support\ServiceProvider;
use RawPHP\LaravelOptions\Commands\OptionsMigrationCommand;

class LaravelOptionsServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = FALSE;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot( )
	{
		$this->package( 'rawphp/laravel-options' );

        $this->commands( 'command.laraveloptions.migration' );
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register( )
	{
        $this->app->bindShared( 'options', function( )
        {
            return new OptionsService( \DB, Config::get( 'laravel-options::options_table_name' ) );
        } );

        $this->app->bindShared( 'command.laraveloptions.migration', function( $app )
        {
            return new OptionsMigrationCommand( );
        } );
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides( )
	{
        return [
            'command.laraveloptions.migration',
        ];
	}
}
