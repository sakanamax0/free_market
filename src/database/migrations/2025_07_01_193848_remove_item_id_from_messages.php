<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveItemIdFromMessages extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            
            $table->dropForeign('messages_item_id_foreign'); 
            $table->dropColumn('item_id');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }
}
