@extends('_layouts.staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Nhật ký tìm kiếm vendor</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
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
                <!-- Search Form -->
                <form role="form" style="margin-bottom:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                                <input class="form-control" type="text" name="search" placeholder="Search " value="{{ Request::input('search') }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="created_at_from" id="created-at-from" value="{{ Request::input('created_at_from') }}" class="form-control js-date-picker" placeholder="Từ ngày">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="created_at_to" id="created-at-to" value="{{ Request::input('created_at_to') }}" class="form-control js-date-picker" placeholder="Đến ngày">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-xs">Search</button>

                </form>
                <!-- End of Search Form -->
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
                            <th>Email</th>
                            <th>Key</th>
                            <th>Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($logs as $log)
                            <tr role="row">
                                <td>{{ $log->email }}</td>
                                <td>{{ $log->key }}</td>
                                <td>{{ $log->createdAt }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $logs->appends(Request::all())->links() !!}
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
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $('#created-at-to').pickadate({
        format: 'yyyy-mm-dd'
    });
    $('#created-at-from').pickadate({
        format: 'yyyy-mm-dd',
        onStart: function () {
            var fromPicker = $('#created-at-from').pickadate('picker');

            if (fromPicker.get('select')) {
                var toPicker = $('#created-at-to').pickadate('picker');

                toPicker.set('min', fromPicker.get('select').obj);
            }
        },
        onSet: function (context) {
            var toPicker = $('#created-at-to').pickadate('picker');

            if (toPicker.get('select') && toPicker.get('select').pick <= context.select) {
                toPicker.set('select', new Date(context.select));
            }

            toPicker.set('min', new Date(context.select));
        }
    });

    $(".js-help-icon").popover({
        html: true,
        trigger: "hover",
        delay: { "hide": 1000 }
    });

    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $('#approve-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('approve-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $('#disapprove-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('disapprove-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



