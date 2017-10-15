@extends('_layouts/error')

@section('page_title', 'Sự cố hệ thống')

@section('content')
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <div class="text-center content-group">
          <h1>Xin lỗi, có gì đó không đúng</h1>
          <h5>Chúng tôi đang làm việc để khắc phục sự cố này.</h5>
          <div style="margin: 150px 0;">
            <img src="{{ asset('assets/images/error-500.png') }}" width="200" />
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
