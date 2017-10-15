<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('logo');
            $table->integer('country_id')->unsigned()->index();
            $table->string('address');
            $table->string('email');
            $table->string('phone_number');
            $table->string('fax');
            $table->string('website');
            $table->text('contact_info');
            $table->string('login_email')->index();
            $table->string('password');
            $table->boolean('password_change_required');
            $table->rememberToken();
            $table->tinyInteger('status')->unsigned();
            $table->integer('activated_by')->nullable()->unsigned()->index();
            $table->timestamp('activated_at')->nullable();
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
        Schema::drop('businesses');
    }
}
