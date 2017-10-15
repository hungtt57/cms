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
          {{ isset($message) ? 'Sửa  ' : 'Thêm ' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($message) ? route('Staff::Management::message@update', [$message->id] ): route('Staff::Management::message@store') }}">
                  {{ csrf_field() }}
                  @if (isset($message))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- Short_msg------------>
                    <div class="form-group {{ $errors->has('title') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Title</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="title" name="title" class="form-control" value="{{ old('short') ?: @$message->title }}" />
                      @if ($errors->has('title'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('title') }}</div>
                      @endif
                    </div>
                    <div class="form-group {{ $errors->has('short_msg') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Short message</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="title" name="short_msg" class="form-control" value="{{ old('short') ?: @$message->short_msg }}" />
                      @if ($errors->has('short_msg'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('short_msg') }}</div>
                      @endif
                    </div>
                  <!------------------ Full_msg--------------->
                    <div class="form-group {{ $errors->has('full_msg') ? 'has-error has-feedback' : '' }}">
                      <label for="contact-info" class="control-label text-semibold">Full message</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                      <textarea id="editor1" name="full_msg" rows="5" cols="5" class="form-control">{{ old('full_msg') ?: @$message->full_msg }}</textarea>
                      @if ($errors->has('full_msg'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('full_msg') }}</div>
                      @endif
                    </div>
                  {{--type--}}
                  <div class="form-group {{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
                    <label for="country" class="control-label text-semibold">Select Parent</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                    <select id="id_parent" name="type" class="js-select">
                      @php $types = ['0' => 'normal','1' => 'warning']; @endphp
                      @foreach ($types as $key => $type)
                        <option value="{{ $key}}" {{ ((old('type') and old('type') == $key) or (isset($message) and $message->type == $key)) ? ' selected="selected"' : '' }}>
                          {{$type}}</option>

                      @endforeach
                    </select>
                    @if ($errors->has('type'))
                      <div class="help-block">{{ $errors->first('type') }}</div>
                    @endif
                  </div>




                  <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($message) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  $(".js-select").select2();
  // Replace the <textarea id="editor1"> with a CKEditor
  // instance, using default configuration.
  CKEDITOR.replace( 'editor1' );
</script>
@endpush
