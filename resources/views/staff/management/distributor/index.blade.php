@extends('_layouts/staff')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Distributor</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="{{ route('Staff::Management::distributor@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm </a>

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
      <div class="panel panel-flat">
          <table class="table table-hover">
            <thead>
            <tr>
              <th>Name</th>
              <th>Address</th>
              <th>Country</th>
              <th>Contact</th>
              <th>Other</th>
              <th>Title</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($distributors as $row)
              <tr role="row" id="">
                <td><input type="text" class="form-control pname editable"  data-url="{{route('Staff::Management::distributor@distributorInline', [$row->id])}}" data-id="{{$row->id}}" data-attr="name" value="{{ $row->name }}" />(<a href="{{ route('Staff::Management::product2@listByDistributor', [$row->id]) }}">Xem sản phẩm</a>)</td>
                <td><input type="text" class="form-control pname editable"  data-url="{{route('Staff::Management::distributor@distributorInline', [$row->id])}}" data-id="{{$row->id}}" data-attr="address" value="{{ $row->address }}" /></td>
                <td>{{$row->country()->first()->name}}</td>
                <td><input type="text" class="form-control pname editable"  data-url="{{route('Staff::Management::distributor@distributorInline', [$row->id])}}" data-id="{{$row->id}}" data-attr="contact" value="{{ $row->contact }}" /></td>
                <td><input type="text" class="form-control pname editable"  data-url="{{route('Staff::Management::distributor@distributorInline', [$row->id])}}" data-id="{{$row->id}}" data-attr="other" value="{{ $row->other }}" /></td>
                <td>{{$row->title()->first()->title}}</td>
                <td>{{$row->status}}</td>
                <td><a href="{{ route('Staff::Management::distributor@edit', [$row->id]) }}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a> <a onclick="return approveCat();" href="{{ route('Staff::Management::distributor@approve', [$row->id]) }}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-remove"></span> Approve</a> <a onclick="return xoaCat();" href="{{ route('Staff::Management::distributor@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>

      </div>
    </div>
    <!-- /main content -->
  </div>

            {!! $distributors->appends(Request::all())->links() !!}
  <!-- /page content -->
</div>
<!-- /page container -->


@endsection

@push('scripts_foot')
<script>
  function xoaCat(){
    var conf = confirm("Bạn chắc chắn muốn xoá?");
    return conf;
  }
  function approveCat(){
    var conf = confirm("Bạn chắc chắn muốn post tin nay?");
    return conf;
  }

  var oldData = {};
  $(document).on('focus', '.editable', function () {
    var $this = $(this);
    var id = $this.data('id');
    var attr = $this.data('attr');
    var old= $this.val();

    if (!oldData[id]) {
      oldData[id] = {};
    }

    oldData[id][attr] = old;
  });

  $(document).on('blur', '.editable', function () {
    var $this = $(this);
    var id = $this.data('id');
    var attr = $this.data('attr');
    var url = $this.data('url');
    var newVal = $this.val();

    if (newVal !== oldData[id][attr]) {
      var data = {};

      if (attr === "address") {
        data = {
          "address": newVal
        };
      } else if (attr === "name") {
        data = {
          "name": newVal
        };
      } else if (attr === "contact") {
        data = {
          "contact": newVal
        };
      } else if (attr === "other") {
        data = {
          "other": newVal
        };
      }

      $.ajax({
        type: "PUT",
        url: url,
        headers: {
          'X-CSRF-Token': "{{ csrf_token() }}"
        },
        data: data,
        success: function () {
        },
        error: function () {
          alert('Lỗi, hãy thử lại sau');
        }
      });
    }
  });
</script>
@endpush

@push('scripts_ck')
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush
