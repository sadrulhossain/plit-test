<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->enum('action', ['0', '1', '2'])->comment('1=Created, 2=Updated');
            $table->dateTime('taken_at', $precision = 0);
            $table->integer('taken_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_log');
    }
}
