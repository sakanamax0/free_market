<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserIdFromItemsTable extends Migration
{
    /**
     * マイグレーションを実行する
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // 外部キー制約があれば削除
            $table->dropForeign(['user_id']);
            // カラムを削除
            $table->dropColumn('user_id');
        });
    }

    /**
     * マイグレーションを元に戻す
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // user_id カラムを再度追加
            $table->unsignedBigInteger('user_id')->nullable();
            // 外部キー制約を再度追加
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
