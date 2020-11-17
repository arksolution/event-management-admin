<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Event;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
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
        return view('room.create', compact('event'));
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
           'name' => 'required',
           'channel_id' => 'required',
           'capacity' => 'required|numeric|min:0',
        ]);

        $nameRoom = Channel::find($request['channel_id'])->room->where('name', $request['name'])->first();

        if ($nameRoom != null)
            return back()->withErrors([
                'name' => 'Name is already used'
            ])->withInput($request->input());

        Room::create($data);
        return redirect()->route('event.show', $event->id)->with(['message' => 'Room success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, Room $room)
    {
        if ($event->organizer->id != Auth::user()->id)
            return abort(404);

        if ($room->session->count() != 0)
            return  back()->with([
                'message' => 'Room is already used',
                'status' => 'danger'
            ]);

        $room->delete();
        return redirect()->route('event.show', $event->id)->with([
            'message' => 'Room successfully destroyed'
        ]);
    }
}
