<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('user_id');
            $table->string('month', 7);
            $table->integer('amount');
            $table->integer('cheque')->nullable();
            $table->integer('amount_cheque')->default(0);
            $table->integer('balance')->default(0);
            $table->integer('cash')->default(0);
            $table->timestamps();

            $table->unique(['month', 'user_id']);
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
