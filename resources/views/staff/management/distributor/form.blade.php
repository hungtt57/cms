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
          {{ isset($distributor) ? 'Sửa  ' : 'Thêm' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($distributor) ? route('Staff::Management::distributor@update', [$distributor->id] ): route('Staff::Management::distributor@store') }}">
                  {{ csrf_field() }}
                  @if (isset($distributor))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- Name------------>
                  <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Name</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$distributor->name }}" />
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
                      <input type="text" id="address" name="address" class="form-control" value="{{ old('address') ?: @$distributor->address }}" />
                      @if ($errors->has('address'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('address') }}</div>
                      @endif
                    </div>

                  <!-------------Country--------------->

                    <div class="form-group {{ $errors->has('country') ? 'has-error has-feedback' : '' }}">
                      <label for="country" class="control-label text-semibold">Quốc gia</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Quốc gia"></i>
                      <select id="country" name="country" class="js-select">
                        @foreach ($countries as $country)
                          <option value="{{ $country->id }}" {{ ((old('country') and old('country') == $country->id) or (isset($distributor) and $distributor->country == $country->id)) ? ' selected="selected"' : '' }}>{{ $country->name }}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('country'))
                        <div class="help-block">{{ $errors->first('country') }}</div>
                      @endif
                    </div>

                  <!-------------title--------------->

                  <div class="form-group {{ $errors->has('title_id') ? 'has-error has-feedback' : '' }}">
                    <label for="title" class="control-label text-semibold">Loại</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Loại"></i>
                    <select id="title" name="title_id" class="js-select">
                      @foreach ($titles as $title)
                        <option value="{{ $title->id }}" {{ ((old('title_id') and old('title_id') == $title->id) or (isset($distributor) and $distributor->title_id == $title->id)) ? ' selected="selected"' : '' }}>{{ $title->title }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('title_id'))
                      <div class="help-block">{{ $errors->first('title_id') }}</div>
                    @endif
                  </div>

                  <!-------------Contact--------------->

                    <div class="form-group {{ $errors->has('contact') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Contact</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') ?: @$distributor->contact }}" />
                      @if ($errors->has('contact'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('contact') }}</div>
                      @endif
                    </div>

                    <!-------------Site--------------->

                    <!-------------Site--------------->

                    <div class="form-group {{ $errors->has('site') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Site</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="site" name="site" class="form-control" value="{{ old('site') ?: @$distributor->site }}" />
                      @if ($errors->has('site'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('site') }}</div>
                      @endif
                    </div>


                    <!-------------Other--------------->

                    <div class="form-group {{ $errors->has('other') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Other</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="other" name="other" class="form-control" value="{{ old('other') ?: @$distributor->other }}" />
                      @if ($errors->has('other'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('other') }}</div>
                      @endif
                    </div>
                  <!---------------Status----------------->
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

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($post) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

    $(".js-help-icon").popover({
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });
  });
</script>
@endpush
