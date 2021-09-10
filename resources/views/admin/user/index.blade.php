@extends('admin.layouts.admin')

@section('style')
    {{-- Datatables --}}
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('datatables/responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        #table {
            table-layout: fixed;
            /* width: 99% !important; */
            /* width: 97% !important; */
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
                <div class="card-header"><strong>User List</strong></div>
                <div class="card-body">

                    <a href="{{ route('user.create') }}" type="button" class="btn btn-sm btn-primary"
                        style="margin-bottom: 15px;"><i class="fas fa-plus"></i> Create New</a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive display nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="4%">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th width="9%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <a href="{{ route('user.edit', $user->id) }}"
                                                class="btn btn-warning btn-circle btn-sm mb-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-circle btn-sm mb-1" data-toggle="modal"
                                                data-target="#deleteModal" data-id="{{ $user->id }}">
                                                <i class="fas fa-trash"></i>
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
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this user?</p>
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
        $(document).ready(function() {
            table = $('#table').DataTable({
                columnDefs: [{
                    type: 'natural',
                    targets: 1
                }, {
                    orderable: false,
                    targets: 5
                }, ],
                // order: [
                //     [1, 'desc']
                // ],
            });
        })

        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            var form = document.getElementById("deleteForm");
            let url = "{{ route('user.destroy', ':id') }}";
            url = url.replace(':id', id);
            form.action = url;
        })
    </script>
@endsection
