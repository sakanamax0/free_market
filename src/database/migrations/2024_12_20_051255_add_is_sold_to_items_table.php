<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSoldToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->boolean('is_sold')->default(false); // 未販売の商品は false とする
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn('is_sold');
    });
}

}
