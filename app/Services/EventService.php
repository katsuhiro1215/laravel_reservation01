<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EventService
{
  public static function checkEventDuplication($eventDate, $startTime, $endTime)
  {
    $check = DB::table('events')
      ->whereDate('start_date', $eventDate)
      ->whereTime('end_date', '>', $startTime)
      ->whereTime('start_date', '<', $endTime)
      ->exists();

    return $check;
  }

  public static function countEventDuplication($eventDate, $startTime, $endTime)
  {
    $count = DB::table('events')
      ->whereDate('start_date', $eventDate)
      ->whereTime('end_date', '>', $startTime)
      ->whereTime('start_date', '<', $endTime)
      ->count();

    return $count;
  }

  public static function joinDateAndTime($date, $time)
  {
    $join = $date . " " . $time;
    $dateTime = Carbon::createFromFormat('Y-m-d H:i', $join);

    return $dateTime;
  }
}
