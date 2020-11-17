@extends('layouts.main', compact('event'))

@section('content')
    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Create new channel</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="{{route('channel.store', $event->id)}}" method="post">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Name</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}" id="inputName" name="name" placeholder=""
                       value="{{old('name') ? old('name') : ''}}">
                <div class="invalid-feedback">
                    {{$errors->first('name')}}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Save channel</button>
        <a href="{{route('event.show', $event->id)}}" class="btn btn-link">Cancel</a>
    </form>
    @endsection
