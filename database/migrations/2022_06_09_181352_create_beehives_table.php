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
            $table->string('identifier')->unique();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('beehive_type_id')->constrained()->cascadeOnDelete();
            $table->UnsignedBigInteger('apiary');
            $table->foreignId('beehive_status_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('beehive_levels')->default(1);
            $table->unsignedInteger('beehive_frames')->default(10);
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
