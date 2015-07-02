<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChangeRequests extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('change_requests', function($table)
    {
      $table->increments('id');
      $table->integer('location_id')->references('id')->on('location_status');
      $table->boolean('is_open')->default(false);
      $table->string('ip_address')->nullable();
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
    Schema::dropIfExists('change_requests');
  }

}