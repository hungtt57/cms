@extends('_layouts/error')

@section('page_title', 'Bảo trì')

@section('content')
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <div class="text-center content-group">
          <h1>Xin lỗi, hệ thống đang bảo trì</h1>
          <h5>Hệ thống hiện đang trong quá trình bảo trì, bạn vui lòng quay lại sau.</h5>
          <div class="content-group">
            <img src="{{ asset('http://www.betcoin.ag/files/betcoin/images/list+main[1].png') }}" style="max-width: 100%; max-height: 500px;" />
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
