<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');          
            $table->string('dates',30);            
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('product_id');
            $table->decimal('qantity',16,2);
            $table->decimal('price',16,2);
            $table->decimal('micro_time',21,6);
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoicebacks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesbacks');
    }
}
