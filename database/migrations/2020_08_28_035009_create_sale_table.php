<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');          
            $table->string('dates',30);            
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('store_id');
            $table->decimal('qantity',16,2);
            $table->decimal('price',16,2);
            $table->unsignedTinyInteger('action_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
             $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
