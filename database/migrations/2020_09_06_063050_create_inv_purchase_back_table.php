<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvPurchaseBackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invpurchasebacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dates',30);
            $table->integer('supplier_id');
            $table->integer('total_item');
            $table->decimal('transport',16,2)->nullable();
            $table->decimal('labour_cost',16,2)->nullable();
            $table->decimal('fine',16,2)->nullable();
            $table->decimal('total_payable',16,2);
            $table->decimal('total',16,2);
            $table->decimal('micro_time',21,6);
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
        Schema::dropIfExists('invpurchasebacks');
    }
}
