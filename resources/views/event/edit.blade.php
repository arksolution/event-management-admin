@extends('layouts.app', compact('event'))

@section('content')
    <div class="border-bottom mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{{$event->name}}</h1>
        </div>
    </div>

    <form class="needs-validation" novalidate action="{{route('event.update', $event->id)}}" method="post">
        @method('PATCH')
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Name</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{$errors->first('name') ? "is-invalid" : ''}}" id="inputName" name="name" placeholder=""
                       value="{{old('name') ? old('name') : $event->name}}">
                <div class="invalid-feedback">
                    {{$errors->first('name')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSlug">Slug</label>
                <input type="text" class="form-control {{$errors->first('slug') ? "is-invalid" : ''}}" id="inputSlug" name="slug" placeholder=""
                       value="{{old('slug') ? old('slug') : $event->slug}}">
                <div class="invalid-feedback">
                    {{$errors->first('slug')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputDate">Date</label>
                <input type="text"
                       class="form-control {{$errors->first('date') ? "is-invalid" : ''}}"
                       id="inputDate"
                       name="date"
                       placeholder="yyyy-mm-dd"
                       value="{{old('date') ? old('date') : $event->date}}">
                <div class="invalid-feedback">
                    {{$errors->first('date')}}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Save event</button>
        <a href="{{route('event.index')}}" class="btn btn-link">Cancel</a>
    </form>
    @endsection
