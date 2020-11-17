@extends('layouts.main', compact('event'))

@section('content')
    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Room Capacity</h2>
        </div>
    </div>

    <!-- TODO create chart here -->

    <canvas id="myChart"></canvas>
    <script>

        var data = @JSON($session);
        var title = [];
        var attendee = [];
        var capacity = [];
        var color = [];

        data.forEach(x => {
            attendee.push(x.attendee);
            title.push(x.title);
            capacity.push(x.capcaity);
            if(parseInt(x.capcaity) < parseInt(x.attendee))
                color.push('red');
            else
                color.push('green')
        });

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: title,
                datasets: [{
                    label: '#Cacacity',
                    data: capacity,
                    backgroundColor: 'blue',
                    borderWidth: 1
                },{
                    label: '#Attendee',
                    data: attendee,
                    backgroundColor: color,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>



    @endsection
