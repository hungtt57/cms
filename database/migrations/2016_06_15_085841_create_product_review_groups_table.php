<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icheck_id')->unique();
            $table->string('name');
            $table->string('categories')->index();
            $table->integer('review_count')->unsigned()->default(0);
            $table->integer('max_review')->unsigned()->default(0);
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
        Schema::drop('product_review_groups');
    }
}
