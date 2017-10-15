@extends('_layouts/staff')

@section('page_title', 'Sửa sản phẩm')

@section('content')

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Sửa sản phẩm
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

            <form method="POST" enctype="multipart/form-data" action="{{ route('Staff::operation@update', [$product->id]) }}">
              {{ csrf_field() }}
              <div class="row">
              <div class="col-md-6">
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group {{ $errors->has('max_review') ? 'has-error has-feedback' : '' }}">
                  <label for="max-review" class="control-label text-semibold">Tên sản phẩm có mã vạch ({{@$product->gtin}})</label>
                  <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của sản phẩm"></i>
                  <input type="text" id="max-review" name="product_name" class="form-control" value="{{ @$product->product_name }}" />
                  @if ($errors->has('product_name'))
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block">{{ $errors->first('product_name') }}</div>
                  @endif
                </div>
                <div class="form-group">
                  <label for="max-review" class="control-label text-semibold">Giá sản phẩm</label>
                  <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Giá sản phẩm"></i>
                  <input type="text" id="max-review" name="product_price" class="form-control" value="{{ @$product->product_price }}" />
                  @if ($errors->has('price'))
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block">{{ $errors->first('product_price') }}</div>
                  @endif
                </div>
                <div class="form-group ">
                  <div class="display-block">
                    <label class="control-label text-semibold">Hình ảnh sản phẩm</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb" data-original-title="" title=""></i>
                  </div>
                  <div class="media no-margin-top">
                    <div class="media-left">
                      <img src="{{$img != '' ? $img : "http://bus.runtime.mobi/assets/images/image.png"}}" style="width: 64px; height: 64px;" alt="">
                    </div>
                    <div class="media-body">
                      <div class="uploader">
                          <input type="file" name="product_image" class="js-file">
                      </div>
                      <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                      @if ($errors->has('product_image'))
                          <div class="help-block">{{ $errors->first('product_image') }}</div>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="max-review" class="control-label text-semibold">Mô tả sản phẩm:</label>
                  <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mô tả sản phẩm"></i>
                  <textarea id="content" name="description" rows="10" class="form-control" placeholder="">{{ old('description') ?: @$product->description }}</textarea>
                  @if ($errors->has('description'))
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block">{{ $errors->first('description') }}</div>
                  @endif
                </div>
              </div>
              <div class="col-md-6 form-group required has-success">
                  <label for="max-review" class="control-label text-semibold">Danh mục sản phẩm:</label>
                  <?php if($categories):?>
                  <ul class="list-unstyled" style="height: 500px; overflow: auto;">
                    <?php foreach($categories as $category): ?>
                    <?php
                        $checked = in_array($category->id, $dataCatygories) ? ' checked="checked"' : '';
                    ?>
                    <li class="">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="productCategory[<?= $category->id; ?>]"<?= $checked; ?>> <?= $category->name; ?>
                            </label>
                        </div>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </ul>
              </div>
              </div>
              <div class="text-right row col-md-12" style="margin-top:20px">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
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
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(".js-help-icon").popover({
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

    // Initialize with options
    $(".js-groups-select").select2({
      templateResult: function (item) {
        if (!item.id) {
          return item.text;
        }

        var originalOption = item.element,
            item =  ($(item.element).data('level') ? '<img src="' + $(item.element).data('icon') + '" />' : '') + item.text;

        return item;
      },
      templateSelection: function (item) {
        return item.text;
      },
      escapeMarkup: function (m) {
        return m;
      },
      dropdownCssClass: 'border-primary',
      containerCssClass: 'border-primary text-primary-700'
    });

    // Initialize with options
    $(".js-select").select2();

    // Checkboxes, radios
    $(".js-radio").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
        fileButtonClass: "action btn btn-default"
    });

  $(document).on('submit', 'form', function () {
    $('button[type="submit"]').prop('disabled', true);
  });

  });
  </script>
@endpush

