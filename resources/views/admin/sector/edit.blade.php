@extends('admin.layouts.admin')

@section('style')
    <link href="{{ asset('fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('floor.index') }}">Floor List</a></li>
            <li class="breadcrumb-item"><a href="{{ route('floor.sector.index', $floor->id) }}">{{ $floor->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $sector->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col"></div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Edit Floor</h5>
                </div>
                <div class="card-body">
                    @if (count($errors) > 0)
                        <p class="card-description">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        </p>
                    @endif
                    <form action="{{ route('floor.sector.update', [$floor->id, $sector->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" autofocus
                                value="{{ $sector->name }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" cols="30"
                                rows="4">{{ $sector->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Map</label>
                            {{-- <img src="{{ $floor->getFirstMediaUrl('map', 'thumb') }}" / width="120px"> --}}
                            <input type="file" class="form-control" id="file" name="photo[]" multiple>
                        </div>
                        <div class="row mb-1">
                            <div class="col">
                                <div class="d-flex justify-content-end text-align-center flex-column flex-md-row">
                                    <a type="button" href="javascript:;" onclick="history.back()"
                                        class="btn btn-outline-secondary mb-2 mb-lg-0 mb-md-0 mr-0 mr-md-2 mr-lg-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><strong>Update</strong></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('fileinput/themes/fa/theme.min.js') }}"></script>
    <script type="text/javascript">
        var imageUrl = "{{ $floor->getFirstMediaUrl('maps', 'thumb') }}";
        if (imageUrl != "") {
            var initialPreview = [
                "{{ $floor->getFirstMediaUrl('maps', 'thumb') }}",
            ];
            var initialPreviewConfig = [{
                type: "image",
                caption: "{{ isset($floor->media[0]) ? $floor->media[0]->file_name : '' }}",
                size: "{{ isset($floor->media[0]) ? $floor->media[0]->size : '' }}",
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
