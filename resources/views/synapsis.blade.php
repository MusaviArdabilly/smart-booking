@extends('layouts.app')

@section('content')
    <div class="position-relative overflow-hidden pt-5 mt-5 profile-wrapper w-100">
        <div class="col-md-8 mx-auto my-5 text-center">
            {{-- <div class="logo mb-3">
                <img class="img-landing-page" src="{{ asset('/img/thunderbolt.png') }}" alt="">
            </div> --}}
            <h1 class="display-6 font-weight-normal text-light"><b>Synapsis Smart Booking</b></h1>
            @if (Route::has('login'))
                @auth
                    <p class="lead font-weight-normal text-light mx-2">
                        MVP of the Month
                    </p>
                @else
                    <span>
                        <p class="lead font-weight-normal text-light"><b><a class="text-light"
                                    href="{{ route('login') }}">Login<a></b> to manage your bookings!
                        </p>
                    </span>
                @endauth
            @endif
            {{-- <div style="color: honeydew; font-size:12px">Icons made by <a
                    href="https://www.flaticon.com/authors/pixel-perfect" title="Pixel perfect" style="color:white">Pixel
                    perfect</a> from <a href="https://www.flaticon.com/" title="Flaticon"
                    style="color:white">www.flaticon.com</a></div> --}}
            {{-- <div class="my-5">
                <a class="btn-white px-5" href="{{ url('/table') }}"><b>Display Table</b></a>
            </div> --}}
        </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
        <path fill="#366bbd" fill-opacity="1"
            d="M0,64L80,74.7C160,85,320,107,480,96C640,85,800,43,960,37.3C1120,32,1280,64,1360,80L1440,96L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z">
        </path>
    </svg>

    <div class="container mt-4">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col">
                    <canvas id="chart-activityMonth" height="40px !important" width="100% !important">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header"><strong>User with the Most Bookings</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="4%">#</th>
                                            <th>Name</th>
                                            <th>Month</th>
                                            <th>All</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($most->bookings as $booking)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $booking->name }}</td>
                                                <td>{{ $booking->booking_month_count }} times</td>
                                                <td>{{ $booking->booking_count }} times</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header"><strong>User with the Most Assessments</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="4%">#</th>
                                            <th>Name</th>
                                            <th>Month</th>
                                            <th>All</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($most->assessments as $assessment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $assessment->name }}</td>
                                                <td>{{ $assessment->assessment_month_count }} times</td>
                                                <td>{{ $assessment->assessment_count }} times</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <canvas id="chart-logMonth" height="40px !important" width="100% !important">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <canvas id="chart-hourMonth" height="40px !important" width="100% !important">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header"><strong>User with the Most Keluar/Masuk Gedung with Scanning
                                Assessment</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="4%">#</th>
                                            <th>Name</th>
                                            <th>Month</th>
                                            <th>All</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($most->logs as $log)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $log->name }}</td>
                                                <td>{{ $log->assessment_log_month_count }} times</td>
                                                <td>{{ $log->assessment_log_count }} times</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        //chart Activity per month
        var ctx_day = document.getElementById("chart-activityMonth");
        var chartActivityMonth = new Chart(ctx_day, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Booking per Day',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)'
                }, {
                    label: 'Assessment per Day',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)'
                }, ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Usage'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Day'
                        }
                    }],
                }
            }
        });

        var updateActivityMonth = function() {
            $.ajax({
                url: "{{ url('/activitymonth') }}",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log(data);
                    chartActivityMonth.data.labels = data.days;
                    // usage = data.usage.map(a => a.toFixed(4));
                    chartActivityMonth.data.datasets[0].data = data.bookings;
                    chartActivityMonth.data.datasets[1].data = data.assessments;
                    chartActivityMonth.update();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        //chart Logs in a month
        var ctx_day = document.getElementById("chart-logMonth");
        var chartLogMonth = new Chart(ctx_day, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Assessment Scan',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)'
                }, {
                    label: 'Booking Check In',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)'
                }, {
                    label: 'Booking Check Out',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(255, 255, 132, 0.2)',
                    borderColor: 'rgba(255, 255, 132, 1)'
                }, ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Usage'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Hour'
                        }
                    }],
                }
            }
        });

        //chart Logs in a month
        var ctx_day = document.getElementById("chart-hourMonth");
        var chartHourMonth = new Chart(ctx_day, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Booking Hour',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)'
                }, ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Usage'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Hour'
                        }
                    }],
                }
            }
        });

        var updateLogMonth = function() {
            $.ajax({
                url: "{{ url('/logmonth') }}",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log(data);
                    chartLogMonth.data.labels = data.hours;
                    // usage = data.usage.map(a => a.toFixed(4));
                    chartLogMonth.data.datasets[0].data = data.logs;
                    chartLogMonth.data.datasets[1].data = data.checkin;
                    chartLogMonth.data.datasets[2].data = data.checkout;
                    chartLogMonth.update();

                    chartHourMonth.data.labels = data.hours;
                    chartHourMonth.data.datasets[0].data = data.booking;
                    chartHourMonth.update();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        updateActivityMonth();
        updateLogMonth();
    </script>
@endsection
