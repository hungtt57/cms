@extends('_layouts/default')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Sản phẩm ({{$totalProduct}}/{{$quota}})</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Business::product@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Sản phẩm</a>
          <a href="#" class="btn btn-link" data-toggle="modal" data-target="#import-modal"><i class="icon-plus-circle"></i> Nhập từ file Excel</a>
          {{--<a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>--}}
          <a href="{{route('Business::downloadForm')}}" class="btn btn-link"><i class="icon-add"></i>Mẫu file excel</a>
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
        <div class="row" style="margin-bottom:20px">
          <form>
          <div class="col-md-3">

              <div class="has-feedback has-feedback-left">
                <input type="text" name="q" class="form-control" value="{{ Request::input('q') }}" placeholder="Tìm kiếm theo tên hoặc GTIN ...">
                <div class="form-control-feedback">
                  <i class="icon-search"></i>
                </div>
              </div>



          </div>
            <div class="col-md-3">
              <select name="status" class="form-control" id="status-filter">
                <option value="4" >Tất cả </option>
                @foreach(App\Models\Enterprise\Product::$statusTexts as $key => $statusText)

                  <option value="{{$key}}" @if((string) Request::input('status') == (string) $key) selected @endif>{{$statusText}}</option>
                  @endforeach
              </select>
            </div>

            <div class="col-md-3">
              <select name="gln" class="form-control" id="gln-filter">
                <option value="0" >Tất cả </option>
                @foreach($list_gln as $gln)
                <option value="{{$gln->id}}" @if((string) Request::input('gln') == (string) $gln->id) selected @endif >{{$gln->name}}({{$gln->gln}})</option>
                  @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>
          </form>
        </div>

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

        <div class="panel panel-flat">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
                  <th>Tên</th>
                  <th>Hình ảnh</th>
                  <th>Barcode</th>
                  <th>Nhà sản xuất</th>
                  <th>Trạng thái</th>
                  <th>Lý do</th>
                  <th>Ngày tạo</th>
                  <th>Bình luận</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($products as $index => $product)
                  <tr role="row" id="product-{{ $product->id }}">
                    <td><input type="checkbox" name="selected[{{ $product->id }}]" class="js-checkbox" value="1" /></td>
                    <td>{{ $product->name }}</td>
                    <td>

                        @if(!empty($product->image) && is_array(json_decode($product->image,true)))
                          @foreach(json_decode($product->image,true) as $image)
                      <img width="50" src="{{ get_image_url($image, 'thumb_small') }}" />
                        @endforeach
                       @endif
                    </td>
                    <td>
                      <?php
                      try {
                        echo DNS1D::getBarcodeSVG(trim($product->barcode), "EAN13");
                      } catch (\Exception $e) {
                        echo trim($product->barcode);
                      }
                      ?>
                      {{ $product->barcode }}
                    </td>
                    <td>{{ $product->gln->name }} ({{ $product->gln->gln }})</td>
                    <td>{{ $product->statusText }}</td>
                    <td>{{@$product->reason}}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>
                      @if (auth()->user()->can('view-comment'))
                        @if($product->is_quota == 1)
                        <a href="{{route('Business::product@comments',['gtin' => $product->barcode])}}"><button type="submit" class="btn btn-success btn-xs legitRipple" >Comment<span class="legitRipple-ripple" ></span></button></a>
                        @endif
                      @endif

                    </td>
                    <td>
                      <div class="dropdown">
                        <button id="product-{{ $product->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                          <i class="icon-more2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="product-{{ $product->id }}-actions">
                          <li><a href="{{ route('Business::product@edit', [$product->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                          @if (auth()->user()->can('view-relate-product'))
                            @if($product->is_quota == 1)
                            <li><a href="{{ route('Business::relateProductDN@listRelateProduct', ['gtin' => $product->barcode]) }}"><i class="icon-pencil5"></i> Sản phẩm liên quan</a></li>
                            @endif
                          @endif
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {!! $products->appends(Request::all())->links() !!}
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->

<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
      </div>
      <div class="modal-body">
        Quý Doanh nghiệp có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-product-name"></strong> khỏi hệ thống của iCheck?
      </div>
      <div class="modal-footer">
        <form method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="DELETE">
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-danger">Xác nhận</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('Business::product@import') }}" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="import-modal-label">Nhập nhiều sản phẩm từ file Excel</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="reason" class="control-label text-semibold">Tệp tin</label>
            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
            <input id="reason" type="file" name="file">
          </div>
        </div>
        <div class="modal-footer">
          {{ csrf_field() }}
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-primary">Nhập</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $('#delete-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
        $modal = $(this);

    $modal.find('form').attr('action', $btn.data('delete-url'));
    $modal.find('.js-product-name').text($btn.data('name'));
  });

  $(".js-checkbox").uniform({ radioClass: "choice" });
  </script>
@endpush



