@if (auth()->check())
  <!-- Second navbar -->
  <div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
      <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
      <ul class="nav navbar-nav navbar-nav-material">
        <li><a href="{{ route('Business::dashboard') }}"><i class="icon-display4 position-left"></i> Dashboard</a></li>

        <li>
          <a href="{{ route('Business::gln@index') }}" class="dropdown-toggle"><i class="icon-barcode2 position-left"></i> Mã địa điểm toàn cầu (GLN)</a>
        </li>

        <li>
          <a href="{{ route('Business::product@index') }}" class="dropdown-toggle"><i class="icon-bag position-left"></i> Sản phẩm</a>
        </li>

        <li>
          <a href="{{ route('Business::product@listProductDistributor') }}" class="dropdown-toggle"><i class="icon-bag position-left"></i>Phân Phối Sản Phẩm</a>
        </li>
        <li>
          <a href="{{ route('Business::hdsd') }}" class="dropdown-toggle"><i class="icon-bag position-left"></i>Hướng dẫn sử dụng</a>
        </li>



          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-stats-dots position-left"></i> Thống kê <span class="caret"></span></a>

            <ul class="dropdown-menu">

              <li>
                <a href="{{ route('Business::statistical@products') }}" class="dropdown-toggle"><i class="icon-display4 position-left"></i> Thống kê product</a>
              </li>
              <li>
                <a href="{{ route('Business::statistical@categories') }}" class="dropdown-toggle"><i class="icon-display4 position-left"></i> Thống kê category</a>
              </li>
              <li>
                <a href="{{ route('Business::statistical@ages') }}" class="dropdown-toggle"><i class="icon-display4 position-left"></i> Thống kê độ tuổi</a>
              </li>

                <li>
                    <a href="{{ route('Business::statistical@locations') }}" class="dropdown-toggle"><i class="icon-display4 position-left"></i> Thống kê location</a>
                </li>


            </ul>
          </li>

        {{--<li class="dropdown mega-menu mega-menu-wide">--}}
          {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-stats-dots position-left"></i> Thống kê <span class="caret"></span></a>--}}

          {{--<div class="dropdown-menu dropdown-content">--}}
            {{--<div class="dropdown-content-body">--}}
              {{--<div class="row">--}}
                {{--<div class="col-md-3">--}}
                  {{--<span class="menu-heading underlined">Hành động</span>--}}
                  {{--<ul class="menu-list">--}}
                    {{--<li>--}}
                      {{--<a href="{{ route('Business::analytics@action') }}"><i class="icon-search2"></i> Tổng hợp</a>--}}
                    {{--</li>--}}
                  {{--</ul>--}}
                {{--</div>--}}
                {{--<div class="col-md-3">--}}
                  {{--<span class="menu-heading underlined">Địa lý</span>--}}
                  {{--<ul class="menu-list">--}}
                    {{--<li>--}}
                      {{--<a href="{{ route('Business::analytics@geo') }}"><i class="icon-location4"></i> Khu vực</a>--}}
                    {{--</li>--}}
                  {{--</ul>--}}
                {{--</div>--}}
                {{--<div class="col-md-3">--}}
                  {{--<span class="menu-heading underlined">Con người</span>--}}
                  {{--<ul class="menu-list">--}}
                    {{--<li>--}}
                      {{--<a href="#"><i class="icon-man-woman"></i> Giới tính</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                      {{--<a href="#"><i class="icon-seven-segment-9"></i> Độ tuổi</a>--}}
                    {{--</li>--}}
                  {{--</ul>--}}
                {{--</div>--}}
              {{--</div>--}}
            {{--</div>--}}
          {{--</div>--}}
        {{--</li>--}}

      </ul>
    </div>
  </div>
  <!-- /second navbar -->
@endif
