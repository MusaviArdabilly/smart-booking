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
    <div>
        <div class="row row-flex mb-4">
            <div class="col-xl-6 col-md-6 col-6">
                <div class="content content-settings bg-primary text-white shadow">
                    <strong>@lang('home.title_stockin')</strong>
                    <div class="text-white small">@lang('home.subtitle_stockin')</div>
                    {{-- <a href="{{ action('StockInController@index') }}" class="stretched-link"></a> --}}
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-6">
                <div class="content content-settings bg-success text-white shadow">
                    <strong>@lang('home.title_stockout')</strong>
                    <div class="text-white small">
                        {{-- <mark style='color: #4e73df'><b>{{ $stocks->count() }}</b></mark>@lang('home.subtitle_stockout') --}}
                    </div>
                    {{-- <a href="{{ action('StockOutController@index') }}" class="stretched-link"></a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header text-primary"><b>@lang('list.stockout')</b></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display responsive nowrap" id="table" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>@lang('table.date')</th>
                                    <th>@lang('table.photo')</th>
                                    <th>@lang('table.created_by')</th>
                                    <th>@lang('table.status')</th>
                                    <th>@lang('table.confirmation')</th>
                                    <th>@lang('table.description')</th>
                                    <th>@lang('table.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse ($stocks as $stock)
                                    <tr>
                                        <td width="4%">{{ $loop->iteration }}</td>
                                        <td>{{ $stock->id_transaction }}</td>
                                        <td>{{ $stock->date }}</td>
                                        <td>
                                            <div href="#" class="pop">
                                                <img width="30"
                                                    src="{{ asset('images/users') }}/{{ $stock->create_by->photo }}"
                                                    alt="{{ $stock->create_by->name }}"
                                                    class="img-thumbnail rounded-circle">
                                            </div>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($stock->create_by->name, 20, '...') }}</td>
                                        <td>{{ ucfirst($stock->status) }}</td>
                                        <td>{{ ucfirst($stock->confirmation) }}</td>
                                        <td>{{ isset($stock->description) ? \Illuminate\Support\Str::limit($stock->description, 30, '...') : '-' }}
                                        </td>
                                        <td width="13%">
                                            <a href="{{ action('HomeController@show', $stock->id) }}"
                                                class="btn btn-primary btn-circle btn-sm mb-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ action('HomeController@edit', $stock->id) }}"
                                                class="btn btn-warning btn-circle btn-sm mb-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-circle btn-sm mb-1" data-toggle="modal"
                                                data-target="#deleteModal" data-id="{{ $stock->id }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center">@lang('table.empty')</td>
                                    </tr>
                                @endforelse --}}
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
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('datatables/sorting/natural.js') }}"></script>
    <script>
        $(document).ready(function() {
            table = $('#table').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    },
                    {
                        type: 'natural',
                        targets: 1
                    },
                    {
                        orderable: false,
                        targets: 3
                    },
                ],
                order: [
                    [1, 'desc']
                ],
            });
            table.on('order.dt search.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        })

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
