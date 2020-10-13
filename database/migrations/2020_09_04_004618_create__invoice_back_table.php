<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceBackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicebacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dates',30);
            $table->integer('customer_id');
            $table->integer('total_item');
            $table->decimal('fine',16,2)->nullable();
            $table->decimal('total_payable',16,2);
            $table->decimal('total',16,2);
            $table->unsignedBigInteger('increment_id');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('invoicebacks');
    }
}
