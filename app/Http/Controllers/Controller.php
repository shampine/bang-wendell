<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

  public function getIndex()
  {
    return view('index', ['status' => $this->isWendellOpen()]);
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
    $schedule = [
      "Sun" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Mon" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Tue" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Wed" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Thu" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Fri" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Sat" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
    ];
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

}
