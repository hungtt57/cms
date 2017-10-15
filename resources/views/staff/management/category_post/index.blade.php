@extends('_layouts/staff')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Category Post</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="{{ route('Staff::Management::categoryPost@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Category Post</a>

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
              <th>Tên</th>
              <th>Description</th>
              <th>Settings</th>
              <th>Keyword</th>
              <th>Ngày tạo</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $row)
              <tr role="row" id="">
                <td>{{$row->name}}</td>
                <td>{{$row->description}}</td>
                <td>{{$row->settings}}</td>
                <td>{{$row->keywords}}</td>
                <td>{{$row->createdAt}}</td>
                <td><a href="{{ route('Staff::Management::categoryPost@edit', [$row->id]) }}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a>
                  <a  onclick="return xoaCat();" href="{{ route('Staff::Management::categoryPost@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>

        </div>
    <div class="row">
      {!! $categories->appends(Request::all())->links() !!}
    </div>
      </div>
    </div>
    <!-- /main content -->
  </div>
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

</script>
@endpush