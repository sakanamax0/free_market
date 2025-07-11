<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriesToItemsTable extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->text('categories')->nullable();  
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn('categories');  
    });
}

}
