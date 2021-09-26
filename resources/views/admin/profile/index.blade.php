@extends('admin.layouts.admin')

@section('style')
    <style>
        .pop {
            width: 280px;
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .pop img {
            height: 100%;
            min-width: 100%;
            top: 0;
            left: 0;
            position: absolute;
            vertical-align: top;
            object-fit: contain;
        }

    </style>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-dismissable alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span>{{ $message }}</span>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span>{{ $message }}</span>
        </div>
    @elseif(count($errors) > 0)
        <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </span>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <strong class="m-0 font-weight-bold text-primary">Profile Picture</strong>
                </div>
                <div class="card-body">
                    <div class="col text-center align-self-center">
                        @if ($user->getFirstMediaUrl('avatars', 'thumb'))
                            <div href="#" class="pop">
                                <img src="{{ $user->getFirstMediaUrl('avatars', 'thumb') }}" alt=""
                                    class="img-thumbnail rounded-circle">

                            </div>
                            {{-- <div href="#" class="pop">
                                <img width="30" src="{{ asset('images/users') }}/{{ $user->photo }}"
                                    alt="{{ $user->photo }}" class="img-thumbnail rounded-circle">
                            </div> --}}
                        @else
                            <div class="img-thumbnail">
                                No Image
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow mb-4">

                <div class="card-header">
                    <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-rate-tab" data-toggle="tab" href="#nav-rate" role="tab"
                            aria-controls="nav-rate" aria-selected="true">
                            <h6 class="m-0 font-weight-bold">Account Details</h6>
                        </a>
                        <a class="nav-item nav-link" id="nav-interval-tab" data-toggle="tab" href="#nav-interval" role="tab"
                            aria-controls="nav-rate" aria-selected="false">
                            <h6 class="m-0 font-weight-bold">Password Management</h6>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-rate" role="tabpanel" aria-labelledby="nav-rate-tab">
                            <div class="row">
                                <div class="col">
                                    <form action="{{ route('profile.update', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" type="text" class="form-control" name="name"
                                                value="{{ $user->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input id="username" type="text" class="form-control" name="username"
                                                value="{{ $user->username }}" required>
                                        </div>
                                        <div class="form-row form-group">
                                            <div class="col">
                                                <label for="email">Email</label>
                                                <input id="email" type="email" class="form-control" name="email"
                                                    value="{{ $user->email }}" required>
                                            </div>
                                            <div class="col">
                                                <label for="phone">Phone</label>
                                                <input id="phone" type="text" class="form-control" name="phone"
                                                    value="{{ $user->phone }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password-confirm">Role</label>
                                            <input id="role" type="text" class="form-control" name="role"
                                                placeholder="{{ ucfirst($user->role) }}" disabled readonly>
                                        </div>
                                        <div class="d-flex justify-content-end text-align-center flex-column flex-md-row">
                                            <button type="submit"
                                                class="btn btn-primary px-5"><strong>Save</strong></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-interval" role="tabpanel" aria-labelledby="nav-interval-tab">
                            <div class="row">
                                <div class="col">
                                    <form action="{{ route('profile.update', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="old_password">Old Password</label>
                                            <input id="old_password" type="password" class="form-control"
                                                name="old_password" required>
                                        </div>
                                        <div class="form-row form-group">
                                            <div class="col">
                                                <label for="password">New Password</label>
                                                <input id="password" type="password" class="form-control" name="password"
                                                    required>
                                            </div>
                                            <div class="col">
                                                <label for="password_confirmation">New Password Confirmation</label>
                                                <input id="password_confirmation" type="password" class="form-control"
                                                    name="password_confirmation" required>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end text-align-center flex-column flex-md-row">
                                            <button type="submit"
                                                class="btn btn-primary px-5"><strong>Save</strong></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{-- <img src="" class="imagepreview" style="width: 100%;"> --}}
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            $(function() {
                $(document).on("click", '.pop', function(event) {
                    // $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                    $('#imagemodal').modal('show');
                });


            });
        </script>
    @endsection
