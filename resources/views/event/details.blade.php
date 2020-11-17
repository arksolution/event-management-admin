@extends('layouts.app', compact('event'))

@section('content')
    <script>
        window.onload = function () {
          [...document.getElementsByClassName('close')].forEach(element => {
              element.addEventListener('mousedown', setData);
          })
        };

        function setData(ev) {
            document.getElementById('ticket-form').action = ev.target.attributes['data-request'].value;
        }
    </script>


    <div class="modal" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Do you want to delete this?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" href=""
                       onclick="event.preventDefault();
                                                     document.getElementById('ticket-form').submit();">
                        Delete
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <form id="ticket-form" action="" method="POST" style="display: none;">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('message'))
        <br>
        <div class="alert alert-{{session('status') ?? 'success'}}">{{session('message')}}</div>
    @endif
        <div class="border-bottom mb-3 pt-3 pb-2 event-title">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h1 class="h2">{{$event->name}}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="{{route('event.edit', $event->id)}}" class="btn btn-sm btn-outline-secondary">Edit event</a>
                    </div>
                </div>
            </div>
            <span class="h6">{{$event->date}}</span>
        </div>

        <!-- Tickets -->
        <div id="tickets" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Tickets</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="{{route('ticket.create', $event->id)}}" class="btn btn-sm btn-outline-secondary">
                            Create new ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row tickets">
            @foreach($event->ticket as $ticket)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="close" data-toggle="modal" data-target="#myModal" data-request="{{route('ticket.destroy', [$event->id, $ticket->id])}}">&times;</div>

                        <h5 class="card-title">{{$ticket->name}}</h5>
                        <p class="card-text">{{$ticket->cost}}.-</p>
                        {{$ticket->special_validity()}}
                        <p class="card-text">{{$ticket->description}}&nbsp</p>
                    </div>
                </div>
            </div>
                @endforeach
        </div>

        <!-- Sessions -->
        <div id="sessions" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Sessions</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="{{route('session.create', $event->id)}}" class="btn btn-sm btn-outline-secondary">
                            Create new session
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive sessions">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Type</th>
                    <th class="w-100">Title</th>
                    <th>Speaker</th>
                    <th>Channel</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($event->room as $room)
                    @foreach($room->session as $session)
                        <tr>
                            <td class="text-nowrap">{{date('H:i', strtotime($session->start))}} - {{date('H:i', strtotime($session->end))}}</td>
                            <td>{{$session->type}}</td>
                            <td><a href="{{route('session.edit', [$event->id, $session->id])}}">{{$session->title}}</a></td>
                            <td class="text-nowrap">{{$session->speaker}}</td>
                            <td class="text-nowrap">{{$room->channel->name}} / {{$room->name}}</td>
                            <td class="close" data-toggle="modal" data-target="#myModal" data-request="{{route('session.destroy', [$event->id, $session->id])}}">&times;</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Channels -->
        <div id="channels" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Channels</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="{{route('channel.create', $event->id)}}" class="btn btn-sm btn-outline-secondary">
                            Create new channel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row channels">
            @foreach($event->channel as $channel)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="close" data-toggle="modal" data-target="#myModal" data-request="{{route('channel.destroy', [$event->id, $channel->id])}}">&times;</div>
                        <h5 class="card-title">{{$channel->name}}</h5>
                        <p class="card-text">{{$channel->session->count()}} sessions, {{$channel->room->count()}} room</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Rooms -->
        <div id="rooms" class="mb-3 pt-3 pb-2">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2 class="h4">Rooms</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="{{route('room.create', $event->id)}}" class="btn btn-sm btn-outline-secondary">
                            Create new room
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive rooms">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($event->room as $room)
                <tr>
                    <td>{{$room->name}}</td>
                    <td>{{$room->capacity}}</td>
                    <td><div class="close" data-toggle="modal" data-target="#myModal" data-request="{{route('room.destroy', [$event->id, $room->id])}}">&times;</div></td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endsection
