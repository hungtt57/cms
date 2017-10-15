
  <!-- Second navbar -->
  <div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
      <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
      <ul class="nav navbar-nav navbar-nav-material">
        {{--<li><a href="{{ route('Staff::dashboard') }}"><i class="icon-display4 position-left"></i> Dashboard</a></li>--}}


        <li class="dropdown mega-menu mega-menu-wide">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-office position-left"></i>Doanh Nghiệp<span class="caret"></span></a>
          <div class="dropdown-menu dropdown-content">
            <div class="dropdown-content-body">
              <div class="row">
                <div class="col-md-3 col-lg-2">
                  <span class="menu-heading underlined">Doanh nghiệp</span>
                  <ul class="menu-list">
                    <li>
                      <a href="{{ route('Staff::Management::business@index') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Doanh nghiệp</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::businessPermission@permission@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-user position-left"></i>Quyền doanh nghiệp</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::businessPermission@role@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-user position-left"></i>Nhóm doanh nghiệp</a>
                    </li>
                  </ul>
                </div>
                <div class="col-md-3 col-lg-2">
                  <span class="menu-heading underlined">Sản xuất</span>
                  <ul class="menu-list">
                    <li>
                      <a href="{{ route('Staff::Management::gln@index') }}" class="dropdown-toggle"><i class="icon-barcode2 position-left"></i> Mã địa điểm toàn cầu (GLN)</a>
                    </li>
                    <li>
                      <a href="{{ route('Staff::Management::product@index') }}"><i class="icon-display4"></i> Sửa s/p sản xuất</a>
                    </li>

                  </ul>
                </div>

                <div class="col-md-3 col-lg-2">
                  <span class="menu-heading underlined">Phân phối</span>
                  <ul class="menu-list">
                    <li>
                      <a href="{{ route('Staff::Management::businessDistributor@index') }}"><i class="icon-display4"></i>Danh sách phân phối</a>
                    </li>
                    <li>
                      <a href="{{ route('Staff::Management::businessDistributor@listEditProductDistributor') }}"><i class="icon-display4"></i>Sửa s/p phân phối</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::businessDistributor@addProductDistributor') }}"><i class="icon-display4"></i>Thêm s/p phân phối</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::businessDistributor@listProductBusiness') }}"><i class="icon-display4"></i>Tìm kiếm phân phối</a>
                    </li>

                  </ul>
                </div>
              </div>
            </div>
          </div>
        </li>





        <!-- <li>
          <a href="{{ route('Staff::Management::survey@index') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Survey</a>
        </li> -->


        <li class="dropdown">
          <a href="{{ route('Staff::Management::collaborator@index') }}" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user position-left"></i>  Cộng tác viên <span class="caret"></span></a>

          <ul class="dropdown-menu">
            <li>
              <a href="{{ route('Staff::Management::collaborator@history') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-user position-left"></i>Lịch sử ctv</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::collaborator@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-user position-left"></i> Danh sách ctv</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::collaborator@listGroup') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-user position-left"></i> Danh sách nhóm</a>
            </li>


          </ul>
        </li>


        <li class="dropdown mega-menu mega-menu-wide">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-bag position-left"></i> Sản phẩm <span class="caret"></span></a>
          <div class="dropdown-menu dropdown-content">
            <div class="dropdown-content-body">
              <div class="row">
                <div class="col-md-3 col-lg-2">
                  <span class="menu-heading underlined">Hệ thống</span>
                  <ul class="menu-list">
                    <li>
                      <a href="{{ route('Staff::Management::product2@index') }}"><i class="icon-display4"></i> Sản phẩm trên hệ thống</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::category@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Category</a>
                    </li>
                    <li>
                      <a href="{{ route('Staff::Management::category@listAttr') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Danh sách thuộc tính</a>
                    </li>

                    <li>
                            <a href="{{ route('Staff::Management::message@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-envelope position-left"></i> Message</a>
                          </li>

                    <li>
                      <a href="{{ route('Staff::Management::product2@listByUser') }}"><i class="icon-warning"></i> Sản phẩm do người dùng đóng góp</a>
                    </li>
                    <li>
                      <a href="{{ route('Staff::Management::product2@listWarning') }}"><i class="icon-warning"></i> Sản phẩm đang bị cảnh báo</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::comment@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-close position-left"></i>  Bình luận</a>
                    </li>

                  </ul>
                </div>
                <div class="col-md-3 col-lg-2">
                  <span class="menu-heading underlined">Distributor</span>
                  <ul class="menu-list">
                    <li>
                      <a href="{{ route('Staff::Management::vendor@index') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Vendor</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::distributor@index') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Distributor</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::product2@d') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Tìm sản phẩm theo Distributor</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::product2@ad') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Gắn A-D vào GTIN</a>
                    </li>


                    <li>
                      <a href="{{ route('Staff::Management::product2@removeD') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Xoá Distributor khỏi GTIN</a>
                    </li>

                    <li>
                      <a href="{{ route('Staff::Management::product2@removeField') }}" class="dropdown-toggle"><i class="icon-office position-left"></i> Xóa Nhanh trường của sản phẩm</a>
                    </li>
                  </ul>
                </div>

                <div class="col-md-3 col-lg-2">
                    <span class="menu-heading underlined">Sản phẩm</span>
                    <ul class="menu-list">

                        <li>
                            <a href="{{ route('Staff::Management::contributeProduct@index') }}"><i class="glyphicon glyphicon-pencil"></i>Sản phẩm được đóng góp thống tin</a>
                        </li>
                          <li>
                            <a href="{{ route('Staff::Management::relateProduct@index') }}"><i class="glyphicon glyphicon-pencil"></i>Sản phẩm liên quan</a>
                          </li>

                          <li>
                            <a href="{{ route('Staff::Management::logScanNotFound@index') }}"><i class="glyphicon glyphicon-pencil"></i>Mã log không tìm thấy</a>
                          </li>

                      <li>
                        <a href="{{ route('Staff::viewJob') }}"><i class="glyphicon glyphicon-pencil"></i>Xem job hệ thống</a>
                      </li>




                    </ul>
                </div>
              </div>
            </div>
          </div>
        </li>


        <li class="dropdown">
          <a href="{{ route('Staff::Management::user@index') }}" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user position-left"></i> Thành viên <span class="caret"></span></a>

          <ul class="dropdown-menu">

            <li>
              <a href="{{ route('Staff::Management::user@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-user position-left"></i> Thành viên</a>
            </li>

            <li>
              <a href="{{ route('Staff::Management::role@index') }}" class="dropdown-toggle"><i class="icon-user position-left"></i> Nhóm quyền</a>
            </li>

            <li>
              <a href="{{ route('Staff::Management::permission@index') }}" class="dropdown-toggle"><i class="icon-user position-left"></i> Phân quyền</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::fake@index') }}" class="dropdown-toggle"><i class="icon-user position-left"></i> Fake Người dùng</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::fake@collaboratorApp') }}" class="dropdown-toggle"><i class="icon-user position-left"></i> Cộng tác viên trên App</a>
            </li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-envelope position-left"></i> Báo cáo <span class="caret"></span></a>

          <ul class="dropdown-menu">
            <li>
              <a href="{{ route('Staff::Management::report@index', ['type' => 0]) }}" class="dropdown-toggle"><i class="glyphicon glyphicon-envelope position-left"></i> Sản phẩm</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::report@index', ['type' => 1]) }}" class="dropdown-toggle"><i class="glyphicon glyphicon-envelope position-left"></i> Feed</a>
            </li>


          </ul>
        </li>




        <li class="dropdown mega-menu mega-menu-wide">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-stats-dots position-left"></i> Thống kê <span class="caret"></span></a>

          <div class="dropdown-menu dropdown-content">
            <div class="dropdown-content-body">
              <div class="row">
                <div class="col-md-3">
                  <span class="menu-heading underlined">Report</span>
                  <ul class="menu-list">
                    <li>
                    <a href="{{ route('Staff::reportDashboard@product') }}"><i class="icon-search2"></i> Product</a>
                    </li>
                    <li>
                      <a href="{{ route('Staff::reportDashboard@category') }}"><i class="icon-search2"></i> Category</a>
                    </li>
                    <li>
                      <a href="{{ route('Staff::reportDashboard@vendor') }}"><i class="icon-search2"></i> Vendor</a>
                    </li>

                  </ul>
                </div>
                <div class="col-md-3">
                  <span class="menu-heading underlined">Report</span>
                  <ul class="menu-list">
                    <li>
                      <a href="{{ route('Staff::Management::statisticalVendorBusiness@index') }}"><i class="icon-search2"></i> Thống kê doanh nghiệp</a>
                    </li>

                  </ul>
                </div>


                {{--<div class="col-md-3">--}}
                  {{--<span class="menu-heading underlined">Tool Thống Kê</span>--}}
                  {{--<ul class="menu-list">--}}
                    {{--<li>--}}
                      {{--<a href="{{route('Staff::analytics@post_comment')}}"><i class="icon-bubble6"></i>Thống kê số lượng bài viết và comment trên mạng xã hội</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                      {{--<a href="{{route('Staff::analytics@product_comment')}}"><i class="icon-bubble6"></i>Thống kê số lượng comment chất lượng,chia sẻ,đóng góp sản phẩm</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                      {{--<a href="{{route('Staff::analytics@ga')}}"><i class="icon-bubble6"></i>Thống kê google analytics</a>--}}
                    {{--</li>--}}
                  {{--</ul>--}}
                {{--</div>--}}
              </div>
            </div>
          </div>
        </li>


        {{--<li class="dropdown">--}}
          {{--<a href="{{ route('events.list') }}" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-gift positoin-left"></i> Events <span class="caret"></span></a>--}}

          {{--<ul class="dropdown-menu">--}}
            {{--<li>--}}
              {{--<a href="{{ route('events.list') }}" class="dropdown-toggle"><i class="icon-gift positoin-left"></i> Event</a>--}}
            {{--</li>--}}
            {{--<li>--}}
              {{--<a href="{{ route('missions.list') }}" class="dropdown-toggle"><i class="icon-gift positoin-left"></i> Mission</a>--}}
            {{--</li>--}}
          {{--</ul>--}}

        {{--</li>--}}

        <li>
          <a href="{{ route('Staff::managerUser@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i>Quản lý User</a>
        </li>

        <li class="dropdown">
          <a href="{{ route('Staff::Management::fake@index') }}" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-folder-open position-left"></i>Tool ma két tinh <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ route('Staff::Management::fake@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Người dùng fake</a></li>
            <li><a href="{{ route('Staff::Management::virtualUser@post.all.list') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Tất cả các bài viết</a></li>
            <li><a href="{{ route('Staff::analytics@post_comment') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Phân tích bài viết và bình luận</a></li>
            <li><a href="{{ route('Staff::analytics@product_comment') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Thống kê người dùng</a></li>
            <li><a href="{{ route('Staff::Management::statistical@listComment') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Danh sách bình luận nhận xét</a></li>
            <li>
              <a href="{{ route('Staff::Management::userPoint@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-envelope position-left"></i>Điểm người dùng theo ngày</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::userPoint@statisticalByUser') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-envelope position-left"></i> Điểm người dùng theo user</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::post@index') }}" ><i class="glyphicon glyphicon-folder-open position-left"></i> Bài viết mới</a>
            </li>
            <li>
              <a href="{{ route('Staff::Management::categoryPost@index') }}" ><i class="glyphicon glyphicon-folder-open position-left"></i>Danh sách danh mục bài viết</a>
            </li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="{{ route('Staff::Management::fake@index') }}" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-folder-open position-left"></i>Tool data <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ route('Staff::Craw::website@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i>Danh sách website craw</a></li>
            <li><a href="{{ route('Staff::Craw::website@websiteInCraw') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Danh sách website đang chay</a></li>
            <li><a href="{{ route('Staff::mapProduct::product@index') }}" class="dropdown-toggle"><i class="glyphicon glyphicon-folder-open position-left"></i> Nối sản phẩm</a></li>

          </ul>
        </li>

        @if (Auth::guard('staff')->user()->can('chat'))
          <li>
            <a href="{{ route('Staff::Management::chat@index') }}"><i class="glyphicon glyphicon-envelope position-left"></i> Chat</a>
          </li>
        @endif
      @if (Auth::guard('staff')->user()->can('view-log'))
        <li>
          <a href="{{ route('Staff::log') }}"><i class="glyphicon glyphicon-envelope position-left"></i> Nhật ký</a>
        </li>
          <li>
            <a href="{{ route('Staff::logSearchVendor') }}"><i class="glyphicon glyphicon-envelope position-left"></i>Log tìm kiếm vendor</a>
          </li>
        @endif



      </ul>
    </div>
  </div>
  <!-- /second navbar -->
