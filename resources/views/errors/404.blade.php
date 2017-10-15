@extends('_layouts/default')

@section('page_title', 'Trang không tồn tại')

@section('content')
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <div class="text-center content-group">
          <h1>Xin lỗi, trang này không tồn tại</h1>
          <h5>Trang tương ứng với liên kết bạ đang truy cập hiện không tồn tại.</h5>
          <div style="margin: 150px 0;">
            <img src="{{ asset('assets/images/error-404.png') }}" width="200" />
          </div>
          <ul class="list-inline">
            <li><a href="#" onclick="window.history.back();">Quay về trang trước</a></li>
            <li><a href="/">Quay về trang chủ</a></li>
            <li><a href="#">Đến Trung tâm trợ giúp</a></li>
          </ul>
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection
