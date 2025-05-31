<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCategoriesColumnFromItemsTable extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn('categories');
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->json('categories')->nullable();
    });
}

}
