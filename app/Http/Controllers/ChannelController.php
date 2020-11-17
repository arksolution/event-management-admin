<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
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
        return view('channel.create', compact('event'));
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
            'name' => 'required'
        ]);

        $channelName = $event->channel()->where('name', $request->name)->first();
        if ($channelName != null)
            return back()->withErrors([
                'name' => 'Name is already used'
            ])->withInput($request->input());

        $event->channel()->create($data);
        return redirect()->route('event.show', $event->id)->with(['message' => 'Channel success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit(Channel $channel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, Channel $channel)
    {
        if ($event->organizer->id != Auth::user()->id)
            return abort(404);

        if ($channel->room->count() != 0)
            return back()->with([
                'message' => 'Channel is already used',
                'status' => 'danger'
            ]);

        $channel->delete();
        return redirect()->route('event.show', $event->id)->with([
            'message' => 'Channel successfully destroyed'
        ]);
    }
}
