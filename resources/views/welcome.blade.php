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
                    <p class="lead font-weight-normal text-light mx-2">You are already logged in, go to <b><a
                                class="text-light" href="{{ route('admin.dashboard') }}">dashboard</a></b> to manage your
                        bookings!
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
            <div class="row">
                <!-- Power consumption -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Floors
                                    </div>
                                    <div class="power-consumption-all h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $count->floor }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="fas fa-building fa-fw fa-2x text-gray-300"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Devices Status -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sectors
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="device-status h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ $count->sector }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-pie fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Desks -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Desk</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="paid-invoices h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ $count->desk }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chair fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Users -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Users
                                    </div>
                                    <div class="rate h5 mb-0 font-weight-bold text-gray-800">{{ $count->user }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header"><strong>Popular Desk</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="4%">#</th>
                                            <th>Name</th>
                                            <th>Booked</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($most->desks as $desk)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $desk->name }}</td>
                                                <td>{{ $desk->booking_count }} times</td>
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
                        <div class="card-header"><strong>Most Active User</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="4%">#</th>
                                            <th>ID</th>
                                            <th>Booking</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($most->users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->booking_count }} times</td>
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
