@extends('_layouts/staff')

@section('page_title', 'Thêm sản phẩm vào danh sách Yêu cầu đánh giá')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Thêm sản phẩm vào danh sách Yêu cầu đánh giá
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
        <div class="panel panel-flat">
          <div class="panel-body">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
              @if (session('error'))
                <div class="alert bg-danger alert-styled-left">
                  <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                  {{ session('error') }}
                </div>
              @endif

              <form method="POST" action="{{ route('Staff::Management::contributeProduct@store') }}">
              {{ csrf_field() }}

              <div class="form-group {{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Chọn loại muốn thêm</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                <select id="types" name="type" class="form-control js-example-basic-single">
                  @foreach ($types as $key => $type)
                    <option value="{{$key}}" >{{ $type }}</option>
                  @endforeach
                </select>
                @if ($errors->has('type'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('type') }}</div>
                @endif
              </div>

              <div  class="div-gtin hide form-group {{ $errors->has('gtin') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Thêm theo GTIN</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                <textarea id="contact-info" name="gtin" rows="5" cols="5" class="form-control" placeholder="">{{ old('gtin') }}</textarea>
                @if ($errors->has('gtin'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('gtin') }}</div>
                @endif
              </div>

                <div class="div-gln form-group hide {{ $errors->has('gln') ? 'has-error has-feedback' : '' }}">
                  <label for="contact-info" class="control-label text-semibold">Thêm theo GLN</label>
                  <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                  <textarea id="contact-info" name="gln" rows="5" cols="5" class="form-control" placeholder="">{{ old('gln') }}</textarea>
                  @if ($errors->has('gln'))
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block">{{ $errors->first('gln') }}</div>
                  @endif
                </div>


              <div class="form-group {{ $errors->has('quantity') ? 'has-error has-feedback' : '' }}">
                <label for="contact-info" class="control-label text-semibold">Nhập số lượng muốn thêm</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                <input type="number" name="quantity" class = "form-control" placeholder="Nhập số lượng">
                @if ($errors->has('quantity'))
                  <div class="form-control-feedback">
                    <i class="icon-notification2"></i>
                  </div>
                  <div class="help-block">{{ $errors->first('quantity') }}</div>
                @endif
              </div>

              <div class="form-group {{ $errors->has('group') ? 'has-error has-feedback' : '' }}">
                <label for="id" class="control-label text-semibold">Group</label>
                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                <select id="types" name="group" class="form-control js-example-basic-single">
                  @foreach ($groups as $key => $group)
                    <option value="{{ $group->group_id}}" >{{ $group->group_id }}</option>
                  @endforeach
                </select>
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary">Thêm mới</button>
              </div>
            </form>
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

<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(".js-example-basic-single").select2();
      $(document).on('submit', 'form', function () {
        $('button[type="submit"]').prop('disabled', true);
      });
    $('#types').change(function(){
      var type = $(this).val();
      if(type == 6){

        $('.div-gln').removeClass('hide');
        $('.div-gln').addClass('show');

        $('.div-gtin').removeClass('hide');
        $('.div-gtin').addClass('show');
      }else{
        $('.div-gtin').removeClass('show');
        $('.div-gtin').addClass('hide');

        $('.div-gln').removeClass('show');
        $('.div-gln').addClass('hide');
      }
    });
  });
  </script>
@endpush


