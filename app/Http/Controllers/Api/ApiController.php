<?php

namespace App\Http\Controllers\Api;

use App\Attendee;
use App\Event;
use App\Event_ticket;
use App\Http\Resources\ChannelRS;
use App\Http\Resources\EventRS;
use App\Http\Resources\RegistrationRS;
use App\Http\Resources\SpeakerRS;
use App\Http\Resources\TicketRS;
use App\Organizer;
use App\Speaker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    function events(){
        $event = Event::where('date', '>', '2021-09-00')->orderBy('date')->get();
        return response()->json([
          'events' =>  EventRS::collection($event)
        ], 200);
    }

    function detail($os, $es){
        $organizer= Organizer::where('slug', $os)->first();
        if ($organizer == null)
            return response(['message' => 'Organizer not found'], 404);

        $event = $organizer->event->where('slug', $es)->first();
        if ($event == null)
            return response(['message' => 'Event not found'], 404);

        return response()->json([
            'id' => $event->id,
            'name' => $event->name,
            'slug' => $event->slug,
            'date' => $event->date,
            'channels' => ChannelRS::collection($event->channel),
            'tickets' => TicketRS::collection($event->ticket)
        ], 200);
    }

    function login(){
        $data = \request();
        if ($data['username'] == null)
            return response(['message' => 'Invalid login'], 401);

        $attendee = Attendee::where('username', $data['username'])
            ->where('password', bcrypt($data['password']))
            ->first();

        if ($attendee == null)
            return response(['message' => 'Invalid login'], 401);

        $token = openssl_random_pseudo_bytes(16);

        $token = bin2hex($token);

        $attendee->update([
            'login_token' => $token
        ]);

        return response()
            ->json([
                'firstname' => $attendee->firstname,
                'lastname' => $attendee->lastname,
                'username' => $attendee->username,
                'email' => $attendee->email,
                'token' => $attendee->login_token,
            ], 200);
    }

    function logout(){
        $data = \request();
        if ($data['token'] == null)
            return response(['message' => 'Invalid token'], 401);

        $attendee = Attendee::where('login_token', $data['token'])->first();

        if ($attendee == null)
            return response(['message' => 'Invalid token'], 401);

        $attendee->update([
            'login_token' => ''
        ]);

        return response(['message' => 'Logout success'], 200);
    }

    function registerAccount(){
        $data = \request();
        if (Attendee::where('username', $data['username'])->first() != null)
            return response()->json([
                'message' => 'Username is already used'
            ], 401);

        $data = $data->validate([
            'firstname' => '',
            'lastname' => '',
            'username' => '',
            'email' => '',
            'login_token' => '',
            'password' => '',
        ]);

        $data['password'] = bcrypt($data['password']);

        Attendee::create($data);
        return response()->json([
            'message' => 'Account successfully created'
        ], 200);
    }

    function registration($os, $es){
        $data = \request();
        if ($data['token'] == null)
            return response(['message' => 'User not logged in'], 401);
        $attendee = Attendee::where('login_token', $data['token'])->first();
        if ($attendee == null)
            return response(['message' => 'User not logged in'], 401);

        foreach ($attendee->registration as $registration){
            if ($registration->ticket_id == $data['ticket_id'])
                return response(['message' => 'User already registered'], 401);
            foreach ($registration->ticket->event->ticket as $value)
                if ($value->id == $data['ticket_id'])
                    return response(['message' => 'User already registered'], 401);
        }

        $ticket = Event_ticket::find($data['ticket_id']);
        $ticket->special_validity();
        if (!$ticket->available)
            return response(['message' => 'Ticket is no longer available'], 401);

        $registration = $ticket->registration()->create([
            'attendee_id' => $attendee->id
        ]);

        if ($data['session_ids'] != null)
            foreach ($data['session_ids'] as $id)
                $registration->session_registration()->create([
                   'session_id' => $id
                ]);

        return response(['message' => 'Registration successful'], 200);
    }

    function registrations(){
        $data = \request();
        if ($data['token'] == null)
            return response(['message' => 'User not logged in'], 401);
        $attendee = Attendee::where('login_token', $data['token'])->first();
        if ($attendee == null)
            return response(['message' => 'User not logged in'], 401);

        return response()->json([
            'registrations' => RegistrationRS::collection($attendee->registration)
        ], 200);
    }

    function speaker($id){
        if ($id == null)
            return response()->json([
                'message' => 'Speaker not found'
            ], 404);

        $speaker = Speaker::find($id);
        if ($speaker == null)
            return response()->json([
                'message' => 'Speaker not found'
            ], 404);

        return response()->json([
            'speaker' => new SpeakerRS($speaker)
        ], 200);
    }
}
