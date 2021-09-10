@extends('admin.layouts.admin')

@section('style')
    {{-- Datatables --}}
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('datatables/responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        #table {
            table-layout: fixed;
            /* width: 98% !important; */
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
                        <table class="table table-bordered table-responsive display nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Desk</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                                        <td>
                                            <select class="form-control" name="status" id="status"
                                                data-id="{{ $booking->id }}"
                                                {{ $booking->status == 'checked-in' ? '' : 'disabled' }}
                                                data-old_status="{{ $booking->status }}">
                                                @if ($booking->status == 'booked')
                                                    <option value="booked" selected>Booked</option>
                                                @elseif ($booking->status == 'cancelled')
                                                    <option value="cancelled" selected>Cancelled</option>
                                                @elseif ($booking->status == 'checked-out')
                                                    <option value="checked-out" selected>Check Out</option>
                                                @else
                                                    <option value="checked-in" selected>Check In</option>
                                                    <option value="checked-out">Check Out</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <a href="{{ route('booking.show', $booking->id) }}"
                                                class="btn btn-info btn-circle btn-sm mb-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
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
    <script src="{{ asset('js/toastr.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            table = $('#table').DataTable({
                columnDefs: [
                    // {
                    //     type: 'natural',
                    //     targets: 1
                    // },
                    {
                        orderable: false,
                        targets: 6
                    },
                ],
                // order: [
                //     [1, 'desc']
                // ],
            });
        })

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#table').on('change', '.form-control', function() {
                let id = $(this).data('id');
                let url = "{{ route('booking.checkout', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: url,
                    data: {},
                    success: function(data) {
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.success(data.message);
                        location.reload(true);
                    },
                    error: function(request, errorType, errorMsg) {
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.error("there was an issue with ajax call: " + errorMsg);
                    },
                });
            });
        });
    </script>
@endsection
