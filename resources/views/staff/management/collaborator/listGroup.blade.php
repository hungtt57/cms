@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Danh sách group CTV</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{ route('Staff::Management::collaborator@addGroup') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Group</a>

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

                            <th>Tên</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($groups as $group)
                            <tr role="row" id="">
                                <td>{{ $group->group_id }}</td>
                                <td><a href="{{route('Staff::Management::collaborator@editGroup',[$group->id])}}">  <button type="button" class="btn btn-primary">Sửa</button></a>
                                    <a href="{{route('Staff::Management::collaborator@deleteGroup',[$group->id])}}" onclick="xoa()">  <button type="button" class="btn btn-danger">Xóa</button></a>

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

@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    function xoa(){
        if(confirm("Bạn có chắc chắn muốn xóa GROUP này")){
            return true
        }
        return false;
    }
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



