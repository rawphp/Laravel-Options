<?php echo "<?php\n"; ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create{{ ucfirst( $table ) }}Table extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up( )
    {
        // Creates the roles table
        Schema::create( '{{ $table }}', function ( $table )
        {
            $table->increments( 'id' )->unsigned( );
            $table->string( 'key' )->unique( );
            $table->string( 'value' );
            $table->timestamps( );
        } );
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down( )
    {
        Schema::drop( '{{ $table }}' );
    }
}
