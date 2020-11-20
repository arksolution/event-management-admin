<?php

namespace App\Http\Controllers;

use App\Event;
use App\Speaker;
use App\Speaker_session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $event = Auth::user()->event->sortBy('date');
        return view('event.index', compact('event'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
           'name' => 'required',
           'slug' => 'regex:/^([0-9a-z]+(-[0-9a-z]+)*)+$/',
           'date' => 'required|date_format:Y-m-d',
        ],[
            'slug.regex' => '"Slug must not be empty and only contain a-z, 0-9 and \'-\''
        ]);

        if (Auth::user()->event->where('name', $data['name'])->count() != 0)
            return back()->withErrors([
                'name' => 'Name is already used'
            ])->withInput($request->input());

        if (date_format(date_create($request['date']), 'Y m d') < date('Y m d'))
        return back()->withErrors([
            'date' => 'Date is not right'
        ])->withInput($request->input());

        foreach (Auth::user()->event as $value)
            if ($value->slug == $data['slug'])
                return back()->withErrors([
                    'slug' => 'Slug is already used'
                ])->withInput($request->input());

        $event = Auth::user()->event()->create($data);
        return redirect()->route('event.show', $event->id)->with(['message' => 'Event success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');

        foreach ($event->room as $room)
            foreach ($room->session as $session){
                $session->speaker = '';

                $speaker = [];

                foreach (Speaker_session::where('session_id', $session->id)->get() as $value){
                    array_push($speaker, Speaker::find($value->speaker_id));
                }

                $i = 0;
                foreach ($speaker as $value){
                    $session->speaker .= $value->name;
                    $i++;
                    if (count($speaker) > $i)
                    $session->speaker .= ', ';
                }

            }

        return view('event.details', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');

        return  view('event.edit', compact('event'))->with(['message' => 'Event success updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');

        $data = $request->validate([
            'name' => 'required',
            'slug' => 'regex:/^([0-9a-z]+(-[0-9a-z]+)*)+$/',
            'date' => 'required|date_format:Y-m-d',
        ],[
            'slug.regex' => '"Slug must not be empty and only contain a-z, 0-9 and \'-\''
        ]);

        if (Auth::user()->event->where('id', '<>', $event->id)->where('name', $data['name'])->count() != 0)
            return back()->withErrors([
                'name' => 'Name is already used'
            ])->withInput($request->input());

        if (date_format(date_create($request['date']), 'Y m d') < date('Y m d'))
            return back()->withErrors([
                'date' => 'Date is not right'
            ])->withInput($request->input());

        foreach (Auth::user()->event->where('id', '<>', $event->id) as $value)
            if ($value->slug == $data['slug'])
                return back()->withErrors([
                    'slug' => 'Slug is already used'
                ])->withInput($request->input());

        $event->update($data);
        return redirect()->route('event.show', $event->id)->with(['message' => 'Event success updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        if ($event->organizer->id != Auth::user()->id)
            return abort(404);

        if ($event->channel->count() != 0 || $event->ticket->count() != 0)
            return back()->with([
                'message' => 'Event is already used',
                'status' => 'danger'
            ]);

        $event->delete();
        return redirect()->route('event.index')->with([
            'message' => 'Event successfully destroyed'
        ]);
    }

    public function report(Event $event){
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');
        $session = $event->room->map(function ($x) {
            return $x->session;
        })->collapse()->sortBy('start')->values();

        $count = 0;
        foreach ($event->ticket as $ticket){
            $count += $ticket->registration->count();
        }

        foreach ($session as $value){
            $value->capcaity = $value->room->capacity;
            if ($value->type == 'talk')
                $value->attendee = $count;
            else
                $value->attendee = $value->session_registration->count();
        }

        return view('event.report', compact('event', 'session'));
    }
}
