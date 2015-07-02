<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLocationStatus extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('location_status', function($table)
    {
      $table->increments('id');
      $table->string('location')->nullable();
      $table->string('nicename')->nullable();
      $table->boolean('is_open')->default(false);
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
    Schema::dropIfExists('location_status');
  }

}
