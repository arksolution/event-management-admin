<?php

namespace App\Http\Controllers;

use App\Event;
use App\Event_ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
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
        return view('ticket.create', compact('event'));
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
           'cost' => 'required',
            'special_validity' => ''
        ]);

        if ($data['special_validity'] != null)
            if ($data['special_validity'] == 'amount')
                $data['special_validity'] = json_encode([
                   'type' => 'amount',
                   'amount' => $request->validate([
                       'amount' => 'required|numeric|min:0'
                   ])['amount']
                ]);
            else
                $data['special_validity'] = json_encode([
                    'type' => 'date',
                    'date' => $request->validate([
                        'valid_until' => 'required|date'
                    ])['valid_until']
                ]);

            $event->ticket()->create($data);
            return redirect()->route('event.show', $event->id)->with(['message' => 'Ticket success created']);
    }

    /**
     * Display the specified resource.
     *
     * @param Event_ticket $event_ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Event_ticket $event_ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Event_ticket $event_ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Event_ticket $event_ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Event_ticket $event_ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event_ticket $event_ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @param Event_ticket $event_ticket
     * @return void
     */
    public function destroy(Event $event, $id)
    {
        $ticket = Event_ticket::find($id);

        if ($ticket->event->organizer->id != Auth::user()->id)
            return abort(404);

        if ($ticket->registration->count() != 0)
            return back()->with([
                'message' => 'Ticket already used',
                'status' => 'danger'
            ]);

        $ticket->delete();
        return redirect()->route('event.show', $event->id)->with([
            'message' => 'Ticket successfully destroyed'
        ]);
    }
}
