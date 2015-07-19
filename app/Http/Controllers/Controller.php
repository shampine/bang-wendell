<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Controller extends BaseController
{

  // GET REQUESTS //

  /**
   * Listens on GET requests for route /
   *
   * @return view
   */
  public function getIndex()
  {
    return view('index', [
      'status' => $this->isWendellOpen(),
      'hours'  => $this->getTodaysHours(),
    ]);
  }

  // POST REQUESTS //

  /**
   * Listens on POST requests for route /
   *
   * @return redirect
   */
  public function postIndex(Request $request)
  {
    $location_id = 1;
    $is_open     = $request->input('state') === 'open' ? 1 : 0;
    $id_address  = isset($_SERVER['X-Forwarded-For']) ? $_SERVER['X-Forwarded-For'] : null;

    $change  = app('db')->insert()
    $state   = app('db')->update("UPDATE `location_status` SET `is_open`=$is_open WHERE `id`=$location_id");
    return redirect()->back()->with('state', $state);
  }

  // Helpers //

  /**
   * Returns an array of the weekly schedule for the location. Allows
   * us to use it else where. Eventually move these to the status model
   * if and when we write it
   */
  private function getLocationHours()
  {
    return [
      "Sun" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Mon" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Tue" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Wed" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Thu" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Fri" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Sat" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
    ];
  }

  /**
   * This is where the current day is taken from the array, then the available times the
   * bar is open that day are tested to see if it falls in the middle. Cool solution from
   * stackoverflow especially if you have multiple timeslots in a day, e.g., 8-12 && 1-5.
   *
   * http://stackoverflow.com/questions/14904864/determine-if-business-is-open-closed-based-on-business-hours
   *
   * @return bool
   */
  private function isWendellOpen()
  {
    $schedule  = $this->getLocationHours();
    $timestamp = time();
    $current   = (new \DateTime())->setTimestamp($timestamp);
    $status    = false;

    foreach($schedule[date('D', $timestamp)] as $start => $end) {
      $start = \DateTime::createFromFormat('h:i A', $start);
      $end   = \DateTime::createFromFormat('h:i A', $end);

      if(($start < $current) && ($current < $end)) {
        $status = true;
        break;
      }
    }
    return $status;
  }

  /**
   * Returns a string of the days operating hours for display in the view
   *
   * @return string
   */
  private function getTodaysHours()
  {
    $schedule = $this->getLocationHours();
    $count    = 0;
    $time     = "";
    foreach($schedule[date('D', time())] as $start => $end) {
      $time = $count === 0 ? $end : "$start - $time";
      $count++;
    }
    return $time;
  }

}
