<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use App\Services\MyPageService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyPageController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $events = $user->events;
        $fromTodayEvents = MyPageService::reservedEvent($events, 'fromToday');
        $pastEvents = MyPageService::reservedEvent($events, 'past');

        // dd($user, $events, $fromTodayEvents, $pastEvents);
        return view('mypage/index', compact('fromTodayEvents', 'pastEvents'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        // dd($reservation);

        return view('mypage/show', compact('event', 'reservation'));
    }

    public function cancel($id)
    {
        // dd($id);
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        $reservation->canceled_date = Carbon::now()->format('Y-m-d H:i:s');
        $reservation->save();

        session()->flash('status', 'キャンセルしました。');

        return to_route('dashboard');
    }
}
