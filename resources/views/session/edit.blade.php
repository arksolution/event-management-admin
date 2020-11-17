@extends('layouts.main', compact('event'))

@section('content')
    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Edit session</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="{{route('session.update', [$event->id, $session->id])}}" method="post">
        @method('PATCH')
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectType">Type</label>
                <select class="form-control" id="selectType" name="type">
                    <option value="talk" {{old('type') == 'talk' ? 'selected' : $session->type == 'talk' ? 'selected' : ''}}>Talk</option>
                    <option value="workshop" {{old('type') == 'workshop' ? 'selected' : $session->type == 'workshop' ? 'selected' : ''}}>Workshop</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputTitle">Title</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{$errors->first('title') ? 'is-invalid' : ''}}" id="inputTitle" name="title" placeholder=""
                       value="{{old('title') ? old('title') : $session->title}}">
                <div class="invalid-feedback">
                    {{$errors->first('title')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSpeaker">Speaker</label>
                <select id="inputSpeaker" name="speaker[]" class="form-control {{$errors->first('speaker') ? 'is-invalid' : ''}}" multiple>
                    <option value="" disabled>Choose speaker...</option>
                    @foreach($speakers as $speaker)
                        <option value="{{$speaker->id}}" {{old('speaker') ? (in_array($speaker->id, old('speaker')) ? 'selected' : '' ) : (in_array($speaker->id, $session_speaker) ? 'selected' : '')}}>{{$speaker->name}}</option>
                    @endforeach
                </select>
                <div>Hold ctrl to select more</div>
                <div class="invalid-feedback">
                    {{$errors->first('speaker')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectRoom">Room</label>
                <select class="form-control {{$errors->first('room_id') ? 'is-invalid' : ''}}" id="selectRoom" name="room_id">
                    @foreach($event->room as $room)
                        <option value="{{$room->id}}" {{old('room_id') == $room->id ? 'selected' : $session->room_id == $room->id ? 'selected' : ''}}>{{$room->name}} / {{$room->channel->name}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{$errors->first('room_id')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCost">Cost</label>
                <input type="number" class="form-control {{$errors->first('cost') ? 'is-invalid' : ''}}" id="inputCost" name="cost" placeholder=""
                       value="{{old('cost') ? old('cost') : ($session->cost == '' ? 0 : $session->cost)}}">
                <div class="invalid-feedback">
                    {{$errors->first('cost')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <label for="inputStart">Start</label>
                <input type="text"
                       class="form-control {{$errors->first('start') ? 'is-invalid' : ''}}"
                       id="inputStart"
                       name="start"
                       placeholder="yyyy-mm-dd HH:MM"
                       value="{{old('start') ? old('start') : date('Y-m-d H:i', strtotime($session->start))}}">
                <div class="invalid-feedback">
                    {{$errors->first('start')}}
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <label for="inputEnd">End</label>
                <input type="text"
                       class="form-control {{$errors->first('end') ? 'is-invalid' : ''}}"
                       id="inputEnd"
                       name="end"
                       placeholder="yyyy-mm-dd HH:MM"
                       value="{{old('end') ? old('end') : date('Y-m-d H:i', strtotime($session->end))}}">
                <div class="invalid-feedback">
                    {{$errors->first('end')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label for="textareaDescription">Description</label>
                <textarea class="form-control {{$errors->first('description') ? 'is-invalid' : ''}}" id="textareaDescription" name="description" placeholder="" rows="5">{{old('description') ? old('description') : $session->description}}</textarea>
                <div class="invalid-feedback">
                    {{$errors->first('description')}}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Save session</button>
        <a href="{{route('event.show', $event->id)}}" class="btn btn-link">Cancel</a>
    </form>
@endsection
