<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToItemsTable extends Migration
{
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
    });
}

    public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropForeign(['user_id']); // 先に外部キー制約を削除
        $table->dropColumn('user_id');    // その後、カラムを削除
    });
}
}
