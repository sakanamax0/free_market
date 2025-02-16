<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoldOutToProductsTable extends Migration
{
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->boolean('sold_out')->default(false); // sold_out フィールドを追加
    });
}

    public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('sold_out');
    });
}
}
