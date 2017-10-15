@extends('_layouts/default')
@push('css_files_head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.45/css/bootstrap-datetimepicker.min.css"
      rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('css/hungtt.css') }}" type="text/css">

<style>
    .row {
        margin-left: 10px !important;
        margin-right: 10px !important;
    }

    #highlighted-justified-tab1 .row {
        margin-bottom: 10px !important;
    }
    .table-search{
        margin-top: 10px;
    }

    .table-change {
        border-width: 2px;
        border: 1px solid #3f51b5;
    }



    .table-change  { overflow:hidden;}
    .table-change:hover { overflow:auto; }

    #search-form .table > tbody > tr > td, .table > tbody > tr > th, #search-form .table > tfoot > tr > td, #search-form .table > tfoot > tr > th, #search-form .table > thead > tr > td, #search-form .table > thead > tr > th {

        border: none !important;
    }

    .date {
        margin-top: -10px;
    }

    select[multiple] {
        height: 400px !important;
    }

    #changeGtin {
        margin-top: 10px;
        margtin-bottom: 10px;
    }

    .title-box {
        width: 100%;
        text-align: center;
        padding: 10px;
        background-color: #3f51b5;
        color: white;
        margin-bottom: 0px !important;
        /*border-radius: 48px;*/
    }

    .button-add-remove {
        text-align: center;
    }
    .pagination-row{
        /*width: 100%;*/
        padding: 20px;
        text-align: left;
    }
    .input-search{
        padding: 12px 30px;
    }
    .table>tbody>tr>td{
        padding: 5px 20px !important;
    }

