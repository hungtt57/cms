@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    {{ isset($group) ? 'Sửa  ' : 'Thêm ' }}
                </h2>
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
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        @if (session('success'))
                            <div class="alert bg-success alert-styled-left">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="POST" enctype="multipart/form-data" action="{{ isset($group) ? route('Staff::Management::collaborator@updateGroup', [$group->id] ): route('Staff::Management::collaborator@storeGroup') }}">
                                    {{ csrf_field() }}
                                    @if (isset($group))
                                        <input type="hidden" name="_method" value="PUT">
                                @endif
                                <!---------- ID------------>
                                    <div class="form-group {{ $errors->has('group_id') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">ID</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                                        <input type="text" id="id" name="group_id" class="form-control" value="{{ old('group_id') ?: @$group->group_id }}" />
                                        @if ($errors->has('group_id'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('group_id') }}</div>
                                        @endif
                                    </div>


                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">{{ isset($group) ? 'Cập nhật' : 'Thêm mới' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
@endsection

@push('js_files_foot')
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush

@push('scripts_foot')
<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( 'editor1' );
</script>
@endpush