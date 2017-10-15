@extends('_layouts/collaborator')

@section('page_title', isset($review) ? 'Sửa Đánh giá sản phẩm' : 'Viết đánh giá sản phẩm')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          {{ isset($review) ? 'Sửa Đánh giá sản phẩm' : 'Viết đánh giá sản phẩm' }}
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
          <div class="col-md-6">
            <div class="panel panel-flat">
              <div class="panel-body">
                <div class="media">

                  @if (@$product->cached_info->image_default)
                  <div class="media-left">
                    <a href="#"><img src="{{ get_image_url($product->cached_info->image_default) }}" class="img-circle" alt=""></a>
                  </div>
                  @endif

                  <div class="media-body">
                    <h3 class="media-heading text-info">{{ @$product->cached_info->product_name }}</h3>
                    Giá bán tham khảo: <strong>{{ @$product->cached_info->price_default }}</strong><br />
                    Nhà sản xuất: <strong>{{ @$product->cached_info->vendor->name }}</strong>
                    @foreach (($product->cached_info->attributes ?: []) as $attr)
                    <p><strong>{{ $attrs[$attr->attribute]->title }}</strong><br />{!! $attr->content !!}</p>
                    @endforeach
                  </div>
                </div>
                @if (!isset($review))
                <a href="{{ route('Collaborator::productReview@next') }}" class="btn btn-danger">Bỏ qua sản phẩm này</a>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-6">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="{{ isset($review) ? route('Collaborator::productReview@update', [$review->id]) : route('Collaborator::productReview@submitReview') }}">
                  {{ csrf_field() }}
                  @if (isset($review))
                  <input type="hidden" name="_method" value="PUT">
                  @endif
                  <input type="hidden" name="gtin" value="{{ $product->gtin }}" />
                  <div class="form-group {{ $errors->has('content') ? 'has-error has-feedback' : '' }}">
                    <label for="content" class="control-label text-semibold">Nội dung đánh giá</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                    <textarea id="content" name="content" rows="20" class="form-control" placeholder="">{{ old('content') ?: @$review->content }}</textarea>
                    @if ($errors->has('content'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('content') }}</div>
                    @endif
                  </div>

                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($review) ? 'Cập nhật' : 'Gửi đánh giá' }}</button>
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

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(document).on('submit', 'form', function () {
      $('button[type="submit"]').prop('disabled', true);
    });

  });
  </script>
@endpush

