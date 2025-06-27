<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserIdFromItemsTable extends Migration
{
    /**
     * 
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            
            $table->dropForeign(['user_id']);
            
            $table->dropColumn('user_id');
        });
    }

    /**
     * 
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
