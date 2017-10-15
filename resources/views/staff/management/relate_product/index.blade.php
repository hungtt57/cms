@extends('_layouts/staff')
@push('css_files_head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.45/css/bootstrap-datetimepicker.min.css"
      rel="stylesheet" type="text/css">
<style>
    .row {
        margin-left: 10px !important;
        margin-right: 10px !important;
    }
    #highlighted-justified-tab1 .row{
        margin-bottom:10px !important;
    }

    .table-change {
        border-width: 2px;
    }
    #search-form .table > tbody > tr > td, .table > tbody > tr > th,#search-form .table > tfoot > tr > td,#search-form .table > tfoot > tr > th,#search-form .table > thead > tr > td,#search-form .table > thead > tr > th {

            border: none !important;
    }
    .date{
        margin-top: -10px;
    }
    select[multiple]{
        height:400px !important;
    }
    #changeGtin{
        margin-top:10px;
        margtin-bottom:10px;
    }
</style>
@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Sản phẩm liên quan</h2>
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
    @elseif (session('danger'))
        <div class="alert bg-danger alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                        class="sr-only">Close</span></button>
            {{ session('danger') }}
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

                            <div class="col-md-6 col-md-offset-3" style="background: white;margin-bottom: 20px">

                                <table class="table table-responsive">
                                    <tr>
                                        <td> Switch :</td>
                                        <td><input type="text" id="gtin_code" name="gtin_code"
                                                   placeholder="Nhập gtin_code"
                                                   class="form-control" value="{{Request::input('gtin_code')}}"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>

                                            <select class="form-control"
                                                    {{--onchange="change_type()" --}}
                                                    name="type"
                                                    id="status-filter">
                                                <option value="0"
                                                        @if(Request::input('type')==0) selected="selected" @endif>Chọn
                                                    loại liên quan
                                                </option>
                                                <option value="1"
                                                        @if(Request::input('type')==1) selected="selected" @endif>Chọn
                                                    theo sản phẩm
                                                </option>
                                                <option value="2"
                                                        @if(Request::input('type')==2) selected="selected" @endif>Chọn
                                                    sản phẩm cùng category
                                                </option>
                                                <option value="3"
                                                        @if(Request::input('type')==3) selected="selected" @endif>Chọn
                                                    sản phẩm cùng vendor
                                                </option>
                                                <option value="4"
                                                        @if(Request::input('type')==4) selected="selected" @endif>Chọn
                                                    theo nhiều category
                                                </option>
                                                <option value="5"
                                                        @if(Request::input('type')==5) selected="selected" @endif>Chọn
                                                    theo nhiều vendor
                                                </option>
                                            </select>
                                        </td>
                                    </tr>



                                </table>


                                <!-- End of Search Form -->

                            </div>

                    <div class="row">
                        <div class="col-md-4 col-md-offset-8">
                            <input class="form-control" type="text" name="search" value="{{Request::input('search')}}"
                                   placeholder="Search theo tên và gtin"/>
                            <input type="hidden" name="page" id="page" value="">
                        </div>

                    </div>
                       <div class="row" style="margin-bottom:10px">
                           <div class="col-md-2 col-md-offset-3">
                               <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                       data-target="#edit-pro">Search
                               </button>
                           </div>
                       </div>


                    </div>
                    </form>
                    <div class="row" style="display: block">
                        <div class="tabbable">
                            <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                                <li class="active"><a href="#highlighted-justified-tab1" data-toggle="tab"
                                                      class="legitRipple" aria-expanded="true">Tìm kiếm</a></li>
                                <li class=""><a href="#highlighted-justified-tab2" data-toggle="tab" class="legitRipple"
                                                aria-expanded="false">Sản phẩm đã được set</a></li>
                                <li class=""><a href="#highlighted-justified-tab3" data-toggle="tab" class="legitRipple"
                                                aria-expanded="false">Xóa s/p được set</a></li>

                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="highlighted-justified-tab1">
                                    <form id="submit-form" method="POST">

                                        <div class="row">
                                            <input type="hidden" name="gtin_code"
                                                   value="{{Request::input('gtin_code')}}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            @if(isset($products))

                                                <div class="table-responsive">
                                                    <table  class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select-all"
                                                                       class="js-checkbox"/>
                                                            </th>

                                                            <th>Name</th>
                                                            <th>Gtin code</th>
                                                            <th>Image</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($products as $p)
                                                            <tr>
                                                                <td><input type="checkbox" name="selected[]" class="s"
                                                                           value="{{$p->id}}"></td>
                                                                <td>{{$p->product_name  }}</td>
                                                                <td>{{$p->gtin_code}}</td>
                                                                <td>
                                                                    @if($p->image_default)
                                                                        <img src="{{$p->image('thumb_small')}}" alt="">

                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach


                                                        </tbody>
                                                    </table>
                                                    <div class="row">
                                                        <div class="col-md-10 pull-right" style="text-align: right">
                                                            {!! $products->appends(Request::all())->links() !!}
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            @php
                                                                $start_date = \Carbon\Carbon::today()->format('d-m-Y');
                                                                $end_date = \Carbon\Carbon::today()->addYear()->format('d-m-Y');

                                                            @endphp
                                                            @if(Request::input('type'))
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                       <b>Thời gian liên quan</b>
                                                                    </div>
                                                                    <div class="col-md-10 ">
                                                                        <div class="col-md-1"><b>Start :</b></div>
                                                                        <div class="col-md-4 date"><input id="start-date" name="start-date"
                                                                                                     type="text" class="form-control"
                                                                                                     @if(old('start-date')) value="{{old('start-date')}}"
                                                                                                     @else value="{{$start_date}}" @endif></div>

                                                                        <div class="col-md-1"><b>End :</b></div>
                                                                        <div class="col-md-4 date"><input id="end-date" name="end-date"
                                                                                                     @if(old('end-date')) value="{{old('end-date')}}"
                                                                                                     @else value="{{$end_date}}"
                                                                                                     @endif  type="text" class="form-control">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button id="addProduct" class="btn btn-success btn-xs"
                                                                    data-toggle="modal"
                                                                    data-target="#edit-pro">Submit
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif

                                            @if(isset($product_categories))

                                                <div class="table-responsive">
                                                    <table  class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select-all"
                                                                       class="js-checkbox"/>
                                                            </th>

                                                            <th>Name</th>

                                                            <th>Gtin code</th>
                                                            <th>Image</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($product_categories as $pC)

                                                            <tr>
                                                                <td><input type="checkbox" name="selected[]" class="s"
                                                                           value="{{$pC->product->id}}"></td>
                                                                <td>{{$pC->product->product_name  }}</td>

                                                                <td>{{$pC->product->gtin_code}}</td>
                                                                <td>
                                                                    @if($pC->product->image_default)
                                                                        <img src="{{$pC->product->image('thumb_small')}}"
                                                                             alt="">

                                                                    @endif
                                                                </td>

                                                            </tr>


                                                        @endforeach
                                                        </tbody>
                                                    </table>



                                                    <div class="row">
                                                        <div class="col-md-10 pull-right" style="text-align: right">
                                                            {!! $product_categories->appends(Request::all())->links() !!}
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            @php
                                                                $start_date = \Carbon\Carbon::today()->format('d-m-Y');
                                                                $end_date = \Carbon\Carbon::today()->addYear()->format('d-m-Y');

                                                            @endphp
                                                            @if(Request::input('type'))
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <b>Thời gian liên quan</b>
                                                                    </div>
                                                                    <div class="col-md-10 ">
                                                                        <div class="col-md-1"><b>Start :</b></div>
                                                                        <div class="col-md-4 date"><input id="start-date" name="start-date"
                                                                                                     type="text" class="form-control"
                                                                                                     @if(old('start-date')) value="{{old('start-date')}}"
                                                                                                     @else value="{{$start_date}}" @endif></div>

                                                                        <div class="col-md-1"><b>End :</b></div>
                                                                        <div class="col-md-4 date"><input id="end-date" name="end-date"
                                                                                                     @if(old('end-date')) value="{{old('end-date')}}"
                                                                                                     @else value="{{$end_date}}"
                                                                                                     @endif  type="text" class="form-control">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="col-md-5">
                                                                <button id="addProductCat" class="btn btn-success btn-xs"
                                                                        data-toggle="modal"
                                                                        data-target="#edit-pro">Submit
                                                                </button>

                                                            </div>
                                                            <div class="col-md-5">
                                                                <button id="addProducCatAll" class="btn btn-success btn-xs"
                                                                        data-toggle="modal"
                                                                        data-target="#edit-pro">Submit All
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            @endif


                                            @if(isset($product_vendors))

                                                <div class="table-responsive">
                                                    <table  class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select-all"
                                                                       class="js-checkbox"/>
                                                            </th>

                                                            <th>Name</th>
                                                            <th>Gtin code</th>
                                                            <th>Image</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($product_vendors as $pV)

                                                            <tr>
                                                                <td><input type="checkbox" name="selected[]" class="s"
                                                                           value="{{$pV->id}}"></td>
                                                                <td>{{$pV->product_name  }}</td>
                                                                <td>{{$pV->gtin_code}}</td>
                                                                <td>
                                                                    @if($pV->image_default)
                                                                        <img src="{{$pV->image('thumb_small')}}" alt="">

                                                                    @endif
                                                                </td>

                                                            </tr>

                                                        @endforeach
                                                        </tbody>
                                                    </table>




                                                    <div class="row">
                                                        <div class="col-md-10 pull-right" style="text-align: right">
                                                            {!! $product_vendors->appends(Request::all())->links() !!}
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            @php
                                                                $start_date = \Carbon\Carbon::today()->format('d-m-Y');
                                                                $end_date = \Carbon\Carbon::today()->addYear()->format('d-m-Y');

                                                            @endphp
                                                            @if(Request::input('type'))
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <b>Thời gian liên quan</b>
                                                                    </div>
                                                                    <div class="col-md-10 ">
                                                                        <div class="col-md-1"><b>Start :</b></div>
                                                                        <div class="col-md-4 date"><input id="start-date" name="start-date"
                                                                                                     type="text" class="form-control"
                                                                                                     @if(old('start-date')) value="{{old('start-date')}}"
                                                                                                     @else value="{{$start_date}}" @endif></div>

                                                                        <div class="col-md-1"><b>End :</b></div>
                                                                        <div class="col-md-4 date"><input id="end-date" name="end-date"
                                                                                                     @if(old('end-date')) value="{{old('end-date')}}"
                                                                                                     @else value="{{$end_date}}"
                                                                                                     @endif  type="text" class="form-control">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="col-md-5">
                                                                <button id="addProductVendor" class="btn btn-success btn-xs"
                                                                        data-toggle="modal"
                                                                        data-target="#edit-pro">Submit
                                                                </button>

                                                            </div>
                                                            <div class="col-md-5">
                                                                <button id="addProducVendorAll" class="btn btn-success btn-xs"
                                                                        data-toggle="modal"
                                                                        data-target="#edit-pro">Submit All
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>



                                                </div>
                                            @endif

                                            @if(isset($categories))

                                                <div class="table-responsive">
                                                    <table  class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select-all"
                                                                       class="js-checkbox"/>
                                                            </th>
                                                            <th>Name</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>


                                                        @foreach($categories as $row)
                                                            @if($row->parent_id != 0)
                                                            <tr role="row" id="">

                                                                <td><input type="checkbox" name="selected[]" class="s"
                                                                           value="{{$row->id}}"></td>
                                                                <td>{{ str_repeat('------', $row->level) }}
                                                                    {{$row->name}}</td>


                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>



                                                    {{--<div class="row">--}}
                                                        {{--<div class="col-md-10 pull-right" style="text-align: right">--}}
                                                            {{--{!! $categories->appends(Request::all())->links() !!}--}}
                                                        {{--</div>--}}

                                                    {{--</div>--}}
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            @php
                                                                $start_date = \Carbon\Carbon::today()->format('d-m-Y');
                                                                $end_date = \Carbon\Carbon::today()->addYear()->format('d-m-Y');

                                                            @endphp
                                                            @if(Request::input('type'))
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <b>Thời gian liên quan</b>
                                                                    </div>
                                                                    <div class="col-md-10 ">
                                                                        <div class="col-md-1"><b>Start :</b></div>
                                                                        <div class="col-md-4 date"><input id="start-date" name="start-date"
                                                                                                     type="text" class="form-control"
                                                                                                     @if(old('start-date')) value="{{old('start-date')}}"
                                                                                                     @else value="{{$start_date}}" @endif></div>

                                                                        <div class="col-md-1"><b>End :</b></div>
                                                                        <div class="col-md-4 date"><input id="end-date" name="end-date"
                                                                                                     @if(old('end-date')) value="{{old('end-date')}}"
                                                                                                     @else value="{{$end_date}}"
                                                                                                     @endif  type="text" class="form-control">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" id="addCat"
                                                                    class="btn btn-success btn-xs" data-toggle="modal"
                                                                    data-target="#edit-pro">Submit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            @if(isset($vendors))

                                                <div class="table-responsive">
                                                    <table  class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select-all"
                                                                       class="js-checkbox"/>
                                                            </th>
                                                            <th>Name</th>
                                                            <th>Gln</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($vendors as $vendor)

                                                            <tr>
                                                                <td><input type="checkbox" name="selected[]" class="s"
                                                                           value="{{$vendor->id}}"></td>
                                                                <td>{{$vendor->name}}</td>
                                                                <td>{{$vendor->gln_code}}</td>

                                                            </tr>

                                                        @endforeach
                                                        </tbody>
                                                    </table>



                                                    <div class="row">
                                                    <div class="col-md-10 pull-right" style="text-align: right">
                                                        {!! $vendors->appends(Request::all())->links() !!}
                                                    </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            @php
                                                                $start_date = \Carbon\Carbon::today()->format('d-m-Y');
                                                                $end_date = \Carbon\Carbon::today()->addYear()->format('d-m-Y');

                                                            @endphp
                                                            @if(Request::input('type'))
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <b>Thời gian liên quan</b>
                                                                    </div>
                                                                    <div class="col-md-10 ">
                                                                        <div class="col-md-1"><b>Start :</b></div>
                                                                        <div class="col-md-4 date"><input id="start-date" name="start-date"
                                                                                                     type="text" class="form-control"
                                                                                                     @if(old('start-date')) value="{{old('start-date')}}"
                                                                                                     @else value="{{$start_date}}" @endif></div>

                                                                        <div class="col-md-1"><b>End :</b></div>
                                                                        <div class="col-md-4 date"><input id="end-date" name="end-date"
                                                                                                     @if(old('end-date')) value="{{old('end-date')}}"
                                                                                                     @else value="{{$end_date}}"
                                                                                                     @endif  type="text" class="form-control">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" id="addVendor"
                                                                    class="btn btn-success btn-xs" data-toggle="modal"
                                                                    data-target="#edit-pro">Submit
                                                            </button>
                                                        </div>
                                                    </div>


                                                </div>
                                            @endif

                                        </div>

                                    </form>
                                </div>

                                <div class="tab-pane" id="highlighted-justified-tab2">
                                    @if(isset($hook_product))
                                        <form action="{{route('Staff::Management::relateProduct@order',['id'=>$hookId])}}" method="POST">
                                            {{--<input type="hidden" name="gtin_code"--}}
                                                   {{--value="{{Request::input('gtin_code')}}">--}}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <select id="id_other_teams" class="form-control table-change"
                                                            name="other_teams[]"
                                                            MULTIPLE>
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
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
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
                                                    <select id="id_my_teams" name="my_teams[]"
                                                            class="table-change form-control"
                                                            MULTIPLE>

                                                    </select>
                                                </div>
                                            </div>
                                            {{--@if(count($pHook) > 1)--}}
                                                <div class="col-md-2 pull-right">
                                                    <button type="submit" id="changeGtin" class="btn btn-success btn-xs"
                                                            data-toggle="modal" onlick="selectAll()"
                                                            data-target="#edit-pro">Change
                                                    </button>

                                                </div>
                                            {{--@endif--}}

                                        </form>
                                    @endif

                                    @if(isset($hCategories))

                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($hCategories as $category)

                                                        <tr>
                                                            <td>{{$category->id}}</td>
                                                            <td>{{$category->name}}</td>
                                                        </tr>


                                                    @endforeach
                                                    </tbody>
                                                </table>


                                            </div>
                                        @endif



                                        @if(isset($hVendors))

                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Gln</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($hVendors as $vendor)

                                                        <tr>
                                                            <td>{{$vendor->id}}</td>
                                                            <td>{{$vendor->name}}</td>
                                                            <td>{{$vendor->gln_code}}</td>

                                                        </tr>

                                                    @endforeach
                                                    </tbody>
                                                </table>



                                            </div>
                                        @endif

                                </div>

                                <div class="tab-pane" id="highlighted-justified-tab3">
                                    @if(isset($hook_product))
                                    <form action="{{route('Staff::Management::relateProduct@deleteRelateProduct',['id' => $hookId])}}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="table-responsive">
                                        <table  class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th width="50%"><input type="checkbox" id="selectDelete"
                                                           class="js-checkbox"/>
                                                </th>

                                                <th>Name(gtin)</th>

                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($hook_product as $pH)
                                                <tr>
                                                    <td><input type="checkbox" name="selected[]" class="checkDelete"
                                                               value="{{$pH->product_id}}"></td>
                                                    <td>   @if(isset($pH->product))
                                                            {{$pH->product->product_name}}
                                                                ({{$pH->product->gtin_code}})
                                                        @else

                                                                {{$pH->product_id}}
                                                        @endif
                                                    </td>

                                                </tr>

                                            @endforeach




                                            </tbody>
                                        </table>



                                    </div>
                                        <div class="row" style="margin-top: 20px;margin-bottom: 20px;text-align: right;">
                                            <button  id="button-delete" class="btn btn-success btn-xs" >Xóa
                                            </button>
                                        </div>
                                    </form>
                                    @endif

                                        @if(isset($hCategories) or isset($hVendors))
                                            <form action="{{route('Staff::Management::relateProduct@deleteRelateAll',['id' => $hookId])}}" method="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="table-responsive">
                                                    <table  class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            {{--<th width="50%"><input type="checkbox" id="selectDelete"--}}
                                                                                   {{--class="js-checkbox"/>--}}
                                                            {{--</th>--}}

                                                            <th>Name</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(isset($hVendors))
                                                            @foreach($hVendors as $vendor)

                                                                <tr>
                                                                    <td>{{$vendor->name}}({{$vendor->gln_code}})</td>

                                                                </tr>

                                                            @endforeach
                                                        @endif
                                                        @if(isset($hCategories))
                                                            @foreach($hCategories as $category)
                                                                <tr>
                                                                    <td>{{$category->name}}</td>
                                                                </tr>


                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>



                                                </div>
                                                <div class="row" style="margin-top: 20px;margin-bottom: 20px;text-align: right;">
                                                    <button  id="button-delete" class="btn btn-success btn-xs" >Xóa
                                                    </button>
                                                </div>
                                            </form>

                                            @endif

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- /main content -->
        </div>

        <!-- /page content -->
    </div>
    <!-- /page container -->










@endsection

@push('js_files_foot')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.1/URI.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.2.5/highcharts.js"></script>
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
    $('#selectDelete').on('click', function () {
        $('.checkDelete').prop('checked', this.checked);
    });
    function SelectMoveRows(SS1, SS2) {
        var SelID = '';
        var SelText = '';
        // Move rows from SS1 to SS2 from bottom to top
        for (i = SS1.options.length - 1; i >= 0; i--) {
            if (SS1.options[i].selected == true) {
                SelID = SS1.options[i].value;
                SelText = SS1.options[i].text;
                var newRow = new Option(SelText, SelID);
                SS2.options[SS2.length] = newRow;
                SS1.options[i] = null;
            }
        }
        SelectSort(SS2);
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
        $('#button-delete').on('click',function(e){
            if(!confirm('Bạn có chắc chắn thực hiện hành động này?')) {
                e.preventDefault();
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

        $('#changeGtin').on('click',function(e){

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

