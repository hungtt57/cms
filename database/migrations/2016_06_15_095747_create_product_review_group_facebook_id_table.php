<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewGroupFacebookIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_group_facebook_id', function (Blueprint $table) {
            $table->integer('group_id')->nullable()->default(null)->unsigned();
            $table->string('facebook_id')->nullable()->default(null);

            $table->primary(['group_id', 'facebook_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_review_group_facebook_id');
    }
}
