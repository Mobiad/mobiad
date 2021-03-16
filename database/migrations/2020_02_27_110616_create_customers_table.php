<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname');
            $table->string('phone');
            $table->string('businessname');
            $table->text('businessdesc');
            $table->integer('subscription_period');
            $table->boolean('verified')->default(0);
            $table->boolean('tune')->default(0);
            $table->string('voice')->nullable();
            $table->string('ref')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('subscriber_numbers');
            $table->boolean('terms_and_conditions');
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
        Schema::dropIfExists('customers');
    }
}
