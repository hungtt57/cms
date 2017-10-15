<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_reviews', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('review_by')
                ->unsigned()
                ->index()
            ;

            $table->string('gtin')->index();
            $table->text('content');
            $table->decimal('price', 22, 4)->default(0);
            $table->tinyInteger('status')->unsigned();
            $table->integer('approved_by')
                ->index()
                ->nullable()
                ->default(null)
                ->unsigned()
            ;
            $table->timestamp('approved_at')
                ->nullable()
                ->default(null)
            ;
            $table->text('note');
            $table->text('error_message');
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
        Schema::drop('product_review_reviews');
    }
}
