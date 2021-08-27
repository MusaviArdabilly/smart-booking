@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="row" id="proBanner">
                                <div class="col-12">
                                    <span class="d-flex align-items-center purchase-popup alert alert-success">
                                        <p>{{ $message }}</p>
                                        <i class="mdi mdi-close ml-auto" id="bannerClose"></i>
                                    </span>
                                </div>
                            </div>
                        @elseif ($message = Session::get('error'))
                            <div class="row" id="proBanner">
                                <div class="col-12">
                                    <span class="d-flex align-items-center purchase-popup alert alert-danger">
                                        <p>{{ $message }}</p>
                                        <i class="mdi mdi-close ml-auto" id="bannerClose"></i>
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
