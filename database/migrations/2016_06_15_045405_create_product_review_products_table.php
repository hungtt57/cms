<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gtin')->unique();
            $table->integer('review_count')->unsigned()->default(0);
            $table->integer('max_review')->unsigned()->default(0);
            $table->integer('reviewing_by')->nullable()->default(null);
            $table->text('cached_info')->nullable()->default(null);
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
        Schema::drop('product_review_products');
    }
}
