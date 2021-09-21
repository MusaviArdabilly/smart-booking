@extends('admin.layouts.admin')

@section('style')
    {{-- Datatables --}}
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('datatables/responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('fileinput/css/fileinput.min.css') }}" rel="stylesheet">

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
                                    <th>Point</th>
                                    <th>Created At</th>
                                    <th>Expires In</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assessments as $assessment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $assessment->assess_id }}</td>
                                        <td>{{ $assessment->user->name }}</td>
                                        <td>{{ $assessment->point }}</td>
                                        <td>{{ $assessment->created_at }}</td>
                                        <td>{{ $assessment->expires_at }}</td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-circle btn-sm mb-1" data-toggle="modal"
                                                data-target="#deleteModal" data-id="{{ $assessment->id }}"
                                                data-url={{ $assessment->getFirstMediaUrl('assessments', 'thumb') }}>
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
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this user?</p>
                    <div id="example1"></div>
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
    <script src="{{ asset('fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('fileinput/themes/fa/theme.min.js') }}"></script>
    <script src="{{ asset('js/pdfobject.js') }}"></script>


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
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var url = button.data('url') + '#toolbar=0';
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            var form = document.getElementById("deleteForm");
            PDFObject.embed(url, "#example1");
            // let url = "{{ route('user.destroy', ':id') }}";
            // url = url.replace(':id', id);
            // form.action = url;
        })
    </script>
@endsection
