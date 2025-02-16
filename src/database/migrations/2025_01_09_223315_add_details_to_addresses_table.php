<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToAddressesTable extends Migration
{
    public function up()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->string('details');  // 'details'カラムを追加
    });
}

public function down()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->dropColumn('details');  // 'details'カラムを削除
    });
}

}
