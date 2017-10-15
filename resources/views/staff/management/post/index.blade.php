@extends('_layouts/staff')

@section('content')
  <link href="{{ asset('/assets/css/any-time.css') }}" rel="stylesheet" type="text/css">
  <style>
    .label-div .form-control{
      border:none !important;
    }
  </style>
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>News</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="{{ route('Staff::Management::post@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Tin tức</a>

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
        <div class="col-md-6 col-md-offset-3">

          <!-- Search Form -->
          <form role="form">

            <!-- Search Field -->
            <div class="row">
              <div class="form-group">
                <div class="input-group">
                  <input class="form-control" type="text" name="search" placeholder="Search" required/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>

                  </span>
                </div>
              </div>
            </div>

          </form>
          <!-- End of Search Form -->

        </div>
      </div>
      @if (session('success'))
        <div class="alert bg-success alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          {{ session('success') }}
        </div>
      @endif
      @if (session('error'))
        <div class="alert bg-danger alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          {{ session('error') }}
        </div>
      @endif
      <div class="panel panel-flat">
          <table class="table table-hover">
            <thead>
            <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Like</th>
              <th>Comment</th>
              <th>Image</th>
              <th>Source</th>
              <th>Tag</th>
              <th>Created_At</th>
              <th>Publish By</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $row)
              <tr role="row" id="">
                <td>{{$row->title}}({{$row->id}})</td>
                <td>{{$row->description}}</td>
                <td>{{@$row->postIcheck->like_count}}</td>
                <td><a href="{{route('Staff::Management::post@comments',[$row->id])}}">{{@$row->postIcheck->comment_count}}</a></td>
                <td><img src="{{ get_image_url($row->image, 'thumb_small')}}" width="50"></td>
                <td>{{$row->source}}</td>
                <td>{{$row->tag}}</td>
                <td>{{$row->created_at}}</td>
                @php     $list_accounts = config('listIcheckId'); @endphp
                <td>@foreach($list_accounts as $key => $name)
                      @if($row->publishBy == $key)
                      {{$name}}
                        @endif

                  @endforeach
               </td>
                <td><a href="{{ route('Staff::Management::post@edit', [$row->id]) }}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a>
                @if (!$row->icheck_id and $row->publishTime == 0)
                    <a href="#" data-toggle="modal" data-target="#approve-modal" data-id="{{ $row->id }}"
                       class="btn btn-success btn-xs"
                       data-approve-url="{{ route('Staff::Management::post@approve', ['id' => $row->id]) }}">
                      <i class="icon-blocked"></i>Approve</a>
                @else
                {{--<a onclick="return approveCat2();" href="{{ route('Staff::Management::post@renew', [$row->id]) }}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-remove"></span> Renew</a>--}}
                @endif
                <a onclick="return xoaCat();" href="{{ route('Staff::Management::post@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a>
                <a  href="{{route('Staff::Management::post@comments',[$row->id])}}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-remove"></span> Comment</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>


      </div>
    </div>
    <!-- /main content -->
  </div>
  <div class="row">

    <div style="float:right;"> {!! $posts->appends(Request::all())->links() !!}</div>
  </div>

  <div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>
        <form method="POST" id="form-edit"
        >
        <div class="modal-body">
          Bạn muốn Approve tin này?

            {{ csrf_field() }}


          <div class="row ">
            <div class="form-group">
              <div class="col-xs-4 label-div">
                <p class="form-control cursor-pointer">Chọn tài khoản</p>
              </div>

              <div class="col-xs-8">
                <select name="icheck_id" class="form-control js-edit" >
                  @foreach ($list_accounts as $key => $value)

                    <option value="{{$key}}">{{$value}}</option>

                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row ">
            <div class="form-group">
              <div class="col-xs-4 label-div">
                <p class="form-control cursor-pointer">Pushlish Now</p>
              </div>

              <div class="col-xs-8">
                <input type="checkbox" id="checkbox-all" name="publishNow" checked value="1" class="js-checkbox" />
              </div>
            </div>
          </div>
          <div class="row ">
            <div class="form-group">
              <div class="col-xs-4 label-div">
                <p class="form-control cursor-pointer">Chọn ngày giờ publish </p>
              </div>

              <div class="col-xs-8">
                {{--<input type="text" id="end-date" name="publishTime" class="form-control" value="" />--}}
                <input type="text" class="form-control" id="anytime-both" name="publishTime">
              </div>
            </div>
          </div>

            <input type="hidden" class="" name="business_id" value="{{Request::get('business_id')}}">
            <input type="hidden" name="product_id" class="product_id">
            <input type="hidden" name="_method" value="POST">


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
          <button type="submit" class="btn btn-danger" id="submit-edit">Xác nhận</button>

        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- /page content -->
</div>
<!-- /page container -->


@endsection
@push('js_files_foot')

<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>

@endpush
@push('scripts_foot')
<script>
  $('#end-date').pickadate({
    format: 'yyyy-mm-dd'
  });
  $("#anytime-both").AnyTime_picker({
    format: "%H:%i %d-%m-%Y",
  });
  function xoaCat(){
    var conf = confirm("Bạn chắc chắn muốn xoá?");
    return conf;
  }
  function approveCat(){
    var conf = confirm("Bạn chắc chắn muốn post tin nay?");
    return conf;
  }
  function approveCat2(){
    var conf = confirm("Bạn chắc chắn muốn làm mới tin nay?");
    return conf;
  }
  $(".js-checkbox").uniform({ radioClass: "choice" });
$('.js-edit').select2();
  $('#approve-modal').on('show.bs.modal', function (event) {

    var $btn = $(event.relatedTarget),
            $modal = $(this);

    $modal.find('form').attr('action', $btn.data('approve-url'));

  });

</script>
@endpush

@push('scripts_ck')
  <script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
@endpush
