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
        Schema::create('beehives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->UnsignedBigInteger('apiary');
            $table->string('status');
            $table->timestamps();
            $table->foreign('apiary')->references('id')->on('apiaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beehives');
    }
};
