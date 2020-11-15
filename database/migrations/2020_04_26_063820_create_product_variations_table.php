<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('product_variation_type_id')->index()->nullable();
            $table->string('variation_name')->nullable();
            $table->double('point');
            $table->double('price');
            $table->double('base_price');
            $table->integer('stock');
            $table->double('weight');
            $table->integer('orderable');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_variation_type_id')->references('id')->on('product_variation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
