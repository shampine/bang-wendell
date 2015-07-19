<?php

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimestampDefaults extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement("ALTER TABLE `location_status` CHANGE `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    DB::statement("ALTER TABLE `location_status` CHANGE `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    DB::statement("ALTER TABLE `change_requests` CHANGE `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    DB::statement("ALTER TABLE `change_requests` CHANGE `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // Doesn't matter.
  }

}
