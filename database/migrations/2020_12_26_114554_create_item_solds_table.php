<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_solds', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('price');
            $table->string('quantity');
            $table->string('total');
            $table->string('costumer_name');
            $table->string('receipt_number');
            $table->string('cashier');
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
        Schema::dropIfExists('item_solds');
    }
}
