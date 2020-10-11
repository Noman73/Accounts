<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('bank_id');
            $table->string('dates',15);
            $table->string('name',15);
            $table->unsignedInteger('name_data_id');
            $table->string('payment_type',15);
            $table->decimal('ammount',16,2);
            $table->unsignedInteger('user_id');
            $table->decimal('micro_time',21,6);
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
        Schema::dropIfExists('voucers');
    }
}
