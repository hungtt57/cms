@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>   <a href="{{ route('Staff::Management::statisticalVendorBusiness@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Sản phẩm  </h2>
            </div>

            <div class="heading-elements">

                <div class="heading-btn-group">
                    {{--<a href="{{ route('Staff::Management::gln@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm GLN</a>--}}
                    {{--<a href="#" class="btn btn-link disabled"><i class="icon-trash"></i> Xoá</a>--}}
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <div class="col-md-4 col-md-offset-4" style="">
            <form action="" id="form">

                {{--<div class="row">--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="input-group">--}}
                            {{--<input class="form-control" type="text" name="search" placeholder="Search by gln_code or name" value="{{ Request::input('search') }}" />--}}
                            {{--<span class="input-group-btn">--}}
                            {{--<button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>--}}

                  {{--</span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </form>
        </div>
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="panel panel-flat">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>

                                <th>Product Name</th>
                                <th>Gtin code</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Scan</th>
                                {{--<th>Scan barcode Việt</th>--}}
                                <th>Comment</th>
                                <th>View</th>
                                <th>Like</th>
                                <th>Vote good</th>
                                <th>Vote normal</th>
                                <th>Vote bad</th>

                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($products)
                            @foreach ($products as $index => $product)
                                <tr role="row" id="gln-{{ $product->id }}">
                                    <td>{{$product->product_name}}</td>
                                    <td>{{$product->gtin_code}}</td>
                                    <td>
                                        @if($product->images)
                                            @foreach($product->images as $image)
                                                <img src="{{$image}}" width="50"/>
                                            @endforeach

                                        @endif
                                    </td>
                                    <td>{{$product->price_default}} {{$product->currency->symbol}} </td>
                                    <td>{{fakeData($product->scan_count)}}</td>
                                    {{--<td>@if($product->MSMVGTIN)--}}
                                            {{--{{$product->MSMVGTIN->view_count}}--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    <td>
                                        @if($product->comment_count > 0)
                                        <a href="{{route('Staff::Management::statisticalVendorBusiness@commentByVendor',['gtin' => $product->gtin_code])}}">{{$product->comment_count}}</a>
                                            @else
                                            {{$product->comment_count}}
                                        @endif
                                    </td>
                                    <td>  {{fakeData($product->view_count)}}</td>
                                    <td>{{$product->like_count}}</td>
                                    <td>{{$product->vote_good_count}}</td>
                                    <td>{{$product->vote_normal_count}}</td>
                                    <td>{{$product->vote_bad_count}}</td>

                                    <td>
                                        {{--<div class="dropdown">--}}
                                        {{--<button id="gln-{{ $number->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">--}}
                                        {{--<i class="icon-more2"></i>--}}
                                        {{--</button>--}}
                                        {{--<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="gln-{{ $number->id }}-actions">--}}
                                        {{--<li><a href="#" data-toggle="modal" data-target="#approve-modal" data-gln="{{ $number->gln }}" data-approve-url="{{ route('Staff::Management::gln@approve', [$number->id]) }}"><i class="icon-checkmark-circle2"></i> Chấp nhận</a></li>--}}
                                        {{--<li><a href="{{ route('Staff::Management::gln@edit', [$number->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>--}}
                                        {{--<li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $number->name }}" data-delete-url="{{ route('Staff::Management::gln@delete', [$number->id]) }}"><i class="icon-trash"></i> Xoá</a></li>--}}
                                        {{--</ul>--}}
                                        {{--</div>--}}
                                    </td>
                                </tr>
                            @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                    @if($products)
                <div class="row" style="text-align: right">
                    {!! $products->appends(Request::all())->links() !!}
                </div>
                        @endif
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

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
        $modal.find('.js-gln-name').text($btn.data('name'));
    });

    $('#approve-modal').on('show.bs.modal', function (event) {
        var $btn = $(event.relatedTarget),
                $modal = $(this);

        $modal.find('form').attr('action', $btn.data('approve-url'));
        $modal.find('.js-product-name').text($btn.data('name'));
    });
    function change(){
        $('#form').submit();
    }
    $(".js-checkbox").uniform({ radioClass: "choice" });
</script>
@endpush



