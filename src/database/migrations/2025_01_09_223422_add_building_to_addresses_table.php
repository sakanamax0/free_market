<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuildingToAddressesTable extends Migration
{
    public function up()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->string('building')->nullable();  // 'building'カラムを追加
    });
}

public function down()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->dropColumn('building');  // 'building'カラムを削除
    });
}

}
