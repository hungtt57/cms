<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gln', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('gln')->index();
            $table->integer('business_id')->unsigned()->index();
            $table->integer('country_id')->unsigned()->index();
            $table->string('address');
            $table->string('email');
            $table->string('phone_number');
            $table->string('fax');
            $table->string('website');
            $table->text('contact_info');
            $table->tinyInteger('status')->unsigned();
            $table->string('icheck_id');
            $table->text('additional_info');
            $table->string('certificate_file');
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
        Schema::drop('gln');
    }
}
