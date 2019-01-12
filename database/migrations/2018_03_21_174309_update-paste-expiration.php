<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePasteExpiration extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
//        Schema::table('pastes', function(Blueprint $table){
//            $table->boolean('burnAfter');
//            DB::statement('UPDATE pastes SET expiration = "1990-01-01 00:00:00" WHERE expiration IN ("expired", "10m", "1d", "1w", "1h", "burn");');
//            DB::statement('UPDATE pastes SET expiration = "0" WHERE expiration = "never";');
//        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        //
    }
}
