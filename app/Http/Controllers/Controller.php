<?php namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{

  /**
   * Protected class vars
   *
   * @var $location object
   */
  private $location;

  /**
   * Construct
   *
   * @return void
   */
   function __construct()
   {
    $this->location   = DB::select('SELECT * FROM location_status WHERE id = ?', [1]);
    $this->location   = isset($this->location[0]) ? $this->location[0] : false;
    $this->ip_address = $this->setIpAddress();
   }

  // GET REQUESTS //

  /**
   * Listens on GET requests for route /
   *
   * @return view
   */
  public function getIndex()
  {
    $status = $this->isWendellOpen();
    $report = false;

    if($status != $this->location->is_open && strtotime($this->getTimezoneAdjusted($this->location->updated_at)) > strtotime(date("Y-m-d H:i:s")) - 43140) {
      $status = $this->location->is_open;
      $report = DB::select('SELECT * FROM `change_requests` WHERE is_open = ? ORDER BY id DESC LIMIT 1', [$this->location->is_open]);
      $report = isset($report[0]) ? $report[0] : false;
      if($report) {
        $report->created_at = strtotime($this->getTimezoneAdjusted($report->created_at));
        $report->updated_at = strtotime($this->getTimezoneAdjusted($report->updated_at));
      }
    }

    return view('index', [
      'status' => $status,
      'report' => $report,
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
    $is_open     = $request->input('state') === 'open' ? true : false;
    $recent      = DB::select('SELECT * FROM `change_requests` 
                               WHERE is_open = ? 
                               AND created_at BETWEEN NOW() - INTERVAL 60 MINUTE AND NOW()
                               GROUP BY ip_address 
                               ORDER BY `updated_at` DESC',
                               [$is_open]);

    if(!$this->location) {
      return redirect()->back()->with('state', 'error');
    }

    DB::insert('INSERT INTO change_requests (location_id, is_open, ip_address) VALUES (?, ?, ?)', [$this->location->id, $is_open, $this->ip_address]);

    if(sizeof($recent) > 1) {
      DB::update('UPDATE location_status SET is_open = ?, updated_at = ? WHERE id = ?', [$is_open, date("Y-m-d H:i:s"), $this->location->id]);
    }

    return redirect()->back()->with('state', 'success');
  }

  // Helpers //

  /**
   * Sets the users IP address, and if in a development environment sets a random ip
   *
   * @return $ip_address string
   */
  private function setIpAddress()
  {
    if(\App::environment() === 'local') {
      $ip_address = (rand(0,255)).'.'.(rand(0,255)).'.'.(rand(0,255)).'.'.(rand(0,255));
      // $ip_address = '127.0.0.1';
    } else {
      $ip_address = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
    }
    return $ip_address;
  }

  /**
   * Convert timestamp
   *
   * @param $datetime string
   *
   * @return string
   */
  private function getTimezoneAdjusted($datetime)
  {
    $date     = new \DateTime($datetime.' +00');
    $date->setTimezone(new \DateTimeZone('America/Los_Angeles'));

    return $date->format('Y-m-d H:i:s');
  }

  /**
   * Returns an array of the weekly schedule for the location. Allows
   * us to use it else where. Eventually move these to the status model
   * if and when we write it
   *
   * @return array
   */
  private function getLocationHours()
  {
    return [
      "Sun" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Mon" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Tue" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Wed" => ["12:00 AM" => "02:00 AM", "08:00 PM" => "11:59 PM"],
      "Thu" => ["12:00 AM" => "02:00 AM", "05:00 PM" => "11:59 PM"],
//       "Fri" => ["12:00 AM" => "02:00 AM", "05:00 PM" => "11:59 PM"],
      "Fri" => ["12:00 AM" => "02:00 AM", "2:01 AM" => "2:02 AM"],
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
