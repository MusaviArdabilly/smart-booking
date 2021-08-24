<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->timestamps();

            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desks');
    }
}
