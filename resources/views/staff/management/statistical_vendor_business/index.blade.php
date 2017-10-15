@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>

                    Thống kê vendor</h2>
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
                @if(auth('staff')->user()->can('search-statistical-vendor-business') or auth()->guard('staff')->user()->can('statistical-vendor-business'))
                    <div class="row">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="search"
                                           placeholder="Search by gln_code or name"
                                           value="{{ Request::input('search') }}"/>

                                </div>

                                <div class="col-md-6">
                                    <select class="form-control" name="filter-business" id="role-filter">
                                        <option value="0">Tất cả</option>
                                        <option value="1" @if(Request::input('filter-business')== 1) selected @endif>Cty
                                            đã
                                            kí H/Đ
                                        </option>
                                        <option value="2" @if(Request::input('filter-business')== 2) selected @endif>Cty
                                            chưa kí H/Đ
                                        </option>
                                    </select>
                                </div>
                                <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                    data-target="#edit-pro">Search</button>

                             </span>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
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
                    @if($errorMessage)
                        <div class="alert bg-danger alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                            </button>
                            {{ $errorMessage}}
                        </div>
                    @endif

                <div class="panel panel-flat">
                    <div class="table-responsive">
                        <table class="table table-hover" oncopy="return false" oncut="return false" onpaste="return false">
                            <thead>
                            <tr>
                                {{--<th><input type="checkbox" id="select-all" class="js-checkbox" /></th>--}}
                                <th>Tên</th>
                                <th>GLN code</th>
                                <th>
                                    <a href="#" data-sort="scan" class="sortable
                                               @if(Request::input('sort_by') == 'scan' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'scan' and Request::input('order') == 'desc') active desc @endif">
                                        Scan <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a>

                                </th>
                                <th>
                                    <a href="#" data-sort="view" class="sortable
                                               @if(Request::input('sort_by') == 'view' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'view' and Request::input('order') == 'desc') active desc @endif">
                                        View <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a>
                                </th>
                                <th><a href="#" data-sort="like" class="sortable
                                               @if(Request::input('sort_by') == 'like' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'like' and Request::input('order') == 'desc') active desc @endif">
                                        Like <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a></th>
                                <th><a href="#" data-sort="comment" class="sortable
                                               @if(Request::input('sort_by') == 'comment' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'comment' and Request::input('order') == 'desc') active desc @endif">
                                        Comment <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a></th>
                                <th>
                                    <a href="#" data-sort="vote_good" class="sortable
                                               @if(Request::input('sort_by') == 'vote_good' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'vote_good' and Request::input('order') == 'desc') active desc @endif">
                                        Vote good <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a>
                                </th>
                                <th><a href="#" data-sort="vote_normal" class="sortable
                                               @if(Request::input('sort_by') == 'vote_normal' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'vote_normal' and Request::input('order') == 'desc') active desc @endif">
                                        Vote normal <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a>
                                </th>
                                <th>
                                    <a href="#" data-sort="vote_bad" class="sortable
                                               @if(Request::input('sort_by') == 'vote_bad' and Request::input('order') == 'asc') active asc @endif
                                    @if(Request::input('sort_by') == 'vote_bad' and Request::input('order') == 'desc') active desc @endif">
                                        Vote bad <i
                                                class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                                data-original-title="" title=""></i>
                                        <span class="sort-direction"></span>
                                    </a>
                                </th>

                                <th>Phone</th>
                                <th>Email</th>
                                <th>Cty đã kí hợp đồng</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($vendors)
                                @foreach ($vendors as $index => $vendor)
                                    <tr role="row" id="gln-{{ $vendor->id }}">
                                        <td>
                                            <a href="{{route('Staff::Management::statisticalVendorBusiness@productByVendor',['gln' => $vendor->gln_code])}}">{{ $vendor->name }}</a>
                                        </td>
                                        <td>{{$vendor->gln_code}}</td>
                                        <td>{{fakeData($vendor->scan)}}</td>
                                        <td>{{fakeData($vendor->view)}}</td>
                                        <td>{{$vendor->like}}</td>
                                        <td>{{$vendor->comment}}</td>
                                        <td>{{$vendor->vote_good}}</td>
                                        <td>{{$vendor->vote_normal}}</td>
                                        <td>{{$vendor->vote_bad}}</td>

                                        <td>
                                            @if($vendor->vendor)
                                                {{@$vendor->vendor->phone}}
                                            @endif

                                        </td>
                                        <td>
                                            @if($vendor->vendor)
                                                {{@$vendor->vendor->email}}
                                            @endif

                                        </td>
                                        <td>
                                            @if($vendor->gln)
                                                {{@$vendor->gln->business->name}}
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($vendors)
                    <div class="row" style="text-align: right">
                        {!! $vendors->appends(Request::all())->links() !!}
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.1/URI.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).keydown(function(event){
        if(event.keyCode==123){
            return false;
        }
        else if(event.ctrlKey && event.shiftKey && event.keyCode==73){
            return false;  //Prevent from ctrl+shift+i
        }
    });
    // disabled mouse right
    window.oncontextmenu = function () {
        return false;
    }
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
    function change() {
        $('#form').submit();
    }
    $('.sortable').click(function () {
        var uri = URI(window.location.href);
        var order = 'desc';
        var sort = $(this).attr('data-sort');
        if ($(this).hasClass('desc')) {
            order = 'asc';
        } else {
            order = 'desc';
        }
        uri.setQuery({
            'sort_by': sort,
            'order': order
        });
        window.location.href = uri.toString();
    });
    $(".js-checkbox").uniform({radioClass: "choice"});
</script>
@endpush



