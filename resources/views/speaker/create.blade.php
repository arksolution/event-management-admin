@extends('layouts.layout')

@section('content')
    @include('layouts.header')

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
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Speaker</h1>
            </div>

            <div class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Create new speaker</h2>
                </div>
            </div>

            <script src="{{asset('/public/js/jquery.min.js')}}"></script>
            <script src="{{asset('/public/js/vue.min.js')}}"></script>

            <form class="needs-validation" enctype="multipart/form-data" novalidate action="{{route('speaker.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputName">Avatar</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="file" name="avatar" value="{{old('avatar')}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputSlug">Name</label>
                        <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : ''}}" id="inputSlug" name="name" placeholder=""
                               value="{{old('name') ?? ''}}">
                        <div class="invalid-feedback">
                            {{$errors->first('name')}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputSlug">Link</label>
                        <input type="text" class="form-control {{$errors->first('link') ? 'is-invalid' : ''}}" id="inputSlug" name="link" placeholder="https://..."
                               value="{{old('link') ?? ''}}">
                        <div class="invalid-feedback">
                            {{$errors->first('link')}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="textareaDescription">Information</label>
                        <textarea class="form-control {{$errors->first('info') ? 'is-invalid' : ''}}" id="inputSlug" name="info" placeholder="" rows="5">{{old('info') ?? ''}}</textarea>
                        <div class="invalid-feedback">
                            {{$errors->first('info')}}
                        </div>
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit">Save speaker</button>
                <a href="{{route('speaker.index')}}" class="btn btn-link">Cancel</a>
            </form>

        </main>
    </div>
@endsection
