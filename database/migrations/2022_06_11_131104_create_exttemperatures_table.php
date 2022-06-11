<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exttemperatures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('exttemperature');
            $table->string('symbol')->default('none');
            $table->UnsignedBigInteger('beehive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exttemperatures');
    }
};
