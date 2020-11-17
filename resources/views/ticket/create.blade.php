@extends('layouts.main', compact('event'))

@section('content')
    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Create new ticket</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="{{route('ticket.store', $event->id)}}" method="post">
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

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCost">Cost</label>
                <input type="number" class="form-control {{$errors->first('cost') ? 'is-invalid' : ''}}" id="inputCost" name="cost" placeholder=""
                       value="{{old('cost') ? old('cost') : '0'}}">
                <div class="invalid-feedback">
                    {{$errors->first('cost')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectSpecialValidity">Special Validity</label>
                <select class="form-control" id="selectSpecialValidity" name="special_validity">
                    <option value=""  {{old('special_validity') == '' ? 'selected' : ''}}>None</option>
                    <option value="amount" {{old('special_validity') == '' ? 'amount' : ''}}>Limited amount</option>
                    <option value="date" {{old('special_validity') == '' ? 'date' : ''}}>Purchaseable till date</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputAmount">Maximum amount of tickets to be sold</label>
                <input type="number" class="form-control {{$errors->first('amount') ? 'is-invalid' : ''}}" id="inputAmount" name="amount" placeholder=""
                       value="{{old('amount') ? old('amount') : '0'}}">
                <div class="invalid-feedback">
                    {{$errors->first('amount')}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputValidTill">Tickets can be sold until</label>
                <input type="text"
                       class="form-control {{$errors->first('valid_until') ? 'is-invalid' : ''}}"
                       id="inputValidTill"
                       name="valid_until"
                       placeholder="yyyy-mm-dd HH:MM"
                       value="{{old('valid_until') ? old('valid_until') : ''}}">
                <div class="invalid-feedback">
                    {{$errors->first('valid_until')}}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Save ticket</button>
        <a href="{{route('event.show', $event->id)}}" class="btn btn-link">Cancel</a>
    </form>
    @endsection
