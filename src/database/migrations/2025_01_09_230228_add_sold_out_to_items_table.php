<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoldOutToItemsTable extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->boolean('sold_out')->default(false); 
    });
}

    public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn('sold_out'); 
    });
}

}
