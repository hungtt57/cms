@extends('_layouts/staff')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Top_Hot_Product</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="{{ route('Staff::Management::top_hot_product@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm</a>

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
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
            <tr>
              <th>Id</th>
              <th>Gtin</th>
              <th>Order</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($top_hot_products as $row)
              <tr role="row" id="">
                <td>{{$row->id}}</td>
                <td>{{$row->gtin}}</td>
                <td>{{$row->order}}</td>
                <td><a href="{{ route('Staff::Management::top_hot_product@edit', [$row->id]) }}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a> <a onclick="return xoaCat();" href="{{ route('Staff::Management::top_hot_product@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>

        </div>

      </div>
    </div>
    <!-- /main content -->
  </div>
  <div style="float:right;"><?php echo $top_hot_products->links(); ?></div>
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

@push('scripts_ck')
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush