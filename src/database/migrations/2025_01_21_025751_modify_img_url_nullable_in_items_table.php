<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyImgUrlNullableInItemsTable extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->string('img_url')->nullable()->change();
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->string('img_url')->nullable(false)->change();
    });
}

}
