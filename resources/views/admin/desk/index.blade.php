@extends('admin.layouts.admin')

@section('style')
    {{-- Datatables --}}
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('datatables/responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        #table {
            table-layout: fixed;
            width: 99% !important;
        }

        #table tbody td {
            display: block;
            border: unset;
        }

        #table>tbody>tr>td {
            border-top: unset;
        }

        .card-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active,
        .btn-secondary.active,
        .open>.dropdown-toggle.btn-secondary {
            background-color: #366bbd;
        }

        .toolbar {
            float: left;
        }

    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('floor.index') }}">Floor List</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('floor.sector.index', $sector->floor->id) }}">{{ $sector->floor->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $sector->name }}</li>
        </ol>
    </nav>

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
    @endif

    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Floor Detail</strong></div>
                <div class="card-body">

                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless display responsive" cellspacing="0">
                                    <tr>
                                        <td style="max-width: 20%; width:20%">Floor ID</td>
                                        <td style="max-width: 1%; width:1%">:</td>
                                        <td>{{ $sector->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sector Name</td>
                                        <td>:</td>
                                        <td>{{ $sector->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td>:</td>
                                        <td>{{ $sector->description }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col text-center align-self-center">
                            @if ($sector->getFirstMediaUrl('sectors', 'thumb'))
                                <div id="carouselControls" class="carousel slide bg-secondary" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($sector->media as $item)
                                            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                                @php
                                                    $i++;
                                                @endphp
                                                @if ($item->hasGeneratedConversion('thumb'))
                                                    <img class="d-block" src="{{ $item->getUrl('thumb') }}" alt=""
                                                        style="height:300px; max-height: 300px; margin:auto">
                                                @else
                                                    <img class="d-block" src="{{ $item->getUrl() }}" alt=""
                                                        style="height:300px; max-height: 300px; margin:auto">
                                                @endif
                                            </div>
                                        @endforeach
                                        <a class="carousel-control-prev" href="#carouselControls" role="button"
                                            data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselControls" role="button"
                                            data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                @else
                                    No Image
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Desk List</strong></div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Room List</strong></div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> --}}

    </div>

    <!-- Modal -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this floor?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">No</button>
                    <form action="#" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="delete-btn"><strong>Yes, delete</strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- Datatable --}}
    <script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatables/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('datatables/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatables/sorting/natural.js') }}"></script>
    <script type="text/javascript">
        // console.log("<?php $message = Session::get('floor') ? $message : ''; ?>");
        $(document).ready(function() {
            // console_log("<?php Session::get('floor') ? Session::get('floor') : ''; ?>");
            $("#table thead").hide();
            let url_list = "{{ route('floor.sector.desk.list', [$sector->floor->id, $sector->id]) }}";
            let url_create = "{{ route('floor.sector.desk.create', [$sector->floor->id, $sector->id]) }}";
            var dt = $('#table').DataTable({
                // ajax: "http://loremjson.itmilenial.com/getdata/user/people",
                ajax: url_list,
                bInfo: false,
                // pageLength: 12,
                pageLength: 6,
                lengthChange: false,
                deferRender: true,
                processing: true,
                dom: 'l<"toolbar">frtip',
                initComplete: function() {
                    $("div.toolbar")
                        .html(
                            '<a href="' + url_create +
                            '" type="button" class="btn btn-sm btn-primary btn-lg btn-block"><i class="fas fa-plus"></i> Create New</a>'
                        );
                },
                language: {
                    paginate: {
                        previous: "<",
                        next: ">"
                    },
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            let floor_id = '{{ $sector->floor->id }}';
                            let sector_id = '{{ $sector->id }}';
                            let id = row.id;

                            let url_show =
                                "{{ route('floor.sector.desk.show', [':floor_id', ':sector_id', ':id']) }}";
                            url_show = url_show.replace(':floor_id', floor_id);
                            url_show = url_show.replace(':sector_id', sector_id);
                            url_show = url_show.replace(':id', row.id) + '/desk';

                            let url_edit =
                                "{{ route('floor.sector.desk.edit', [':floor_id', ':sector_id', ':id']) }}";
                            url_edit = url_edit.replace(':sector_id', sector_id);
                            url_edit = url_edit.replace(':floor_id', floor_id);
                            url_edit = url_edit.replace(':id', row.id);

                            // var sector = (row.number_of_sectors == 0 || row.number_of_sectors ==
                            //     1) ? ' sector' : ' sectors';
                            // var desk = (row.number_of_desks == 0 || row.number_of_desks ==
                            //     1) ? ' desk' : ' desks';

                            var html =
                                '<div class="card shadow">' +
                                // '  <img src="' + row.avatar + '" class="card-img-top">' +
                                // '   <div class="card-header">' + row.name + '</div>' +
                                '   <div class="card-body">' +
                                '       <div class="float-right">' +
                                '           <div class="dropdown">' +
                                '               <a class="btn btn-sm dropdown" type="button" id="dropdownMenuButton"' +
                                '                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                '                   <i class="fas fa-ellipsis-v"></i></a>' +
                                '               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                '                   <a class="dropdown-item" href="' + url_edit +
                                '                       "><i class="fas fa-pencil-alt"></i> Edit</a>' +
                                '                   <a class="dropdown-item" href="#" data-toggle="modal" data-target="' +
                                '                       #deleteModal" data-id="' + row.id +
                                '                       "><i class="fas fa-trash"></i> Delete</a>' +
                                '               </div>' +
                                '           </div>' +
                                '       </div>' +
                                '       <div class="card-text"><small><strong>' + row.name +
                                '           </strong></small></div>' +
                                '       <p class="card-text"><small>' + row.description +
                                // '           <br> <i class="fas fa-chart-pie"></i> ' + row
                                // .number_of_sectors + sector +
                                // '           <br> <i class="fas fa-chair"></i> ' +
                                // row.number_of_desks + desk +
                                '           </small></p>' +
                                // '       <a href="' + url_show +
                                // '           "type="button" class="btn btn-sm btn-secondary btn-lg btn-block"><i class="fas fa-eye"></i> Show</a>' +
                                '   </div>' +
                                '</div>'

                            return html;
                        }
                    },
                    {
                        data: "name",
                        visible: false
                    },
                ]
            });

            dt.on('draw', function(data) {
                $('#table tbody').addClass('row');
                $('#table tbody tr').addClass('col-lg-3 col-md-4 col-sm-12');
                // $('#table tbody tr').addClass('col-lg-6 col-md-8 col-sm-12');
            });
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var id = button.data('id'); // Extract info from data-* attributes
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            var form = document.getElementById("deleteForm");
            let url = "{{ route('floor.sector.desk.destroy', [':floor_id', ':sector_id', ':id']) }}";
            url = url.replace(':floor_id', '{{ $sector->floor->id }}');
            url = url.replace(':sector_id', '{{ $sector->id }}');
            url = url.replace(':id', id);
            form.action = url;
        })
    </script>
@endsection
