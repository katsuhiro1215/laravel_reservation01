<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Services\EventService;

class EventController extends Controller
{

    public function index()
    {
        $today = Carbon::today();

        $events = DB::table('events')
        ->whereDate('start_date', '>=', $today)
        ->orderBy('start_date', 'asc')
        ->paginate(10);

        return view('manager.events.index', compact('events'));
    }

    public function create()
    {
        return view('manager.events.create');
    }

    public function store(StoreEventRequest $request)
    {

        $check = EventService::checkEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        // $check = DB::table('events')
        // ->whereDate('start_date', $request['event_date'])
        // ->whereTime('end_date', '>', $request['start_time'])
        // ->whereTime('start_date', '<', $request['end_time'])
        // ->exists();

        // dd($check);

        if ($check) {
            return view('manager.events.create');
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        // $start = $request['event_date'] . " " .$request['start_time']; 
        // $startDate = Carbon::createFromFormat(
        //     'Y-m-d H:i', $start
        // );

        // $end = $request['event_date'] . " " .$request['end_time'];
        // $endDate = Carbon::createFromFormat(
        //     'Y-m-d H:i', $end
        // );

        Event::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'],
        ]);

        return to_route('events.index');
    }

    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        // dd($eventDate, $startTime, $endTime);

        return view('manager.events.show', compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    public function edit(Event $event)
    {
        $event = Event::findOrFail($event->id);
    
        $today = Carbon::today()->format('Y年m月d日');
        if($event->eventDate < $today) {
            return abort(404);
        }

        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;


        return view('manager.events.edit', compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $check = EventService::countEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        if ($check > 1) {
            $event = Event::findOrFail($event->id);
            $eventDate = $event->editEventDate;
            $startTime = $event->startTime;
            $endTime = $event->endTime;
            return view('manager.events.edit', compact('event', 'eventDate', 'startTime', 'endTime'));
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        $event = Event::findOrFail($event->id);

        $event->name = $request['event_name'];
        $event->information = $request['information'];
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->max_people = $request['max_people'];
        $event->is_visible = $request['is_visible'];
        $event->save();

        return to_route('events.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    public function past()
    {
        $today = Carbon::today();
        $events = DB::table('events')
        ->whereDate('start_date', '<', $today)
        ->orderBy('start_date', 'desc')
        ->paginate(10);

        return view('manager.events.past', compact('events'));
    }
}
