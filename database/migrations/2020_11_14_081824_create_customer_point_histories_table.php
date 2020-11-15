<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPointHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_point_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_point_id')->index();
            $table->morphs('cp_history');
            $table->double('point');
            $table->text('description');
            $table->timestamps();

            $table->foreign('customer_point_id')->references('id')->on('customer_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_point_histories');
    }
}
