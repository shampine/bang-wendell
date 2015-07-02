<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    DB::table('location_status')->insert([
      'location' => 'wendell',
      'nicename' => 'Wendell',
      'is_open'  => true
    ]);
  }

}
