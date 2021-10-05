@extends('layouts.app')

@section('content')
    <div class="container py-4 mt-5">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card o-hidden shadow-lg my-5" style="border-radius: 1.35rem">
                    {{-- <div class="card-header">{{ __('Reset Password') }}</div> --}}
                    <div class="card-body p-0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"><strong>Reset Password</strong></h1>
                                </div>
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                        autofocus placeholder="Enter Email"
                                        style="border-radius:10rem; padding:1.5rem 1rem">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        value="" required autocomplete="new-password" autofocus
                                        placeholder="Enter New Password" style="border-radius:10rem; padding:1.5rem 1rem">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input id="password-confirmation" type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password_confirmation" value="" required autocomplete="new-password" autofocus
                                        placeholder="Enter New Password Confirmation"
                                        style="border-radius:10rem; padding:1.5rem 1rem">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-user btn-block btn-login"
                                            style="border-radius: 10rem; padding: .75rem 1rem;">
                                            Reset Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
