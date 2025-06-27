<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNullImgUrlInItemsTable extends Migration
{
    public function up()
{
    DB::table('items')
        ->whereNull('img_url')
        ->update(['img_url' => 'images/default_image.jpg']);  

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
