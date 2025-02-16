<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZipcodeToAddressesTable extends Migration
{
    public function up()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->string('zipcode');
    });
}

    public function down()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->dropColumn('zipcode');
    });
}
}
