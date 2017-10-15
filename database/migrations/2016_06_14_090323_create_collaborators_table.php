<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaboratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('avatar');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->boolean('password_change_required');
            $table->rememberToken();
            $table->decimal('balance', 22, 4)->default(0);
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
        Schema::drop('collaborators');
    }
}
