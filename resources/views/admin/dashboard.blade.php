@extends('admin.layouts.admin')

@section('style')
    <style>
        .pop {
            width: 30px;
            height: 30px;
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

    <h5 class="font-weight-bold" style="color: #5a5c69">Welcome back,
        {{ isset(Auth::user()->name) ? Auth::user()->name : 'User' }}!</h5>
    <h6 class="mb-4" style="color: #5a5c69">Here's what's happening with your building.</h6>

    <div class="row">
        <!-- Power consumption -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Floors</div>
                            <div class="power-consumption-all h5 mb-0 font-weight-bold text-gray-800">{{ $count->floor }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="fas fa-building fa-fw fa-2x text-gray-300"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Devices Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sectors</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="device-status h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $count->sector }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-fw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Desks -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Desk</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="paid-invoices h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $count->desk }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chair fa-fw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Users</div>
                            <div class="rate h5 mb-0 font-weight-bold text-gray-800">{{ $count->user }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-fw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Popular Desk</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>Name</th>
                                    <th>Booked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($most->desks as $desk)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $desk->name }}</td>
                                        <td>{{ $desk->booking_count }} times</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Most Active User</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>ID</th>
                                    <th>Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($most->users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->booking_count }} times</td>
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

    <div class="row">
        <!-- Power consumption -->
        <div class="col mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bookings Today / All
                            </div>
                            <div class="power-consumption-all h5 mb-0 font-weight-bold text-gray-800">
                                {{ $count->booking_today }} / {{ $count->booking_all }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="fas fa-building fa-fw fa-2x text-gray-300"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Devices Status -->
        <div class="col mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Assessments Today / All
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="device-status h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $count->assess_today }} / {{ $count->assess_all }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-fw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Recent created Booking</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $booking->book_id_1 }}<br>
                                            {{ $booking->book_id_2 }}
                                        </td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->date }},<br>
                                            {{ $booking->time }}
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-circle btn-sm mb-1" data-toggle="modal"
                                                data-target="#detailModal" data-id="{{ $booking->id }}"
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
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header"><strong>Recent created Assessment</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assessments as $assessment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $assessment->assess_id_1 }}<br>
                                            {{ $assessment->assess_id_2 }}
                                        </td>
                                        <td>{{ $assessment->user->name }}</td>
                                        <td>{{ $assessment->expires_at }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-circle btn-sm mb-1" data-toggle="modal"
                                                data-target="#detailModal" data-id="{{ $assessment->id }}"><i
                                                    class="fas fa-eye"></i>
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
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('stockout.title_delete')</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>@lang('stockout.subtitle_delete')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-dismiss="modal">@lang('general.no')</button>
                    <form action="#" method="POST" id="deleteForm">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"
                            id="delete-btn"><strong>@lang('modal.yes_delete')</strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for zooming image -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            var form = document.getElementById("deleteForm");
            var url = '{{-- action('HomeController@destroy', ':id') --}}';
            url = url.replace(':id', id);
            form.action = url;
        })

        $(function() {
            $(document).on("click", '.pop', function(event) {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');
            });
        });
    </script>
@endsection
