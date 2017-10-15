@extends('_layouts/default')

@section('page_title', 'Đăng ký thành công')

@section('content')
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <div class="text-center content-group">
          <h1>Chúc mừng, Quý doanh nghiệp đã đăng ký thành công.</h1>
          <h5>Chúng tôi đã nhận được yêu cầu đăng ký của Quý doanh nghiệp. Chúng tôi sẽ liên hệ lại với Quý doanh nghiệp để xác minh thông tin và tạo tài khoản đăng nhập hệ thống cho Quý doanh nghiệp.</h5>
          <div style="margin: 150px 0;">
            <img src="{{ asset('assets/images/successfully-registered.png') }}" />
          </div>
          <ul class="list-inline">
            <li><a href="/">Tìm hiểu thêm về iCheck cho doanh nghiệp</a></li>
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
