@extends('admin.layouts.admin')

@section('style')
    {{-- Datatables --}}
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('datatables/responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        #table {
            table-layout: fixed;
            width: 99% !important;
            /* width: 97% !important; */
        }

        /* #table.dataTable.no-footer {
                                                                                                                                                                                                    border-bottom: unset;
                                                                                                                                                                                                } */

        /* #table tbody td {
                                                                                                                                                    display: block;
                                                                                                                                                    border: unset;
                                                                                                                                                }

                                                                                                                                                #table>tbody>tr>td {
                                                                                                                                                    border-top: unset;
                                                                                                                                                } */

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

        /* .toolbar {
                                                                                                                                                    float: left;
                                                                                                                                                } */

        /* .breadcrumb {
                                                                                                                                                                                                                                                                                padding: 2px 15px !important;
                                                                                                                                                                                                                                                                            } */

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
    @endif

    <div class="row">

        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Booking List</strong></div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Desk</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $booking->book_id }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->desk->sector->floor->name }} |
                                            {{ $booking->desk->sector->name }} | {{ $booking->desk->name }}</td>
                                        <td>{{ $booking->date }},<br>
                                            {{ $booking->start_time }}-{{ $booking->end_time }}
                                        </td>
                                        <td>{{ $booking->status }}</td>
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
@endsection

@section('script')
    {{-- Datatable --}}
    <script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatables/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('datatables/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatables/sorting/natural.js') }}"></script>
    <script type="text/javascript">
    </script>
@endsection
