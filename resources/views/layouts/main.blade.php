<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Event Backend</title>

    <base href="./">
    <!-- Bootstrap core CSS -->
    <link href="{{asset('public/css/assets/css/bootstrap.css')}}" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{asset('public/css/assets/css/custom.css')}}" rel="stylesheet">

    <script src="{{asset('public/js/Chart.min.js')}}"></script>
</head>

<body>

<div class="container-fluid">
    @include('layouts.header')


    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="{{route('event.index')}}">Manage Events</a></li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>{{$event->name}}</span>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="{{route('event.show', $event->id)}}">Overview</a></li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Reports</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item"><a class="nav-link" href="{{route('report', $event->id)}}">Room capacity</a></li>
                    </ul>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="{{route('speaker.index')}}">Speaker</a></li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="border-bottom mb-3 pt-3 pb-2">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <h1 class="h2">{{$event->name}}</h1>
                    </div>
                    <span class="h6">{{$event->date}}</span>
                </div>
                @yield('content')
            </main>

        </div>
    </div>
</div>
</body>
</html>
