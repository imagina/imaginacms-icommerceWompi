<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('icommercewompi__payment_sources', function (Blueprint $table) {
           
            $table->tinyInteger('default')->default(0)->unsigned()->after('user_id');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('icommercewompi__payment_sources', function (Blueprint $table) {
            $table->dropColumn('default');
        });
    }

};
