@extends('layouts.layout')

@section('content')
    @include('layouts.header')

    <script>
        window.onload = function () {
            [...document.getElementsByClassName('close')].forEach(element => {
                element.addEventListener('mousedown', setData);
            });
            [...document.getElementsByClassName('event')].forEach(element => {
                element.addEventListener('mousedown', _ => {
                    element.childNodes[1].click();
                });
            })
        };

        function setData(ev) {
            ev.stopPropagation();
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

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="{{route('event.index')}}">Manage Events</a></li>
                    </ul>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="{{route('speaker.index')}}">Speaker</a></li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

                @if(session('message'))
                    <br>
                    <div class="alert alert-{{session('status') ?? 'success'}}">{{session('message')}}</div>
                @endif

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Events</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="{{route('event.create')}}" class="btn btn-sm btn-outline-secondary">Create new event</a>
                        </div>
                    </div>
                </div>

                <div class="row events">
                    @foreach($event as $value)
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <div class="btn text-left event">
                                    <a href="{{route('event.show', $value->id)}}" style="display: none"></a>
                                    <div class="card-body">
                                        <div class="close" data-toggle="modal" data-target="#myModal" data-request="{{route('event.destroy', $value->id)}}">&times;</div>
                                        <h5 class="card-title">{{$value->name}}</h5>
                                        <p class="card-subtitle">{{date('F j, Y', strtotime($value->date))}}</p>
                                        <hr>
                                        <p class="card-text">{{$value->registration->count()}} registrations</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>

            </main>
        </div>
    </div>
    @endsection
