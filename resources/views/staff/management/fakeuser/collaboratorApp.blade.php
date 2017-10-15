@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Cộng tác viên ({{$collaborators->count()}})</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{ route('Staff::Management::collaborator@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Cộng tác viên</a>--}}
                    <a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="panel panel-flat">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                            <th>Tên</th>
                            {{--<th>Trạng thái</th>--}}
                            {{--<th>Số dư</th>--}}
                            <th>Ngày tạo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($collaborators as $index => $collaborator)
                            <tr role="row" id="collaborator-{{ $collaborator->id }}">
                                <td><input type="checkbox" name="selected[{{ $collaborator->id }}]" class="js-checkbox" value="1" /></td>
                                <td>{{ $collaborator->name }}</td>
                                {{--<td>{{ $collaborator->statusText }}</td>--}}
                                {{--<td>--}}
                                {{--{{ number_format($collaborator->balance) }}--}}
                                {{--<a href="#" data-toggle="modal" data-target="#withdraw-money-modal" data-name="{{ $collaborator->name }}" data-withdraw-money-url="{{ route('Staff::Management::collaborator@withdrawMoney', [$collaborator->id]) }}"><i class="icon-trash"></i> Rút tiền</a>--}}
                                {{--</td>--}}
                                <td>{{ $collaborator->createdAt }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button id="collaborator-{{ $collaborator->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                                            <i class="icon-more2"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="collaborator-{{ $collaborator->id }}-actions">
                                            <li><a href="{{ route('Staff::Management::collaborator@edit', [$collaborator->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $collaborator->name }}" data-delete-url="{{ route('Staff::Management::collaborator@delete', [$collaborator->id]) }}"><i class="icon-trash"></i> Xoá</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-collaborator-name"></strong> khỏi hệ thống của iCheck?
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="withdraw-money-modal" tabindex="-1" role="dialog" aria-labelledby="withdraw-money-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="withdraw-money-modal-label">Rút tiền của Cộng tác viên</h4>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            Bạn có muốn rút sạch tiền của <strong class="text-danger js-collaborator-name"></strong>?
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: { "hide": 1000 }
    });

    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-collaborator-name').text($btn.data('name'));
    });

    $('#withdraw-money-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('withdraw-money-url'));
        $modal.find('.js-collaborator-name').text($btn.data('name'));
    });

    $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



