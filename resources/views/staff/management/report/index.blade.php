@extends('_layouts/staff')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Báo cáo từ người dùng</h2>
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
      @if (session('success'))
        <div class="alert bg-success alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          {{ session('success') }}
        </div>
      @elseif (session('danger'))
        <div class="alert bg-danger alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          {{ session('danger') }}
        </div>
      @endif
      <div class="panel panel-flat">
        <div class="table-responsive">
          @if ($type == 'product')
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Loại</th>
                <th>Sản phẩm</th>
                <th>Báo cáo bởi</th>
                <th>Kiểu</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Ngày báo cáo</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($reports as $report)
              <tr>
                <td>{{ $type }}</td>
                <td><a href="{{ route('Staff::Management::product2@editByField', ['gtin' => $report->gtin_code]) }}" target="_blank">{{ $report->gtin_code }}</a></td>
                <td>{{ $report->icheck_id }}</td>
                <td>{{ $report->reportType->name }}</td>
                <td>{{ $report->note }}</td>
                <td>Chờ xử lý</td>
                <td>{{ $report->createdAt }}</td>
                <td><a href="{{ route('Staff::Management::report@resolve', ['id'=>$report->id,'type' => 0]) }}" class="btn bg-green"><i class="icon-checkmark-circle2"></i> Xác nhận đã giải quyết</a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          @else
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Loại</th>
                <th>Feed</th>
                <th>Báo cáo bởi</th>
                <th>Kiểu</th>
                <th>Nội dung báo cáo</th>
                <th>Nội dung bài viết</th>
                <th>Hình ảnh bài viết</th>
                <th>Trạng thái</th>
                <th>Ngày báo cáo</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($reports as $report)
              <tr>
                <td>{{ $type }}</td>
                <td>{{ $report->object_id }}</td>
                <td>{{ $report->icheck_id }}</td>
                <td>{{ $report->reportType->name }}</td>
                <td>{{ $report->note }}</td>
                <td>{{ @$report->post->content }}</td>
                <td>
                  @if (is_array($report->post->attachments))
                    @foreach ($report->post->attachments as $image)
                    <img src="{{ get_image_url($image) }}" />
                    @endforeach
                  @endif
                </td>
                <td>Chờ xử lý</td>
                <td>{{ $report->createdAt }}</td>
                <td>
                  <a href="{{ route('Staff::Management::report@deleteFeed', [$report->object_id]) }}" class="btn btn-danger"><i class="icon-checkmark-circle2"></i> Xoá bài</a>
                  <a href="{{ route('Staff::Management::report@resolve', ['id'=>$report->id,'type' => 1]) }}" class="btn bg-green"><i class="icon-checkmark-circle2"></i> Xác nhận đã giải quyết</a>
              </tr>
            @endforeach
            </tbody>
          </table>
                
          @endif
          {{ $reports->links() }}
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
  function xoaCat(){
    var conf = confirm("Bạn chắc chắn muốn xoá?");
    return conf;
  }
  function approveCat(){
    var conf = confirm("Bạn chắc chắn muốn post tin nay?");
    return conf;
  }

</script>
@endpush

@push('scripts_ck')
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush
