<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImgToItemsTable extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->string('img')->nullable();  // 'img' カラムを追加
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn('img');  // 'img' カラムを削除
    });
}

}
