<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Event;
use App\Room;
use App\Session;
use App\Session_registration;
use App\Speaker;
use App\Speaker_session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Event $event
     * @return void
     */
    public function create(Event $event)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');
        $speakers = Speaker::all();
        return view('session.create', compact('event', 'speakers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Event $event
     * @return void
     */
    public function store(Request $request, Event $event)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');

        $data = $request->validate([
           'title' => 'required',
           'type' => 'required',
           'cost' => 'required|numeric|min:0',
           'room_id' => 'required',
           'start' => 'required',
           'end' => 'required',
           'description' => 'required',
        ]);

        $speaker = $request->validate([
            'speaker' => 'required',
        ]);

        if (Room::find($data['room_id'])->session->where('title', $data['title'])->count() != 0)
            return back()->withErrors([
                'title' => 'Title is already used',
            ])
                ->withInput($request->input());

        if ($data['start'] < date('Y-m-d'))
            return back()->withErrors([
                'start' => 'Starting time must be greater than current day',
            ])
                ->withInput($request->input());

        if ($data['start'] < Room::find($data['room_id'])->channel->event->date)
            return back()->withErrors([
                'start' => 'Starting time must be greater than event date',
            ])
                ->withInput($request->input());

        if ($data['start'] >= $data['end'])
            return back()->withErrors([
                'start' => 'Starting time must be greater than end time',
                'end' => 'End time must be greater than end time'
            ])->withInput($request->input());

        if (date('H', strtotime($data['start'])) < 9)
            return back()->withErrors([
                    'start' => 'Starting time should be greater than 9 hours',
                ])->withInput($request->input());

        if (date('H', strtotime($data['end'])) > 17)
            return back()->withErrors([
                'end' => 'End time should be less than 17 hours',
            ])->withInput($request->input());

        foreach (Room::find($data['room_id'])->session as $value)
            if (!($data['start'] >= $value->end || $data['end'] <= $value->start))
                return back()->withErrors(['room_id' => 'Room already booked during this time'])
                    ->withInput($request->input());

        $session = Session::create($data);

        foreach ($speaker['speaker'] as $value){
            Speaker_session::create([
                'session_id' => $session->id,
                'speaker_id' => $value,
            ]);
        }

        return redirect()->route('event.show', $event->id)->with(['message' => 'Session success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Event $event
     * @param \App\Session $session
     * @return void
     */
    public function edit(Event $event, Session $session)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');

        $session_speaker = [];

        $speakers = Speaker::all();

        foreach (Speaker_session::where('session_id', $session->id)->get() as $value){
            array_push($session_speaker, $value->speaker_id);
        }

        return view('session.edit', compact('event', 'session', 'speakers', 'session_speaker'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Event $event
     * @param \App\Session $session
     * @return void
     */
    public function update(Request $request, Event $event, Session $session)
    {
        if (Auth::user()->id != $event->organizer->id)
            return abort('404');

        $data = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'cost' => 'required|numeric|min:0',
            'room_id' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
        ]);

        $speaker = $request->validate([
            'speaker' => 'required',
        ]);

        if ($session->type == 'workshop' && $data['type'] == 'talk')
            if (Session_registration::where('session_id', $session->id)->first() != null)
                return back()->withErrors([
                    'type' => 'The session is registered, cannot be changed to the type'
                ])->withInput();

        if (Room::find($data['room_id'])->session->where('id', '<>', $session->id)->where('title', $data['title'])->count() != 0)
            return back()->withErrors([
                'title' => 'Title is already used',
            ])
                ->withInput($request->input());

        if ($data['start'] < date('Y-m-d'))
            return back()->withErrors([
                'start' => 'Starting time must be greater than current day',
            ])
                ->withInput($request->input());

        if ($data['start'] < Room::find($data['room_id'])->channel->event->date)
            return back()->withErrors([
                'start' => 'Starting time must be greater than event date',
            ])
                ->withInput($request->input());

        if ($data['start'] >= $data['end'])
            return back()->withErrors([
                'start' => 'Starting time must be greater than end time',
                'end' => 'End time must be greater than end time'
            ])->withInput($request->input());
        if (date('H', strtotime($data['start'])) < 9)
            return back()->withErrors([
                'start' => 'Starting time should be greater than 9 o\'clock',
            ])->withInput($request->input());

        if (date('H:i', strtotime($data['end'])) > '17:00')
            return back()->withErrors([
                'end' => 'End time should be less than 17 o\'clock',
            ])->withInput($request->input());

        foreach (Room::find($data['room_id'])->session->where('id', '<>', $session->id) as $value)
            if (!($data['start'] >= $value->end || $data['end'] <= $value->start))
                return back()->withErrors(['room_id' => 'Room already booked during this time'])
                    ->withInput($request->input());


        Speaker_session::where('session_id', $session->id)->delete();

        $session->update($data);

        foreach ($speaker['speaker'] as $value){
            Speaker_session::create([
                'session_id' => $session->id,
                'speaker_id' => $value,
            ]);
        }

        return redirect()->route('event.show', $event->id)->with(['message' => 'Session success updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, Session $session)
    {
        if ($event->organizer->id != Auth::user()->id)
            return abort(404);

        if ($session->session_registration()->count() != 0)
            return back()->with([
                'message' => 'Session is already used',
                'status' => 'danger'
            ]);

        foreach ($session->speaker_session as $speaker_session)
            $speaker_session->delete();
        $session->delete();
        return redirect()->route('event.show', $event->id)->with([
            'message' => 'Session successfully destroyed'
        ]);
    }
}
