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
          {{ isset($agency) ? 'Sửa  ' : 'Thêm' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($agency) ? route('Staff::Management::agency@update', [$agency->id] ): route('Staff::Management::agency@store') }}">
                  {{ csrf_field() }}
                  @if (isset($agency))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- Name------------>
                  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Name</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$agency->name }}" />
                    @if ($errors->has('name'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>
                  <!------------------ Address--------------->
                    <div class="form-group {{ $errors->has('address') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Address</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="address" name="address" class="form-control" value="{{ old('address') ?: @$agency->address }}" />
                      @if ($errors->has('address'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('address') }}</div>
                      @endif
                    </div>


                  <!----- Upload Logo Here ---->
                    <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
                      <div class="display-block">
                        <label class="control-label text-semibold">Logo</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                      </div>
                      <div class="media no-margin-top">
                        <div class="media-left">
                          <img src="{{ (isset($agency) and $agency->logo) ? get_image_url($agency->logo, 'thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                        </div>
                        <div class="media-body">
                          <input type="file" name="logo" class="js-file">
                          <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                        </div>
                      </div>
                      @if ($errors->has('logo'))
                        <div class="help-block">{{ $errors->first('logo') }}</div>
                      @endif
                    </div>
                  <!-------------Status--------------->

                    <div class="form-group {{ $errors->has('status') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Status</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="status" name="status" class="form-control" value="1" />
                      @if ($errors->has('status'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('status') }}</div>
                      @endif
                    </div>

                    <!-------------Phone--------------->

                    <div class="form-group {{ $errors->has('phone') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Phone</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') ?: @$agency->phone }}" />
                      @if ($errors->has('phone'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('phone') }}</div>
                      @endif
                    </div>

                    <!-------------Site--------------->

                    <div class="form-group {{ $errors->has('site') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Site</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="site" name="site" class="form-control" value="{{ old('site') ?: @$agency->site }}" />
                      @if ($errors->has('site'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('site') }}</div>
                      @endif
                    </div>

                    <!-------------Email--------------->

                    <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Email</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="email" name="email" class="form-control" value="{{ old('email') ?: @$agency->email }}" />
                      @if ($errors->has('email'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('email') }}</div>
                      @endif
                    </div>

                    <!-------------Location--------------->

                    <div class="form-group {{ $errors->has('location') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Location</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="location" name="location" class="form-control" value="{{ old('location') ?: @$agency->location }}" />
                      @if ($errors->has('location'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('location') }}</div>
                      @endif
                    </div>
                  <!---------------Other----------------->
                    <div class="form-group {{ $errors->has('other') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Other</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="other" name="other" class="form-control" value="{{ old('other') ?: @$agency->other }}" />
                      @if ($errors->has('other'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('other') }}</div>
                      @endif
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($agency) ? 'Cập nhật' : 'Thêm mới' }}</button>
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