@extends('_layouts/staff')

@section('content')
  <style>
    .form-row{
      margin-bottom: 20px;
    }
    label{
      padding: 8px;
    }
    .body-top50{
      overflow-x: hidden;
      height: 500px;
    }

    .error{
      color: red;
      font-weight: bold;
      margin-top: 10px;
    }
    #money{
      padding-left: 5px;
    }
    .border-error{
      border: 1px solid red !important;
    }

  </style>
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>Cộng tác viên</h2>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="{{ route('Staff::Management::collaborator@add') }}" class="btn btn-link"><i class="icon-add"></i> Thêm Cộng tác viên</a>
          <a href="#" data-toggle="modal" data-target="#change-group-modal" class="btn btn-link"><i class="icon-checkmark-circle2"></i> Chuyển group</a>
          <a href="#"  class="btn btn-link btn-delete"><i class="icon-trash"></i> Xoá</a>
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


        <form role="form">
          <div class="form-group">


          </div>
          <div class="form-group ">

            <input class="form-control" type="text" name="name" value="{{Request::get('name')}}" placeholder="Search tên " />
            <span class="input-group-btn"></span>

          </div>
          <div class="form-group">
            <label for="">Số dư</label>
            <select name="price" class="form-control">
              <option @if(Request::get('price') == '0') selected @endif value="0" >Tất cả</option>
              <option @if(Request::get('price') == 'asc') selected @endif value="asc" >Tăng dần</option>
              <option @if(Request::get('price') == 'desc') selected @endif value="desc" >Giảm dần</option>
            </select>
          </div>

          <div class="form-group">
            <label for="">Group</label>
            <select name="group" class="form-control ">
              <option value="0">Tất cả</option>
              @foreach ($groups as $group)
                <option  @if(Request::get('group') == $group->group_id) selected @endif value="{{ $group->group_id }}">{{ $group->group_id }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-success btn-xs">Search</button>

        </form>




        @if (session('error'))
          <div class="alert bg-danger alert-styled-left">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            {{ session('error') }}
          </div>
        @endif


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
              <th><input type="checkbox" id="select-all" class="js-checkbox" /></th>
              <th>Tên</th>
              <th>Email</th>
              <th>Trạng thái</th>
              <th>Số dư</th>
              <th>Ngày tạo</th>
              <th>Group</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <form id="main-form" action="{{route('Staff::Management::collaborator@deleteList')}}" method="POST">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="POST">
            @foreach ($collaborators as $index => $collaborator)
              <tr role="row" id="collaborator-{{ $collaborator->id }}">
                <td><input type="checkbox" name="selected[]" class="js-checkbox s" value="{{$collaborator->id}}" /></td>
                <td>{{ $collaborator->name }}</td>
                <td>{{$collaborator->email}}</td>
                <td>{{ $collaborator->statusText }}</td>
                <td>
                  {{ number_format($collaborator->balance) }}
                  <a href="#" data-toggle="modal" data-money="{{$collaborator->balance}}" data-target="#withdraw-money-modal" data-name="{{ $collaborator->name }}" data-withdraw-money-url="{{ route('Staff::Management::collaborator@withdrawMoney', [$collaborator->id]) }}"><i class="icon-trash"></i> Rút tiền</a>
                </td>
                <td>{{ $collaborator->created_at }}</td>
                <td>{{$collaborator->group}}</td>
                <td>
                  <div class="dropdown">
                    <button id="collaborator-{{ $collaborator->id }}-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
                      <i class="icon-more2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="collaborator-{{ $collaborator->id }}-actions">
                      <li><a href="{{ route('Staff::Management::collaborator@edit', [$collaborator->id]) }}"><i class="icon-pencil5"></i> Sửa</a></li>
                      <li><a href="#" data-toggle="modal" data-target="#delete-modal" data-name="{{ $collaborator->name }}" data-delete-url="{{ route('Staff::Management::collaborator@delete', [$collaborator->id]) }}"><i class="icon-trash"></i> Xoá</a></li>
                    </ul>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
            </form>
          </table>
        </div>
          <div class="row" style="text-align:right">
        {!! $collaborators->appends(Request::all())->links() !!}
          </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->

  <div class="modal fade" id="change-group-modal" tabindex="-1" role="dialog" aria-labelledby="batch-approve-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="batch-approve-modal-label">Chuyển group</h4>
        </div>
        <form action="{{ route('Staff::Management::collaborator@changeGroup') }}" class="js-batch-form" method="POST">
          <div class="modal-body">

            <div class="form-group">
              <label>Group :</label>
              <select class="form-control selecte-group" name="group">
                @foreach ($groups as $group)
                  <option value="{{ $group->group_id }}"{{ ((string) Request::input('group') === (string) $group->group_id) ? ' selected="selected"' : '' }}>{{ $group->group_id }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="POST">
            <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
            <button type="submit" id="submit-change-group" class="btn btn-danger">Xác nhận</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delete-modal-label">Xoá Sản phẩm</h4>
        </div>
        <div class="modal-body">
          Bạn có chắc chắn muốn xoá Sản phẩm <strong class="text-danger js-collaborator-name"></strong> khỏi hệ thống của iCheck?
        </div>
        <div class="modal-footer">
          <form method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
            <button type="submit" class="btn btn-danger">Xác nhận</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="withdraw-money-modal" tabindex="-1" role="dialog" aria-labelledby="withdraw-money-modal-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="withdraw-money-modal-label">Rút tiền của Cộng tác viên</h4>
        </div>
        <form method="POST">
          <div class="modal-body">
            <div class="row ">
              <div class="form-group">
                <div class="col-xs-3 label-div">
                  <label class="control-label cursor-pointer" >Số tiền muốn rút</label>
                </div>

                <div class="col-xs-9">
                  <input type="number" min="0" id="money" name="money" value="" class="form-control"  placeholder="Nhập số tiền muốn rút">
                  <p class="error hide" >Vui lòng nhập số điểm nhỏ hơn số điểm hiện có</p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="col-md-8">

            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ bỏ</button>
            </div>
            <div class="col-md-2">
              <button type="submit"  id="button-confirm"  class="btn btn-danger">Xác nhận</button>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>



@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  var money = 0;
  $('#money').keyup(function (e) {

      if(e.keyCode == 189){
        $(this).val(0);
      }
    if(parseInt($(this).val()) > money && parseInt($(this).val()) != 0){
      $(this).addClass('border-error');
      $('.error').addClass('show');
      $('.error').removeClass('hide');git
      $('#button-confirm').removeClass('show');
      $('#button-confirm').addClass('hide');
    }else{
      $(this).removeClass('border-error');
      $('.error').removeClass('show');
      $('.error').addClass('hide');
      $('#button-confirm').removeClass('hide');
      $('#button-confirm').addClass('show');
    }
    if(parseInt($(this).val()) == 0){
      $('#button-confirm').removeClass('show');
      $('#button-confirm').addClass('hide');
    }
  });

  $('#button-confirm').click(function(e){
    if($('#money').val() != 0){
      if(confirm("Bạn có chắc chắn muốn rút số tiền")){
        $('#update-form').submit();
      }else{
        e.preventDefault();
      }
    }else{
      alert('Vui lòng nhập số tiền khác 0');
      e.preventDefault();
    }


  });



  $(".js-help-icon").popover({
    html: true,
    trigger: "hover",
    delay: { "hide": 1000 }
  });
  $('.btn-delete').click(function(){
    if(confirm('Bạn có chắc chắn muốn xóa CTV?')){
        $('#main-form').submit();
    };
  });
  $('#select-all').on('click', function () {
    $('.s').prop('checked', this.checked);
  });

  $('#delete-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
            $modal = $(this);

    $modal.find('form').attr('action', $btn.data('delete-url'));
    $modal.find('.js-collaborator-name').text($btn.data('name'));
  });

  $('#withdraw-money-modal').on('show.bs.modal', function (event) {
    var $btn = $(event.relatedTarget),
            $modal = $(this);
    $('#money').val('');
    money =  $btn.data('money');
    $modal.find('form').attr('action', $btn.data('withdraw-money-url'));
    $modal.find('.js-collaborator-name').text($btn.data('name'));
  });
  $('.selecte-group').select2();

  $('.js-batch-form').on('submit', function (e) {
    var ids = [];

    $('[name^="selected[]"]:checked').each(function () {
      ids.push($(this).val());
    });

    var $input = $("<input>").attr({'type': 'hidden', 'name': 'ids'}).val(ids);
    $(this).append($input);
  });

</script>
@endpush



