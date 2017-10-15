@if (auth()->guard('collaborator')->check())
  <!-- Second navbar -->
  <div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
      <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
      <ul class="nav navbar-nav navbar-nav-material">
        <li><a href="{{ route('Collaborator::dashboard') }}"><i class="icon-display4 position-left"></i> Dashboard</a></li>

        <li>
          <a href="{{ route('Collaborator::productReview@index') }}" class="dropdown-toggle"><i class="icon-star-half position-left"></i> Viết Đánh giá sản phẩm</a>
        </li>

      </ul>
    </div>
  </div>
  <!-- /second navbar -->
@endif