</style>
@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Sản phẩm phân phối liên quan</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">


                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    @if (session('success'))
        <div class="alert bg-success alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                        class="sr-only">Close</span></button>
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert bg-danger alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                        class="sr-only">Close</span></button>
            {{ session('error') }}
        </div>
    @endif
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->

            <div class="content-wrapper">
                <div class="container">


                </div>

                <div class="panel panel-flat">
                    <form role="form" id="search-form">
                        <div class="row">

                            <div class="col-md-6 col-md-offset-3" style="background: white;">

                                <table class="table table-responsive table-search">
                                    <tr>

                                        <td style="text-align: right;"> Gtin code :</td>
                                        <td ><p style="margin:0 !important;">{{$gtin}}</p></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>

                                            {{--<select class="form-control"--}}
                                                    {{--name="type"--}}
                                                    {{--id="status-filter">--}}

                                                {{--<option value="1"--}}
                                                        {{--@if(Request::input('type')==1) selected="selected" @endif>Chọn--}}
                                                    {{--theo sản phẩm--}}
                                                {{--</option>--}}

                                                {{--<option value="2"--}}
                                                        {{--@if(Request::input('type') != 1 ) selected="selected" @endif--}}

                                                {{-->Chọn theo nhà sản xuất--}}
                                                {{--</option>--}}

                                            {{--</select>--}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input class="form-control" type="text" name="search"
                                                   value="{{Request::input('search')}}"
                                                   placeholder="Search theo tên và gtin"/>
                                            <input type="hidden" name="page" id="page" value="">
                                        </td>
                                    </tr>


                                </table>


                                <!-- End of Search Form -->

                            </div>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-5 input-search" >

                                </div>

                            </div>
                            <div class="row" style="margin-bottom:10px">
                                <div class="col-md-9 col-md-offset-3" style="text-align:right">
                                    <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#edit-pro">Search
                                    </button>
                                </div>
                            </div>


                        </div>
                    </form>
                    <div class="row" style="display: block">

                        <form action=""
                              method="POST">
                            {{--<input type="hidden" name="gtin_code"--}}
                            {{--value="{{Request::input('gtin_code')}}">--}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="hook_id" value="{{$hookId}}">
                            <div class="row">

                                <div class="col-md-5">
                                    <p class="title-box">Sản phẩm chưa được set</p>
                                    <select id="id_other_teams" class="form-control table-change"
                                            name="other_teams[]"
                                            MULTIPLE>
                                        @foreach($products as $product)
                                            @if($product->product)
                                            <option value="{{$product->product->gtin_code}}"> {{$product->product->product_name}}
                                                ({{$product->product->gtin_code}})
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 button-add-remove">
                                    <input type="Button" class="btn btn-default" value="Add >>"
                                           style="width:100px"
                                           onClick="SelectMoveRows(document.getElementById('id_other_teams'),document.getElementById('id_my_teams'))">
                                    <br>
                                    <br>
                                    <input type="Button" class="btn btn-default" value="<< Remove"
                                           style="width:100px"
                                           onClick="SelectMoveRows(document.getElementById('id_my_teams'),document.getElementById('id_other_teams'))">
                                </div>
                                <div class="col-md-5">
                                    <p class="title-box">Sản phẩm đã được set</p>
                                    <select id="id_my_teams" name="my_teams[]"
                                            class="table-change form-control sortable"
                                            MULTIPLE>
                                        @if(isset($hook_product))
                                            @foreach($hook_product as $pH)
                                                @if(isset($pH->product))
                                                    <option value="{{$pH->product->gtin_code}}"> {{$pH->product->product_name}}
                                                        ({{$pH->product->gtin_code}})
                                                    </option>
                                                @else
                                                    <option value="{{$pH->product_id}}"> {{$pH->product_id}}
                                                        ({{$pH->product_id}})
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row pagination-row">


                                    {!! $products->appends(Request::all())->links() !!}

                            </div>


                        </form>


                    </div>

                </div>
            </div>
        </div>

        <!-- /main content -->
    </div>

    <!-- /page content -->
    </div>
    <!-- /page container -->

    <div id="preloader" style="display:none">
        <div class="clock">
            <div class="arrow_sec"></div>
            <div class="arrow_min"></div>
        </div>
    </div>








@endsection

@push('js_files_foot')

<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(function () {

        $('#start-date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        $('#end-date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
//



    });
</script>

<script>
    $('#select-all').on('click', function () {
        $('.s').prop('checked', this.checked);
    });

    function SelectMoveRows(SS1, SS2) {
        var SelID = '';
        var SelText = '';
        var arrayNewGtin = [];
        // Move rows from SS1 to SS2 from bottom to top
        for (i = SS1.options.length - 1; i >= 0; i--) {
            if (SS1.options[SS1.options.length - 1 - i].selected == true) {

                SelID = SS1.options[SS1.options.length - 1 - i].value;
                SelText = SS1.options[SS1.options.length - 1 - i].text;
                var newRow = new Option(SelText, SelID);
                SS2.options[SS2.length] = newRow;
                SS1.options[SS1.options.length - 1 - i] = null;
                arrayNewGtin.push(SelID);
            }
        }
        $("#preloader").fadeIn();

        if(arrayNewGtin){
            $.ajax({
                type: "POST",
                url : "{{route('Business::relateProductDN@updateRelateProduct')}}",
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    gtin_code: '{{$gtin}}',
                    id : '{{$hookId}}',
                    gtin_codes : arrayNewGtin
                },
                success: function (data) {
                    jQuery("#preloader").fadeOut();
                },
                error: function (data) {
                    jQuery("#preloader").fadeOut();
                    var error = JSON.parse(data.responseText);
                    alert(error.error);
                    location.reload();

                }
            });

        }

//        SelectSort(SS2);
    }
    function SelectSort(SelList) {
        var ID = '';
        var Text = '';
        for (x = 0; x < SelList.length - 1; x++) {
            for (y = x + 1; y < SelList.length; y++) {
                if (SelList[x].text > SelList[y].text) {
                    // Swap rows
                    ID = SelList[x].value;
                    Text = SelList[x].text;
                    SelList[x].value = SelList[y].value;
                    SelList[x].text = SelList[y].text;
                    SelList[y].value = ID;
                    SelList[y].text = Text;
                }
            }
        }
    }
    function selectAll() {

        selectBox = document.getElementById("id_my_teams");

        for (var i = 0; i < selectBox.options.length; i++) {
            selectBox.options[i].selected = true;
        }
        console.log(selectBox);
    }
    $(document).ready(function () {



        $('#addProduct').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {
                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addProduct') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });

        $('#addProductCat').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {

                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addProductCategory') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });


        $('#addProducCatAll').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {

                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addProductCategoryAll') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });
        $('#addProductVendor').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {

                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addProductVendor') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });


        $('#addProducVendorAll').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {

                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addProductVendorAll') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });


        $('#addCat').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {

                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addCat') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });

        $('#addVendor').on('click', function (e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                var start_date = $('#start-date').val();
                var end_date = $('#end-date').val();

                if (start_date != null && end_date != null) {

                    $('#submit-form').attr('action', '{{ route('Staff::Management::relateProduct@addVendor') }}').submit();
                } else {
                    alert('Vui lòng chọn start date và end date');
                }

            }

        });

        $('#changeGtin').on('click', function (e) {

            var id_my_teams = document.getElementById("id_my_teams");
            for (var i = 0; i < id_my_teams.options.length; i++) {
                id_my_teams.options[i].selected = true;
            }
            var id_other_teams = document.getElementById("id_other_teams");
            for (var j = 0; j < id_other_teams.options.length; j++) {
                id_other_teams.options[j].selected = true;
            }

        });
    });


    //    function change_type() {
    //        $('#search-form').submit();
    //    }
</script>

@endpush

