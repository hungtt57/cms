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
          {{ isset($top_hot_product) ? 'Sửa  ' : 'Thêm ' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($top_hot_product) ? route('Staff::Management::top_hot_product@update', [$top_hot_product->id] ): route('Staff::Management::top_hot_product@store') }}">
                  {{ csrf_field() }}
                  @if (isset($top_hot_product))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- Gtin------------>
                    <div class="form-group {{ $errors->has('gtin') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Gtin</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="gtin" name="gtin" class="form-control" value="{{ old('gtin') ?: @$top_hot_product->gtin }}" />
                      @if ($errors->has('gtin'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('gtin') }}</div>
                      @endif
                    </div>

                    <!---------- Order------------>
                    <div class="form-group {{ $errors->has('order') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Order</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="order" name="order" class="form-control" value="{{ old('order') ?: @$top_hot_product->order }}" />
                      @if ($errors->has('order'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('order') }}</div>
                      @endif
                    </div>




                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($top_hot_product) ? 'Cập nhật' : 'Thêm mới' }}</button>
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