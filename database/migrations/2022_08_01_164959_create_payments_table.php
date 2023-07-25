<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cliente_auditwhole_ruc', 13);
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->double('amount', 8, 2);
            $table->string('note')->nullable();
            $table->string('type', 30);
            $table->integer('voucher')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            $table->foreign('cliente_auditwhole_ruc')->references('ruc')->on('cliente_auditwholes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
