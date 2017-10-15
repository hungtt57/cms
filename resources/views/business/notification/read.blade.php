@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thông Báo</h2>
            </div>

            <div class="heading-elements">

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
                <div class="row" style="margin-bottom: 30px">
                </div>

                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif


                <div class="panel panel-flat">
                    <h4 style="text-align:center">{!! $notification->content !!}</h4>
                    <table class="table table-hover">
                        @if(count($eBARCODE))
                            <tr>
                                <td>BARCODE sai định dạng:</td>
                                <td>

                                    @foreach($eBARCODE as $barcode)
                                        {{$barcode}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif

                        @if(count($eB))
                            <tr>
                                <td>BARCODE sai vendor:</td>
                                <td>

                                    @foreach($eB as $b)
                                        {{$b}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif
                        @if(count($eGLN))
                            <tr>
                                <td>GLN sai định dạng hoặc thiếu ở barcode :</td>
                                <td>
                                    @foreach($eGLN as $gln)
                                        {{$gln}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif
                        @if(count($eImage))
                            <tr>
                                <td>Ảnh dung lượng nhỏ hơn 20KB :</td>
                                <td>
                                    @foreach($eImage as $image)
                                        {{$image}}<br>
                                    @endforeach

                                </td>
                            </tr>
                        @endif

                        @if(count($eBarcodePP))
                            <tr>
                                <td>Barocde không có trên hệ thống hoặc sai định dạng :</td>
                                <td>
                                    @foreach($eBarcodePP as $e)
                                        {{$e}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif

                        @if(count($eEditPP))
                            <tr>
                                <td>Không có quyền sửa barcode :</td>
                                <td>>
                                    @foreach($eEditPP as $edit)
                                        {{$edit}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif
                        @if(count($barcodeSX_invalid))
                            <tr>
                                <td>Không có quyền sửa barcode :</td>
                                <td>
                                    @foreach($barcodeSX_invalid as $t)
                                        {{$t}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif

                        @if(count($eSX))
                            <tr>
                                <td>Barcode là mã đã đăng kí sản xuất :</td>
                                <td>
                                    @foreach($eSX as $ed)
                                        {{$ed}}
                                    @endforeach

                                </td>
                            </tr>
                        @endif


                            @if(count($ePrice))
                                <tr>
                                    <td>Giá sai định dạng,hoặc không phải là số :</td>
                                    <td>>
                                        @foreach($ePrice as $eprice)
                                            {{$eprice}}
                                        @endforeach

                                    </td>
                                </tr>
                            @endif


                    </table>

                </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="delete-modal-label">Phân phối Sản phẩm</h4>
                </div>
                <div class="modal-body">
                    Quý Doanh nghiệp có chắc chắn muốn đăng kí phân phối sản phẩm ??
                </div>
                <div class="modal-footer">
                    <form method="POST" id="form-register"
                          action="{{ route('Business::product@PostRegisterProduct') }}">
                        {{ csrf_field() }}
                        <input type="hidden" class="product-distributor" name="product_id">
                        <input type="hidden" name="_method" value="POST">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-danger" id="submit-register">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label"
         data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('Business::product@importDistributor') }}"
                      enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="import-modal-label">Nhập nhiều sản phẩm từ file Excel</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason" class="control-label text-semibold">Tệp tin</label>
                            <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
                            <input id="reason" type="file" name="file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
                        <button type="submit" class="btn btn-primary">Nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $('#delete-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('delete-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });

    //    $(".js-checkbox").uniform({ radioClass: "choice" });
    $('#select-all').on('click', function () {
        $('.s').prop('checked', this.checked);
    });
    $('#submit-register').on('click', function (e) {
        e.preventDefault();
        var array = new Array();
        $(".s:checked").each(function () {
            array.push($(this).val());
        });
        $('.product-distributor').val(array);
        $('#form-register').submit();
    });
    function change() {
        $('#form').submit();
    }

</script>
@endpush



