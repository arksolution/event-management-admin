@extends('layouts.layout')

@section('content')
    @include('layouts.header')

    <script>
        window.onload = function () {
            [...document.getElementsByClassName('_close')].forEach(element => {
                element.addEventListener('mousedown', setData);
            });
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


                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Speaker</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="{{route('speaker.create')}}" class="btn btn-sm btn-outline-secondary">Create new speaker</a>
                        </div>
                    </div>
                </div>

                @if(session('message'))
                    <br>
                    <div class="alert {{str_contains(session('message'), 'success') ? 'alert-success' : 'alert-danger'}}">{{session('message')}}</div>
                @endif

                <ul class="list-group">
                    @foreach($speaker as $value)
                        <li class="list-group-item list-speaker">
                            <div class="media speaker">
                                <img class="avatar-img" src="{{asset('public/uploads/avatars/'.$value->avatar)}}?key={{time()}}">
                                <div class="media-body">
                                    <h5 class="mt-0">{{$value->name}}</h5>
                                    <a class="mt-0" href="//{{$value->link}}" target="_blank" role="link">Review</a>
                                    <div class="mt-0">{{$value->info}}</div>
                                    <a href="{{route('speaker.edit', $value->id)}}" class="btn btn-primary" style="margin-right: 10px">Edit</a>
                                    <a href="" class="_close btn btn-danger" data-toggle="modal" data-target="#myModal" data-request="{{route('speaker.destroy', $value->id)}}">Delete</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>


            </main>
        </div>
    </div>

@endsection
