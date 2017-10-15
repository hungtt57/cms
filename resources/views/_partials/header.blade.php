@section('header')
<!-- Main navbar -->
<div class="navbar navbar-inverse bg-indigo">
  <div class="navbar-header">
    <!--<a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>-->
    <a class="navbar-brand" href="{{ route('Business::dashboard') }}"><strong>iCheck</strong> for business</a>

    <ul class="nav navbar-nav pull-right visible-xs-block">
      <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
    </ul>
  </div>

  <div class="navbar-collapse collapse" id="navbar-mobile">
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <i class="icon-bell2"></i>
          <span class="visible-xs-inline-block position-right">Thông báo</span>
          <span class="badge bg-warning-400" id="notifications-unread-count"></span>
        </a>

        <div class="dropdown-menu dropdown-content">
          <div class="dropdown-content-heading">
            Thông báo
            <ul class="icons-list">
              <li><a href="#"><i class="icon-spinner11"></i></a></li>
            </ul>
          </div>

          <ul class="media-list media-list-linked media-list-bordered dropdown-content-body width-350 no-padding" id="notifications">
          </ul>

          <div class="dropdown-content-footer">
            <a href="{{ route('Business::notification@list') }}" data-popup="tooltip" title="Tất cả"><i class="icon-menu display-block"></i></a>
          </div>
        </div>
      </li>



    @if (auth()->check())
      <li class="dropdown dropdown-user">
        <a class="dropdown-toggle" data-toggle="dropdown">
          <img src="{{ asset('assets/images/image.png') }}" alt="">
          <span ng-bind="$root.user.name">{{ auth()->user()->name }}</span>
          <i class="caret"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-right">
          <li><a href="{{ route('Business::password_change_form') }}"><i class="icon-key"></i> Đổi mật khẩu</a></li>
          <li><a href="{{ url('logout') }}"><i class="icon-switch2"></i> Đăng xuất</a></li>
        </ul>
      </li>
      @else
      <li><a href="/login">Đăng nhập</a>
      <li><a href="/register">Đăng ký</a>
      @endif
    </ul>
  </div>
</div>
<!-- /main navbar -->

<script type="text/template" id="notifications-item-template">
  <li class="media">
    <a href="#" class="media-link js-notification-link">
      <!--<div class="media-left">
        <span class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></span>
      </div>-->

      <div class="media-body">
        <p class="js-notification-content"></p>
        <div class="media-annotation js-notification-time"></div>
      </div>
    </a>
  </li>
</script>
@show
