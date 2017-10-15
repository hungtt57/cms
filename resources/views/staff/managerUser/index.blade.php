@extends('_layouts/staff')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Manager User</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    {{--<a href="{{route('Staff::Management::fake@add')}}" class="btn btn-link"><i class="icon-add"></i> Thêm thành viên</a>--}}

                </div>
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
                <div class="container">
                    <form role="form" id="form">
                        <div class="col-md-6 col-md-offset-3">

                            <!-- Search Form -->


                            <!-- Search Field -->
                            <div class="row">
                                <div class="form-group">

                                    <input class="form-control" type="text" name="search" value="{{$search}}"
                                           placeholder="Search theo tên"/>
                                    <input type="hidden" name="page" id="page" value="{{$page}}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="search_id" value="{{$search_id}}"
                                           placeholder="Search theo icheck_id"/>
                                </div>
                            </div>
                            <div class="row">

                                <select class="form-control" name="is_shop" id="status-filter">
                                    <option value="2" @if($is_shop == 2) selected @endif>Tất cả</option>
                                    <option value="1" @if($is_shop == 1) selected @endif>Là shop online</option>
                                    <option value="0" @if($is_shop == 0) selected @endif>Không là shop online</option>
                                </select>

                            </div>
                            <!-- End of Search Form -->

                        </div>
                    </form>
                        <span class="input-group-btn">
                                    <button  id="submit" class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#edit-pro">Search</button>
                                    </span>

                </div>
                @if (session('success'))
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @elseif (session('danger'))
                    <div class="alert bg-danger alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {{ session('danger') }}
                    </div>
                @endif

                <div class="" style="margin-bottom:10px">

                    <ul class="pager text-right">
                        @if($page > 1)
                            <li>
                                <button type="button" id="previous" class="legitRipple btn btn-success btn-xs">←
                                    Previous
                                </button>
                            </li>
                        @endif
                        @if(count($users) > 0)
                        <li>
                            <button type="button" id="next" class="legitRipple btn btn-success btn-xs">Next →</button>
                        </li>
                            @endif
                    </ul>

                </div>

                <div class="panel panel-flat">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>

                                <th>Icheck_id</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Point</th>
                                <th>Is_virtual</th>
                                <th>Follow Count</th>
                                <th>Rank_id</th>
                                <th>Type</th>
                                <th>Shop Online</th>
                                <th>Block</th>
                                <th>updated_At</th>

                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $row)

                                <tr role="row" id="">

                                    <td>{{$row->icheck_id}}</td>
                                    <td>
                                        @if(isset($row->name))
                                            {{$row->name}}
                                        @else
                                            {{$row->social_name}}

                                        @endif
                                    </td>
                                    <td><img width="50" src="{{ get_image_url($row->avatar, 'thumb_small')}}"></td>
                                    <td>{{$row->point}}</td>
                                    <td>
                                        @if(isset($row->is_virtual) && ($row->is_virtual == 1 || $row->is_virtual))
                                            Yes
                                        @else
                                            No
                                        @endif

                                    </td>
                                    <td>{{$row->follower_count}}</td>
                                    <td>@if(isset($row->rank_id)) {{$row->rank_id}} @endif</td>
                                    <td>@if(isset($row->type))
                                            @if($row->type==1)
                                                Facebook
                                            @else
                                                Device
                                            @endif
                                        @else
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($row->is_shop) and ($row->is_shop == 1 || $row->is_shop))ty
                                            Yes
                                        @else
                                            No
                                        @endif

                                    </td>

                                    <td>
                                        @if(isset($row->status))
                                            @if($row->status==0)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        @else
                                        @endif
                                    </td>
                                    <td>@if($row->updatedAt!=null)
                                        {{date('d/m/Y H:i:s',strtotime($row->updatedAt))}}
                                        @else
                                        @endif
                                    </td>
                                    <td>

                                        @if($row->is_shop==1)
                                            @if(auth()->guard('staff')->user()->can('managerUser-import'))
                                            <a href="#" class="btn btn-link" data-toggle="modal"
                                               data-target="#modal{{$row->id}}">
                                                <button type="button" class="btn btn-success btn-xs"><i
                                                            class="icon-plus-circle"></i>Thêm file
                                                </button>
                                            </a>
                                            @endif
                                        @endif

                                        @if(isset($row->status))
                                            @if($row->status==1)
                                                <a class="block" href="{{route('Staff::managerUser@block',['id'=> $row->id])}}">
                                                    <button type="button" class="btn btn-info btn-xs">Block
                                                    </button>
                                                </a>
                                            @else
                                                <a class="block" href="{{route('Staff::managerUser@block',['id'=> $row->id])}}">
                                                    <button type="button" class="btn btn-info btn-xs">UnBlock
                                                    </button>
                                                </a>
                                            @endif
                                        @endif
                                            @if(auth()->guard('staff')->user()->can('managerUser-verify'))
                                                    @if($row->is_verify==1)
                                                            <a class="block" href="{{route('Staff::managerUser@verify',['id'=> $row->id])}}">
                                                                <button type="button" class="btn btn-warning btn-xs">Hủy Xác thực
                                                                </button>
                                                            </a>
                                                        @else
                                                            <a class="block" href="{{route('Staff::managerUser@verify',['id'=> $row->id])}}">
                                                                <button type="button" class="btn btn-warning btn-xs">xác thực
                                                                </button>
                                                            </a>
                                                    @endif
                                            @endif
                                    </td>

                                </tr>

                                <div class="modal fade" id="modal{{$row->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="import-modal2-label" data-backdrop="static">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form method="POST"
                                                  action="{{ route('Staff::managerUser@import', ['icheck_id' => $row->icheck_id]) }}"
                                                  enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="import-modal2-label">Nhập nhiều sản phẩm
                                                        từ file Excel</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="reason" class="control-label text-semibold">Tệp
                                                            tin</label>
                                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                                           data-content="Lý do từ chối đơn đăng ký cảu Sản phẩm"></i>
                                                        <input id="reason" type="file" name="file">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    {{ csrf_field() }}
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Huỷ bỏ
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Nhập</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>



                            @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
            <!-- /main content -->
        </div>
    {{--<div style="float:right;">{!!$users->appends(['search' => $search])->render()!!}</div>--}}
    <!-- /page content -->
    </div>
    <!-- /page container -->




@endsection

@push('scripts_foot')
<script>
    $('#previous').click(function () {
        var p = parseInt($('#page').val());
        p = p -1;
        $('#page').val(p);
        $('#form').submit();
    });
    $('#next').click(function () {
        var p = parseInt($('#page').val());
        p = p+1;
        $('#page').val(p);
        $('#form').submit();

    });
    $('#submit').click(function(e){
        e.preventDefault();
        $('#page').val(1);
        console.log(1);
        $('#form').submit();
    });
    $('.block').click(function(e){
        console.log(1);

        if(!confirm('Bạn có chắc muốn ?')){
            e.preventDefault();
        }else{

        }
    });
</script>
@endpush

@push('scripts_ck')
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush