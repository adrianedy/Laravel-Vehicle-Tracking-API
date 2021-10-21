<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileDetailInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_code', 50)->nullable()->after('email');
            $table->string('phone', 50)->after('phone_code');
            $table->string('image', 50)->nullable()->after('phone');
            $table->boolean('is_active')->default(1)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_code');
            $table->dropColumn('phone');
            $table->dropColumn('image');
            $table->dropColumn('is_active');
        });
    }
}
