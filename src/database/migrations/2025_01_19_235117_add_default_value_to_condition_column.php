<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValueToConditionColumn extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->string('condition')->default('new')->change(); // 'new' をデフォルト値として設定
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->string('condition')->nullable(false)->change();
    });
}
}
