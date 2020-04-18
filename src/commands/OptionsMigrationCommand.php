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
 * @package   RawPHP/LaravelOptions/Commands
 * @author    Tom Kaczocha <tom.kaczocha.code@gmail.com>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\LaravelOptions\Commands;

use Config;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class OptionsMigrationCommand extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'options:migration';

    /**
     * Command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration for the options table.';

    /**
     * Actions when the command is executed.
     */
    public function fire()
    {
        $this->laravel->view->addNamespace( 'laravel-options', substr( __DIR__, 0, -8 ) . 'views' );

        $optionsTable = lcfirst( $this->getNameByOptionOrConfig() );

        $this->line( '' );
        $this->info( 'Table: ' . $optionsTable );
        $message = 'A migration that creates ' . $optionsTable . ' table will be created in the app/database/migrations directory';

        $this->comment( $message );
        $this->line( '' );

        if ( $this->confirm( 'Proceed with the migration creation? [Yes|No]' ) ) {
            $this->line( '' );
            $this->info( 'Creating migration...' );

            if ( $this->createMigration( $optionsTable ) ) {
                $this->info( 'Migration successfully created!' );
            } else {
                $this->error( "Couldn't create migration.\nCheck the write permissions within the app/database/migrations directory." );
            }

            $this->line( '' );
        }

    }

    protected function getNameByOptionOrConfig()
    {
        if ( $name = $this->option( 'table' ) ) {
            return $name;
        }

        return Config::get( 'laravel-options::options_table_name' );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [ 'table', NULL, InputOption::VALUE_OPTIONAL, 'Options table name.' ],
        ];
    }

    /**
     * Creates the migration for the options table.
     *
     * @param string $optionsTable
     *
     * @return bool
     */
    protected function createMigration( $optionsTable = 'options' )
    {
        $migrationFile = $this->laravel->path . '/database/migrations/' . date( 'Y_m_d_His' ) . '_Create_Options_Table.php';
        $output = $this->laravel->view->make( 'laravel-options::generators.migration' )->with( 'table', $optionsTable )->render();

        //dd( $output );

        if ( !file_exists( $migrationFile ) && $fs = fopen( $migrationFile, 'x' ) ) {
            fwrite( $fs, $output );
            fclose( $fs );

            return TRUE;
        }

        return FALSE;
    }
}
