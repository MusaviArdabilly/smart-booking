@extends('admin.layouts.admin')

@section('style')
    {{-- Datatables --}}
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('datatables/responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        #table {
            table-layout: fixed;
            width: 100% !important;
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
                        <table class="table table-bordered table-responsive display" id="table" cellspacing="0">
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
                                        <td>{{ $booking->desk }}</td>
                                        <td>{{ $booking->date }},<br>
                                            {{ $booking->time }}
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
                                            <a href="#" class="btn btn-info btn-circle btn-sm mb-1" data-toggle="modal"
                                                data-target="#bookModal" data-id="{{ $booking->id }}"
                                                data-book_id="{{ $booking->book_id }}"
                                                data-user="{{ $booking->user->name }}"
                                                data-desk="{{ $booking->desk }}" data-date="{{ $booking->date }}"
                                                data-time="{{ $booking->time }}">
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

    <!-- Modal -->
    <div id="bookModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Detail</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{-- <div id="pdf-viewer"></div> --}}
                    <table class="table table-bordereless display nowrap" id="tableDetail" width="100%" cellspacing="0">
                        <tr>
                            <td width="10%">ID</td>
                            <td width="5%">:</td>
                            <td id="id"></td>
                        </tr>
                        <tr>
                            <td>User</td>
                            <td>:</td>
                            <td id="user"></td>
                        </tr>
                        <tr>
                            <td>Floor/Sector/Desk</td>
                            <td>:</td>
                            <td id="desk"></td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td id="date"></td>
                        </tr>
                        <tr>
                            <td>Time</td>
                            <td>:</td>
                            <td id="time"></td>
                        </tr>
                    </table>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">No</button>
                    <form action="#" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="delete-btn"><strong>Yes, delete</strong></button>
                    </form>
                </div> --}}
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

        $('#bookModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes

            $("#id").html(button.data('book_id'));
            $("#user").html(button.data('user'));
            $("#desk").html(button.data('desk'));
            $("#date").html(button.data('date'));
            $("#time").html(button.data('time'));

            // var url = button.data('url') + '#toolbar=1';
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            // var form = document.getElementById("deleteForm");
            // let url = "{{ route('user.destroy', ':id') }}";
            // url = url.replace(':id', id);
            // form.action = url;
        })
    </script>
@endsection
