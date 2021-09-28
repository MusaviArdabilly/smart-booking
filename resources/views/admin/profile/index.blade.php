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

    <link href="{{ asset('fileinput/css/fileinput.min.css') }}" rel="stylesheet">
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
                    <strong class="m-0 font-weight-bold text-primary">Avatar</strong>
                </div>
                <div class="card-body">
                    <div class="col text-center align-self-center">
                        @if ($user->getFirstMediaUrl('avatars', 'thumb'))
                            <div href="#" class="pop">
                                <img src="{{ $user->getFirstMediaUrl('avatars', 'thumb') }}" alt=""
                                    class="img-thumbnail rounded-circle">
                            </div>
                        @else
                            <div class="img-thumbnail">
                                No Image
                            </div>
                        @endif
                        <a href="#" class="btn btn-primary btn-block mb-1 mt-4" data-toggle="modal"
                            data-target="#imageModal">Change Avatar
                        </a>
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

        <!-- Modal -->
        <div id="imageModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Avatar Preview</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="file" class="form-control" id="file" name="file">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="delete-btn"><strong>Update</strong></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @section('script')
        <script src="{{ asset('fileinput/js/fileinput.min.js') }}"></script>
        <script src="{{ asset('fileinput/themes/fa/theme.min.js') }}"></script>
        <script>
            $('#imageModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var modal = $(this);
            })

            var imageUrl = "{{ $user->getFirstMediaUrl('avatars', 'thumb') }}";
            if (imageUrl != "") {
                var initialPreview = [
                    "{{ $user->getFirstMediaUrl('avatars', 'thumb') }}",
                ];
                var initialPreviewConfig = [{
                    type: "image",
                    caption: "{{ isset($user->media[0]) ? $user->media[0]->file_name : '' }}",
                    size: "{{ isset($user->media[0]) ? $user->media[0]->size : '' }}",
                    // url: "/site/file-delete",
                    key: 1
                }, ];
                $("#file").fileinput({
                    theme: 'fa',
                    showUpload: false,
                    showClose: false,
                    showCancel: false,
                    showRemove: false,
                    allowedFileExtensions: ['svg', 'jpg', 'png'],
                    // overwriteInitial: false,
                    autoReplace: true,
                    overwriteInitial: true,
                    showUploadedThumbs: false,
                    maxFileCount: 1,
                    initialPreview: initialPreview,
                    initialPreviewAsData: true,
                    initialPreviewConfig: initialPreviewConfig,
                    initialPreviewFileType: 'image',
                    initialPreviewShowDelete: false,
                    maxFileSize: 2000,
                    maxFilesNum: 10,
                    slugCallback: function(filename) {
                        return filename.replace('(', '_').replace(']', '_');
                    }
                });
            } else {
                $("#file").fileinput({
                    theme: 'fa',
                    showUpload: false,
                    showClose: false,
                    showCancel: false,
                    allowedFileExtensions: ['svg', 'jpg', 'png'],
                    overwriteInitial: false,
                    maxFileCount: 1,
                    // initialPreview: initialPreview,
                    initialPreviewAsData: true,
                    // initialPreviewConfig: initialPreviewConfig,
                    initialPreviewFileType: 'image',
                    initialPreviewShowDelete: false,
                    maxFileSize: 2000,
                    maxFilesNum: 10,
                    slugCallback: function(filename) {
                        return filename.replace('(', '_').replace(']', '_');
                    }
                });
            }
        </script>
    @endsection
