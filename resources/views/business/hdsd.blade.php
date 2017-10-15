@extends('_layouts/default')

@section('content')
    <style>
        .hdsd{
            padding: 15px;
            min-height:1000px;
        }
        .page-content img{
            width:80%;
            margin: 10px;
        }
        p{
            font-size:15px;
        }
        ul li{
            font-size:15px;
        }
        .p-image{
            text-align: center;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                   Hướng dẫn sử dụng
                </h2>
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

                <div class="panel panel-flat hdsd">
                    {{--<p style="text-align:center"><strong>Ch&agrave;o mừng bạn đến với hệ thống iCheck!</strong></p>--}}

                    {{--<p><em>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;iCheck xin giới thiệu tới bạn một hệ thống với nhiều t&iacute;nh năng quản l&yacute; dữ liệu sản phẩm của doanh nghiệp linh hoạt nhất. Kh&ocirc;ng chỉ quản l&yacute; dữ liệu doanh nghiệp m&agrave; hệ thống n&agrave;y c&ograve;n c&oacute; thể cho bạn thấy thống k&ecirc; chi tiết lượt qu&eacute;t theo ng&agrave;y theo th&aacute;ng để Doanh nghiệp c&oacute; thể khảo s&aacute;t chi tiết t&igrave;nh h&igrave;nh sản phẩm của m&igrave;nh đang được người d&ugrave;ng tiếp cận ra sao. &nbsp;</em></p>--}}

                    {{--<p><em>Ch&uacute;ng t&ocirc;i sẽ hướng dẫn bạn c&aacute;ch c&oacute; thể đăng nhập v&agrave; sử dụng hệ thống theo chi tiết dưới nh&eacute;.</em></p>--}}

                    {{--<p><strong>Bạn c&oacute; thể truy cập v&agrave;o link hệ thống iCheck:&nbsp;<a href="http://cms.icheck.com.vn/login">http://cms.icheck.com.vn/login</a></strong></p>--}}

                    {{--<p><strong>Sau đ&oacute;, Bạn tiến h&agrave;nh nhập T&ecirc;n t&agrave;i khoản v&agrave; mật khẩu đăng nhập v&agrave;o hệ thống nh&eacute;.</strong></p>--}}

                    {{--<p>Dưới đ&acirc;y l&agrave; giao diện Đăng nhập v&agrave;o hệ thống&nbsp;iCheck</p>--}}

                    {{--<p style="text-align:center"><br />--}}
                        {{--<img alt="" src="http://image.prntscr.com/image/11d42d20fba74a38b50d02fbf5531d7f.png" style="width:50%" /></p>--}}

                    {{--<p><strong>Sau khi đăng nhập được v&agrave;o hệ thống &nbsp;iCheck, Bạn c&oacute; thể sử dụng c&aacute;c t&iacute;nh năng tr&ecirc;n hệ thống bao gồm:</strong></p>--}}

                    {{--<p><strong>I. &Aacute;P DỤNG VỚI &nbsp;DOANH NGHIỆP SỞ HỮU M&Atilde; VẠCH</strong></p>--}}

                    {{--<p><strong>1.1.&nbsp;M&Atilde; ĐỊA ĐIỂM TO&Agrave;N CẦU (GLN) :</strong></p>--}}

                    {{--<p>M&atilde; GLN l&agrave; m&atilde; x&aacute;c định vị tr&iacute; địa l&yacute; theo ti&ecirc;u chuẩn GS1. M&atilde; GLN c&oacute; thể sử dụng để x&aacute;c định vị tri địa l&yacute; v&agrave; thực thể hợp ph&aacute;p. M&atilde; GLN sẽ l&agrave; m&atilde; doanh nghiệp quản l&yacute; to&agrave;n bộ c&aacute;c m&atilde; GTIN (M&atilde; thương phẩm) của doanh nghiệp đ&oacute;.</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/d3c4d77c2a3b402c8f55fab90c841527.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li><strong>T&iacute;nh năng &ldquo;T&Igrave;M KIẾM THEO T&Ecirc;N HOẶC GLN&hellip;&rdquo;:&nbsp;</strong>Doanh nghiệp c&oacute; thể dễ d&agrave;ng t&igrave;m kiếm theo m&atilde; T&ecirc;n hoặc m&atilde; GLN&hellip;</li>--}}
                    {{--</ul>--}}

                    {{--<p>Bạn c&oacute; thể chọn M&Atilde; ĐỊA ĐIỂM TO&Agrave;N CẦU (GLN) &ndash; T&Igrave;M KIẾM THEO T&Ecirc;N HOẶC GLN&hellip; nhập T&ecirc;n nh&agrave; sản xuất hoặc GLN của doanh nghiệp đ&oacute; để t&igrave;m kiếm.</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/98ee07a5494d4cda943b159396edf6b8.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li><strong>T&iacute;nh năng &ldquo;TH&Ecirc;M GLN&rdquo;:&nbsp;</strong>Sau khi tạo t&agrave;i khoản đăng nhập tr&ecirc;n hệ thống iCheck, hệ thống sẽ tự động cập nhật m&atilde; GLN để bạn quản l&yacute;. T&iacute;nh năng n&agrave;y c&oacute; thể gi&uacute;p Doanh nghiệp chủ động cập nhật th&ecirc;m m&atilde; GLN khi Doanh nghiệp c&oacute; th&ecirc;m nhu cầu.</li>--}}
                    {{--</ul>--}}

                    {{--<p>Bạn c&oacute; thể chọn M&Atilde; ĐỊA ĐIỂM TO&Agrave;N CẦU (GLN) &ndash; TH&Ecirc;M GLN (b&ecirc;n g&oacute;c phải)</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/dc367d53ba134ac89f51768c639d3317.png" style="width:70%" /></p>--}}

                    {{--<p>Doanh nghiệp điền đầy đủ c&aacute;c th&ocirc;ng cần thiết ( GLN, prefix, t&ecirc;n, địa chỉ, quốc gia&hellip;). Đối với trường th&ocirc;ng tin &ldquo;Giấy&nbsp;chứng nhận chủ sở hữu GLN&rdquo; &ndash; Bắt buộc Doanh nghiệp cần gửi những giấy tờ li&ecirc;n quan chứng thực m&atilde; GLN được ph&eacute;p sử dụng v&agrave; kinh doanh. Lưu &yacute; &nbsp;GLN v&agrave; prefix l&agrave; những th&ocirc;ng tin chỉ được nhập 1 lần duy nhất. DN sẽ kh&ocirc;ng được quyền sửa những th&ocirc;ng tin đ&oacute; sau n&agrave;y, nếu muốn chỉnh sửa bắt buộc phải gửi y&ecirc;u cầu đến iCheck.</p>--}}

                    {{--<ul>--}}
                        {{--<li><strong>T&iacute;nh năng &ldquo; SỬA/ X&Oacute;A&nbsp;GLN&rdquo;</strong>&nbsp;: Với t&iacute;nh năng n&agrave;y cho ph&eacute;p Doanh nghiệp chủ quản c&oacute; thể thay đổi th&ocirc;ng tin dữ liệu đối với m&atilde; GLN đang quản l&yacute;.</li>--}}
                    {{--</ul>--}}

                    {{--<p>Để thao t&aacute;c chọn chỉnh sửa th&ocirc;ng tin của 1 m&atilde; GLN, bạn c&oacute; thể chọn v&agrave;o biểu tượng &nbsp;ở g&oacute;c b&ecirc;n phải m&agrave;n h&igrave;nh v&agrave; chọn <strong>Sửa</strong>.</p>--}}

                    {{--<p>Nếu kh&ocirc;ng c&ograve;n&nbsp;nhu cầu sử dụng m&atilde; GLN hoặc m&atilde; GLN sai th&ocirc;ng tin th&igrave; Doanh nghiệp đ&oacute; c&oacute; thể chủ động x&oacute;a bỏ th&ocirc;ng tin.</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/e508a3ac1efc4d9f97c9f2c0ba0c356b.png" style="width:70%" /></p>--}}

                    {{--<p>C&aacute;c y&ecirc;u cầu <strong>Th&ecirc;m mới, chỉnh sửa, x&oacute;a</strong> th&ocirc;ng tin GLN, Doanh Nghiệp cũng cần chờ Duyệt y&ecirc;u cầu b&ecirc;n ph&iacute;a Quản trị iCheck.</p>--}}

                    {{--<p><strong>1.2&nbsp;SẢN PHẨM</strong></p>--}}

                    {{--<p style="text-align:center">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<img alt="" src="http://image.prntscr.com/image/b192f5e5416642feb6a8b5a36ab2eb58.png" style="width:70%" /></p>--}}

                    {{--<p>Quản l&yacute; to&agrave;n bộ m&atilde; GTIN (M&atilde; thương phẩm) của Doanh nghiệp sở hữu dựa tr&ecirc;n th&ocirc;ng tin GLN doanh nghiệp cung cấp, bao gồm:</p>--}}

                    {{--<p>+ M&atilde; GTIN (M&atilde; thương phẩm)</p>--}}

                    {{--<p>+ Th&ocirc;ng tin chi tiết Sản phẩm (T&ecirc;n- H&igrave;nh ảnh- Chi tiết sản phẩm)</p>--}}

                    {{--<p>+&nbsp; T&igrave;nh trạng cập nhật m&atilde; v&agrave; l&yacute; do phản hồi từ quản trị iCheck</p>--}}

                    {{--<ul>--}}
                        {{--<li><strong>T&iacute;nh năng &ldquo;T&Igrave;M KIẾM&quot;</strong>--}}

                            {{--<ul>--}}
                                {{--<li><strong>THEO T&Ecirc;N HOẶC GTIN&hellip;.&rdquo;:</strong>&nbsp;Doanh nghiệp c&oacute; thể dễ d&agrave;ng t&igrave;m kiếm theo T&ecirc;n hoặc m&atilde; Gtin của sản phẩm. Doanh nghiệp c&oacute; thể t&igrave;m kiếm v&agrave; chỉnh sửa th&ocirc;ng tin theo t&ecirc;n hoặc gtin n&agrave;y.</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/9e24315d61ad43eea60ceb0a59be459b.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>--}}
                            {{--<ul>--}}
                                {{--<li><strong>THEO T&Igrave;NH TRẠNG M&Atilde; : Đ&Atilde; DUYỆT, ĐANG CHỜ DUYỆT, BỊ TỪ CHỐI.</strong>...: Doanh nghiệp c&oacute; thể t&igrave;m v&agrave; chỉnh sửa lại c&aacute;c th&ocirc;ng tin chưa được duyệt hoặc xem t&igrave;nh trạng m&atilde; đ&atilde; đ&oacute;ng g&oacute;p th&ocirc;ng tin của m&igrave;nh</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/5909e435572a4adda438d16bf636d0f4.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>--}}
                            {{--<ul>--}}
                                {{--<li><strong>THEO GLN :</strong> nếu doanh nghiệp sở hữu nhiều GLN, DN c&oacute; thể lọc để xem c&aacute;c sản phẩm thuộc từng GLN</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/994660fc8a4f4e74b989c7d508120eeb.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li><strong>T&iacute;nh năng &ldquo; TH&Ecirc;M SẢN PHẨM&rdquo;:</strong>&nbsp;Hệ thống sẽ tự động cập nhật to&agrave;n m&atilde; c&aacute;c m&atilde; GTIN (M&atilde; thương phẩm) v&agrave;o t&agrave;i khoản của bạn để dễ d&agrave;ng quản l&yacute;.&nbsp; T&iacute;nh năng &ldquo;Th&ecirc;m sản phẩm&rdquo; sẽ gi&uacute;p bạn c&oacute; thể chủ động cập nhật th&ecirc;m khi cần</li>--}}
                    {{--</ul>--}}

                    {{--<p><strong>&rarr; TH&Ecirc;M SẢN PHẨM (Th&ecirc;m từng m&atilde; sản phẩm)</strong></p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/4f133fda5c8c463793858ffcbc024a06.png" style="width:70%" /></p>--}}

                    {{--<p>Hệ thống sẽ dựa tr&ecirc;n m&atilde; prefix ( hoặc GLN) m&agrave; DN cung cấp từ đ&oacute; tự động sinh ra m&atilde; mới cho c&aacute;c sản phẩm của DN. Trường hợp Doanh nghiệp muốn tạo m&atilde; theo nhu cầu, Doanh nghiệp c&oacute; thể tự nhập m&atilde; ph&acirc;n định sản phẩm, hệ thống sẽ sinh ra m&atilde; kiểm tra tự động cho mỗi m&atilde; của DN.</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/d85bbae8b04c4c9ba51a646c8edb3311.png" style="width:70%" /></p>--}}

                    {{--<p>Sau đ&oacute; DN tiếp tục nhập c&aacute;c th&ocirc;ng tin kh&aacute;c: t&ecirc;n, ảnh, gi&aacute;,&nbsp;chi tiết sản phẩm, th&ocirc;ng tin c&ocirc;ng ty, ph&acirc;n biệt thật giả, danh mục sản phẩm v&agrave; ấn <strong>&quot;CẬP NHẬT&quot;</strong></p>--}}

                    {{--<p><strong>&rarr; NHẬP/ SỬA&nbsp;TỪ FILE EXCEL:</strong> Bạn c&oacute; thể th&ecirc;m to&agrave;n bộ th&ocirc;ng tin sản phẩm tr&ecirc;n 1 file excel m&agrave; kh&ocirc;ng cần nhập ri&ecirc;ng từng m&atilde; sản phẩm. Mẫu file exel Doanh nghiệp c&oacute; thể &nbsp;tải trực tiếp tại <strong><a href="http://cms.icheck.com.vn/download">Đ&Acirc;Y</a> </strong>hoặc ngay tại t&agrave;i khoản của qu&yacute; Doanh nghiệp</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/901c3f1171ca4a39ad6a40c8fd0de77f.png" style="width:70%" /></p>--}}

                    {{--<p><strong>T&iacute;nh năng n&agrave;y cần lưu &yacute; c&aacute;c vấn đề sau:</strong></p>--}}

                    {{--<ul>--}}
                        {{--<li>Khi nhập bằng file DN sẽ sử dụng form dữ liệu iCheck cung cấp.</li>--}}
                        {{--<li>Trong file c&oacute; mục Danh mục th&igrave; DN cần điền theo c&aacute;c STT danh mục iCheck cung cấp. &nbsp;&ndash;&nbsp;VD: &nbsp;13 | 34 | 12 (Theo thứ tự : Cate &Ocirc;ng &ndash; Cha &ndash; Con)</li>--}}
                        {{--<li>Nếu DN nhập sai form hoặc m&atilde; vạch sai định dạng, m&atilde; vạch kh&ocirc;ng thuộc DN quản l&yacute;, hệ thống sẽ từ chối cập nhật v&agrave; gửi th&ocirc;ng b&aacute;o &nbsp;cho Doanh nghiệp.<br />--}}
                            {{--&nbsp;</li>--}}
                        {{--<li><strong>T&iacute;nh năng &ldquo; SỬA&rdquo; sản phẩm:&nbsp;</strong>Hỗ trợ Doanh nghiệp c&oacute; thể chỉnh sửa th&ocirc;ng tin sản phẩm khi cần thiết&nbsp;</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/92835b3ad74447439e38292fe954b380.png" style="width:70%" /></p>--}}

                    {{--<p><strong>II. &Aacute;P DỤNG VỚI DOANH NGHIỆP PH&Acirc;N PHỐI&nbsp;SẢN PHẨM</strong></p>--}}

                    {{--<p>Mỗi t&agrave;i khoản Doanh nghiệp ph&acirc;n phối đều c&oacute; 2 quyền lợi cơ bản</p>--}}

                    {{--<ul>--}}
                        {{--<li>Quyền xem thống k&ecirc; về sản phẩm</li>--}}
                        {{--<li>Quyền chỉnh sửa, đ&oacute;ng g&oacute;p th&ocirc;ng tin sản phẩm</li>--}}
                    {{--</ul>--}}

                    {{--<p>C&aacute;c quyền lợi của t&agrave;i khoản doanh nghiệp được ph&acirc;n chia theo loại h&igrave;nh doanh nghiệp ph&acirc;n phối. Hiện tại tr&ecirc;n quản trị của iCheck ph&acirc;n chia doanh nghiệp ph&acirc;n phối như sau:</p>--}}

                    {{--<ul>--}}
                        {{--<li>Doanh nghiệp ph&acirc;n phối độc quyền: được quyền xem thống k&ecirc; v&agrave; chỉnh sửa to&agrave;n bộ th&ocirc;ng tin sản phẩm m&agrave; Doanh nghiệp đ&oacute; chứng minh được việc ph&acirc;n phối độc quyền sản phẩm đ&oacute; tại thị trường Việt Nam. Quyền chỉnh sửa th&ocirc;ng tin sản phẩm sẽ được chuyển giao cho DN sở hữu m&atilde; vạch nếu DN sỡ hữu m&atilde; vạch trực tiếp hợp t&aacute;c với iCheck</li>--}}
                        {{--<li>Doanh nghiệp ph&acirc;n phối lẻ : được ph&eacute;p xem thống k&ecirc; v&agrave; chỉnh sửa to&agrave;n bộ th&ocirc;ng tin sản phẩm do DN đ&oacute; ph&acirc;n phối trong trường hợp--}}
                            {{--<ul>--}}
                                {{--<li>Chưa c&oacute; DN sản xuất hoặc DN ph&acirc;n phối độc quyền sản phẩm tại thị trường VN l&agrave;m việc với iCheck</li>--}}
                                {{--<li>DN l&agrave; DN ph&acirc;n phối lẻ đầu ti&ecirc;n hợp t&aacute;c với iCheck v&agrave; y&ecirc;u cầu quyền chỉnh sửa th&ocirc;ng tin sản phẩm được quyền chỉnh sửa</li>--}}
                                {{--<li>Quyền chỉnh sửa th&ocirc;ng tin sản phẩm sẽ được chuyển giao cho DN sở hữu m&atilde; vạch hoặc DN ph&acirc;n phối độc quyền nếu DN sỡ hữu m&atilde; vạch hoặc DN ph&acirc;n phối độc quyền trực tiếp hợp t&aacute;c với iCheck</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}

                    {{--<p>Doanh nghiệp c&oacute; thể gửi y&ecirc;u cầu đăng k&yacute; chỉnh sửa sản phẩm theo 3 c&aacute;ch</p>--}}

                    {{--<ul>--}}
                        {{--<li>Đăng k&yacute; lẻ từng m&atilde; bằng c&aacute;ch t&igrave;m kiếm m&atilde; vạch sản phẩm v&agrave; gửi y&ecirc;u cầu đăng k&yacute; ph&acirc;n phối.</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/67026d3022ca441c93391c9127157ce2.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>Sử dụng file th&ocirc;ng tin ( được tải tại <a href="http://cms.icheck.com.vn/download-pp">Đ&Acirc;Y</a> hoặc t&agrave;i t&agrave;i khoản của qu&yacute; DN), sau khi DN sử dụng thao t&aacute;c &ldquo;Sửa sản phẩm từ exel&rdquo;, hệ thống sẽ tự động gửi y&ecirc;u cầu đăng k&yacute; ph&acirc;n phối cho qu&yacute; doanh nghiệp.</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/dc24a488fe724617bd2f9e196c96f2e4.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>Tổng hợp bảng m&atilde; vạch v&agrave; gửi y&ecirc;u cầu trực tiếp qua nh&acirc;n vi&ecirc;n hỗ trợ của iCheck</li>--}}
                    {{--</ul>--}}

                    {{--<p>Sau khi đăng k&yacute; th&agrave;nh c&ocirc;ng, t&agrave;i khoản sẽ hiện thị trạng th&aacute;i để DN biết DN c&oacute; quyền chỉnh sửa với m&atilde; vạch đ&oacute; hay kh&ocirc;ng. Nếu DN l&agrave; DN đầu ti&ecirc;n đăng k&yacute; ph&acirc;n phối, DN sẽ c&oacute; quyền chỉnh sửa sản phẩm. Nếu DN l&agrave; DN đăng k&yacute; thứ 2 trở đi, DN sẽ được quyền xem th&ocirc;ng k&ecirc; về sản phẩm ( với trường hợp Doanh nghiệp l&agrave; DN ph&acirc;n phối lẻ)</p>--}}

                    {{--<p>DN c&oacute; thể sử dụng bộ lọc để biết những m&atilde; m&agrave; b&ecirc;n m&igrave;nh được chỉnh sửa/kh&ocirc;ng được chỉnh sửa.</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/9093d22244844cb9a6800fc723d69f24.png" style="width:70%" /></p>--}}

                    {{--<p>DN c&oacute; thể &ldquo;<strong>Hủy đăng k&yacute;</strong>&rdquo; nếu kh&ocirc;ng muốn xem thống k&ecirc; hay tiếp tục chỉnh sửa th&ocirc;ng tin của m&atilde; đ&oacute;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/ac2104d26eef4db8aced664b5462c9b1.png" style="width:70%" /></p>--}}

                    {{--<p>Với c&aacute;c m&atilde; được chỉnh sửa, Doanh nghiệp thực hiện thao t&aacute;c &ldquo;<strong>Sửa</strong>&rdquo; bằng tay hoặc bằng file theo như hướng dẫn ở tr&ecirc;n</p>--}}

                    {{--<p><strong>III. &Aacute;P DỤNG VỚI DOANH NGHIỆP SẢN XUẤT HOẶC PH&Acirc;N PHỐI C&Oacute; K&Yacute; HỢP ĐỒNG DỊCH VỤ TH&Ocirc;NG TIN VỚI ICHECK</strong></p>--}}

                    {{--<p>Doanh nghiệp được sử dụng c&aacute;c quyền lợi sau đ&acirc;y đối với c&aacute;c g&oacute;i đ&atilde; được k&yacute; kết với iCheck trong thời hạn hợp đồng hợp t&aacute;c c&oacute; gi&aacute; trị</p>--}}

                    {{--<p><strong>2.1&nbsp;XEM V&Agrave; GHIM B&Igrave;NH LUẬN SẢN PHẨM</strong></p>--}}

                    {{--<p>Bằng việc cung cấp iCheck - ID của t&agrave;i khoản đăng nhập iCheck cho ch&uacute;ng t&ocirc;i, qu&yacute; doanh nghiệp c&oacute; thể thực hiện c&aacute;c t&iacute;nh năng như : xem v&agrave; b&igrave;nh luận của sản phẩm m&agrave; qu&yacute; kh&aacute;ch sản xuất/ph&acirc;n phối ngay tại t&agrave;i khoản Doanh nghiệp</p>--}}

                    {{--<p>iCheck - ID ch&iacute;nh l&agrave; v&ugrave;ng khoanh đỏ trong ảnh m&ocirc; tả dưới d&acirc;y:</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://i.imgur.com/hMKpAvP.jpg" style="width:30%" /></p>--}}

                    {{--<p>DN truy cập v&agrave;o tab <strong>Sản phẩm</strong> ( đối với DN sản xuất) hoặc tab <strong>Ph&acirc;n phối sản phẩm</strong> (đối với DN ph&acirc;n phối) sau đ&oacute;&nbsp;chọn sản phẩm muốn&nbsp;xem v&agrave; b&igrave;nh luận trực tiếp v&agrave;o sản phẩm đ&oacute;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/1a7581c7deb947ed93637eeeb3b87ed6.png" style="width:70%" /></p>--}}

                    {{--<p>Nhấp chuột v&agrave;o n&uacute;t &quot;Comment&quot; hệ thống sẽ hiện ra cho bạn to&agrave;n bộ comment của sản phẩm đ&oacute;, từ đ&acirc;y bạn c&oacute; thể b&igrave;nh luận v&agrave; ghim b&igrave;nh luận của m&igrave;nh&nbsp;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/f94582f60a76458c8074e2fbc73b37b1.png" style="width:70%" /></p>--}}

                    {{--<p>Ấn &quot; <strong>GHIM</strong>&quot; nếu muốn giữ b&igrave;nh luận đ&oacute; của bạn&nbsp;ở đầu danh s&aacute;ch b&igrave;nh luận</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/a209cb9af35846ea9116e1cc4673993a.png" style="width:70%" /></p>--}}

                    {{--<p>V&agrave; &quot;<strong>Bỏ ghim</strong>&quot; nếu thấy n&oacute; ko cần thiết nữa</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/0b4d15a6c4b94314ba38fd63c815707e.png" style="width:70%" /></p>--}}

                    {{--<p><strong>2.2&nbsp;SẢN PHẨM LI&Ecirc;N QUAN</strong></p>--}}

                    {{--<p>Sản phẩm li&ecirc;n quan l&agrave; sản phẩm hiện thị ở mục *<strong>Sản phẩm li&ecirc;n quan</strong>&quot; khi người d&ugrave;ng xem th&ocirc;ng tin/ qu&eacute;t m&atilde; vạch sản phẩm do qu&yacute; DN quản l&yacute;&nbsp;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/2384b2e034454a73b6f81830a8a91713.png" style="width:70%" /></p>--}}

                    {{--<p>Click v&agrave;o &quot;<strong>Sản phẩm li&ecirc;n quan</strong>&quot; để xem danh s&aacute;ch c&aacute;c sản ph&acirc;m được c&agrave;i đặt l&agrave; sản phẩm li&ecirc;n quan&nbsp;cho sản phẩm đ&oacute;,</p>--}}

                    {{--<p>Sản phẩm li&ecirc;n quan sẽ được c&agrave;i đặt mặc định theo t&agrave;i khoản Doanh nghiệp, Doanh nghiệp c&oacute; thể x&oacute;a 1 hoặc nhiều sản phẩm li&ecirc;n quan khỏi danh s&aacute;ch, v&agrave; th&ecirc;m lại sau đ&oacute; nếu cần&nbsp;</p>--}}

                    {{--<p>- &nbsp;Đối với DN sản xuất : C&aacute;c sản phẩm sản xuất sẽ tự động hiện thị sản phẩm li&ecirc;n quan&nbsp;l&agrave; sản phẩm c&ugrave;ng nh&agrave; sản xuất</p>--}}

                    {{--<p>- Đối với DN ph&acirc;n phối:&nbsp;&nbsp;C&aacute;c sản phẩm ph&acirc;n phối sẽ tự động hiển thị sản phẩm li&ecirc;n quan&nbsp;l&agrave; sản phẩm do c&ugrave;ng Doanh nghiệp đ&oacute; ph&acirc;n phối v&agrave; được cấp quyền chỉnh sửa</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/46625175465749b3a2de241a3e577cff.png" style="width:70%" /></p>--}}

                    {{--<p>Chọn c&aacute;c sản phẩm bạn kh&ocirc;ng muốn hiển thị ở trường sản phẩm li&ecirc;n quan của sản phẩm tr&ecirc;n v&agrave; chọn &quot;<strong>Remove</strong>&quot;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/872ed32c15214fc18ddc2eca3b9a0d61.png" style="width:70%" /></p>--}}

                    {{--<p>Hoặc c&oacute; thể chọn lại ở danh s&aacute;ch c&aacute;c sản phẩm chưa được set, v&agrave; chọn &quot;<strong>ADD</strong>&quot;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/537fb13f4b884f6b928a71a707a7175e.png" style="width:70%" /></p>--}}

                    {{--<p>Doanh nghiệp c&oacute; thể lựa chọn hiện thị trong tất cả sản phẩm m&igrave;nh quản l&yacute; ( <strong>Chọn theo sản phẩm</strong>) hoặc chọn theo sản phẩm c&ugrave;ng nh&agrave; sản xuất ( <strong>Chọn theo nh&agrave; sản xuất</strong>)</p>--}}

                    {{--<p><strong>2.3 THỐNG K&Ecirc; DỮ LIỆU VỀ SẢN PHẨM</strong></p>--}}

                    {{--<ul>--}}
                        {{--<li>Thống k&ecirc; dữ liệu v&agrave; lựa chọn thời gian thống k&ecirc; : &nbsp;Lượt hiển thị - Lượt qu&eacute;t &ndash; Lượt th&iacute;ch &ndash;&ndash; Lượt b&igrave;nh luận&nbsp;</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/25e64b3b251344edb52b98fcd43c9ec4.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>Bạn c&oacute; thể xem chi tiết c&aacute;c th&ocirc;ng k&ecirc; của từng m&atilde; theo thời gian &nbsp;(Lượt qu&eacute;t &ndash; Th&iacute;ch &ndash; Bỏ th&iacute;ch &ndash; B&igrave;nh luận - Lượt đ&aacute;nh gi&aacute; tốt - Trung B&igrave;nh - Kh&ocirc;ng tốt- Lượt share....) bằng c&aacute;ch click chi tiết v&agrave;o từng m&atilde;</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/ffc2faceb37945df8ba4f1affae326f4.png" style="width:70%" /></p>--}}

                    {{--<p>Chi tiết từng m&atilde; theo biểu đồ</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/7871807a7953424bb2c7d69ca7ea432d.png" style="width:70%" /></p>--}}

                    {{--<p>K&eacute;o xuống l&agrave; chi tiết từng m&atilde; theo từng ng&agrave;y trong khoảng thời gian lựa chọn</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/efe5a713a7184123b5299754ef2a643e.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>Bạn c&oacute; thể nhập 1 m&atilde; vạch GTIN sản phẩm cần thống k&ecirc; lượt qu&eacute;t hoặc 1 m&atilde; prefix cần thống k&ecirc; v&agrave; chọn khoảng thời gian &nbsp;(giờ- Ng&agrave;y &ndash; Th&aacute;ng&hellip;) tra cứu cần thiết l&agrave; hệ thống c&oacute; thể thống k&ecirc; v&agrave; hiển thị ra m&agrave;n h&igrave;nh.</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/34d6d4e558384d2e8cac608bd3ad9d12.png" style="width:70%" /></p>--}}

                    {{--<p>&nbsp;</p>--}}

                    {{--<p><strong>2.4&nbsp;THỐNG K&Ecirc; DỮ LIỆU VỀ DANH MỤC SẢN PHẨM</strong></p>--}}

                    {{--<p>Thống k&ecirc; dữ liệu v&agrave; lựa chọn thời gian thống k&ecirc; : &nbsp;Lượt hiển thị - Lượt qu&eacute;t &ndash; Lượt th&iacute;ch &ndash;&ndash; Lượt b&igrave;nh luận&nbsp;</p>--}}

                    {{--<ul>--}}
                        {{--<li>Theo biểu đồ h&igrave;nh tr&ograve;n</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/db2c8cd51e3a4206a6fb0d715f1ac225.png" style="width:70%" /></p>--}}

                    {{--<ul>--}}
                        {{--<li>Theo thống k&ecirc; chi tiết, từ đ&oacute; DN c&oacute; thể biết c&aacute;c sản phẩm m&igrave;nh&nbsp;quản l&yacute; thuộc c&aacute;c danh mục n&agrave;o, lượt quan t&acirc;m của người d&ugrave;ng với danh mục n&agrave;o ra sao</li>--}}
                    {{--</ul>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/d3f5c168aa924f7cb93b64ace156bc88.png" style="width:70%" /></p>--}}

                    {{--<p><strong>2.5&nbsp;THỐNG K&Ecirc; DỮ LIỆU VỀ ĐỘ TUỔI NGƯỜI D&Ugrave;NG</strong></p>--}}

                    {{--<p>Với thống k&ecirc; n&agrave;y, DN c&oacute; thể nhận định được đối tượng kh&aacute;ch h&agrave;ng quan t&acirc;m tới sản phẩm của m&igrave;nh tr&ecirc;n iCheck, từ đ&oacute; đưa ra c&aacute;c định hướng điều chỉnh, kế hoạch ph&aacute;t triển sản phẩm&nbsp;ph&ugrave; hợp</p>--}}

                    {{--<p>Thống k&ecirc; được hiển thị dưới dạng biểu đồ</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/f5260c2639be43858ff69fbfee45ff42.png" style="width:70%" /></p>--}}

                    {{--<p><strong>2.5&nbsp;THỐNG K&Ecirc; DỮ LIỆU VỀ VỊ TR&Iacute; ĐỊA L&Yacute; CỦA&nbsp;NGƯỜI D&Ugrave;NG</strong></p>--}}

                    {{--<p>Thống k&ecirc; dữ liệu v&agrave; lựa chọn thời gian thống k&ecirc; : &nbsp;Lượt hiển thị - Lượt qu&eacute;t &ndash; Lượt th&iacute;ch &ndash;&ndash; Lượt b&igrave;nh luận của từng khu vực vực địa l&yacute;&nbsp;</p>--}}

                    {{--<p style="text-align:center"><img alt="" src="http://image.prntscr.com/image/42a86beff4334471a3f485e6133dd21f.png" style="width:70%" /></p>--}}

                    {{--<p>Doanh nghiệp lựa chọn sắp xếp theo thứ tự được quan t&acirc;m nhiều nhất ( hiển thị nhiều nhất, qu&eacute;t nhiều nhất, like, comment nhiều nhất) để biết được khu vực địa l&yacute; n&agrave;o tập trung nhiều người d&ugrave;ng quan t&acirc;m đến sản phẩm của DN nhất, từ đ&oacute; đưa ra c&aacute;c chiến lược ph&ugrave; hợp</p>--}}

                    {{--<ul>--}}
                    {{--</ul>--}}

                    {{--<p>Ch&uacute;ng t&ocirc;i đ&atilde; tổng hợp lại to&agrave;n bộ những t&iacute;nh năng m&agrave; hệ thống iCheck&nbsp;đang sở hữu k&egrave;m theo đ&oacute; l&agrave; c&aacute;ch thao t&aacute;c sử dụng tr&ecirc;n hệ thống.</p>--}}

                    {{--<p>Trong qu&aacute; tr&igrave;nh thao t&aacute;c nếu c&ograve;n vấn đề chưa r&otilde; bạn c&oacute; thể li&ecirc;n hệ lại với ch&uacute;ng t&ocirc;i để được hỗ trợ một c&aacute;ch tốt nhất.</p>--}}

                    {{--<p>Hotline hỗ trợ: <a href="tel:1900066858">1900 066858</a>.- Email:&nbsp;<u><a href="mailto:cskh@icheck.vn">cskh@icheck.vn</a></u>.- Website:&nbsp;<a href="http://icheckcorp.com/">www.icheckcorp.com</a></p>--}}

                    {{--<p>Ch&uacute;c bạn c&oacute; những điều tuyệt vời khi sử dụng tiện &iacute;ch n&agrave;y của iCheck!</p>--}}

                    <?php

                    echo "<iframe src=".asset('HDSD.pdf')." width=\"100%\" style=\"min-height:1000px\"></iframe>";

                    ?>

                </div>

            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
@endsection

@push('js_files_foot')

@endpush

@push('scripts_foot')

@endpush
