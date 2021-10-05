@extends('layouts.app')

@section('content')
    <div class="container py-4 mt-5">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card o-hidden shadow-lg my-5" style="border-radius: 1.35rem">
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <!-- Nested Row within Card Body -->
                            {{-- <div class="row"> --}}
                            {{-- <div class="col-lg-6 d-none d-lg-block">
                                    <img class="img-login-page" src="{{ asset('/img/6606.jpg') }}" alt="Halo">
                        <a class="img-login-page" href="http://www.freepik.com" style="color: grey; font-size:12px">Designed by pch.vector / Freepik</a>
                </div> --}}
                            {{-- <div class="col"> --}}
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"><strong>{{ __('Login') }} to your
                                            account</strong>
                                    </h1>
                                </div>
                                <div class="form-group">
                                    <input id="username" type="username"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username" autofocus
                                        placeholder="Enter Username..." style="border-radius:10rem; padding:1.5rem 1rem">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password" placeholder="Password"
                                        style="border-radius:10rem; padding:1.5rem 1rem">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-secondary" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-user btn-block btn-login"
                                            style="border-radius: 10rem; padding: .75rem 1rem;">
                                            {{ __('Login') }}
                                        </button>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="{{ route('password.request') }}">Forgot
                                                Password?</a>
                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="{{ route('register') }}">Create an
                                                Account!</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}
                            {{-- </div> --}}
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
