<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_locations', function (Blueprint $table) {
            $table->id();
            $table->decimal('lat', 10, 5)->nullable();
            $table->decimal('lng', 10, 5)->nullable();
            $table->decimal('heading', 8, 5)->nullable();
            $table->unsignedBigInteger('device_id');
            $table->timestamps();
            
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_locations');
    }
}
