@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Bình luận
        </h2>
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

          <!-- Search Form -->
          <form role="form">
              <div class="form-group">
                <div class="input-group">
                  <input class="form-control" type="text" name="gtin" placeholder="Nhập GTIN để tìm comment hihi" required/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit-pro">Search</button>

                  </span>
                </div>
              </div>

          </form>
          <!-- End of Search Form -->

        <form method="POST" class="row" id="batch-form">
          {{ csrf_field() }}
          <div class="col-md-offset-3 col-md-6 pb-20">
            <button type="button" class="btn btn-primary" id="select-all">Chọn tất cả</button>
            <button type="button" class="btn btn-default" id="unselect-all">Bỏ Chọn tất cả</button>
            <button type="submit" class="btn btn-danger" id="delete-all">Xoá</button>
          </div>
          <div class="col-md-offset-3 col-md-6">

            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif

            @foreach ($comments as $comment)
              <div class="panel panel-flat border-left-xlg border-blue">
                <div class="panel-body">
                  <div class="media">
                    <div class="media-left">

                      @if(isset($comment->account()->account_id))
                        <img src="http://graph.facebook.com/{{ $comment->account()->account_id }}/picture"
                             class="img-circle" alt="">
                      @else
                        <img src="{{ asset('assets/images/image.png') }}"
                             class="img-circle" alt="">
                      @endif

                      <label><input type="checkbox" name="selected[]" class="s" value="{{ $comment->id }}"></label>
                    </div>

                    <div class="media-body">
                      <h6 class="media-heading"><strong class="js-actor-name">{{$comment->account()->name}}</strong></h6>
                      <p class="js-comment-content">{!! $comment->content !!}</p>
                      @if ($comment->image)
                      <img src="http://ucontent.icheck.vn/{{ $comment->image }}_original.jpg" class="img-responsive" />
                      @endif
                      <div class="media-annotation mt-5 js-action-time">{{ $comment->createdAt }}</div>

                      @foreach ($comment->childs() as $child)
                        <div class="media">
                          <div class="media-left">
                            @if(isset($child->account()->account_id))
                              <img src="http://graph.facebook.com/{{ $child->account()->account_id}}/picture"
                                   class="img-circle" alt="">
                            @else
                              <img src="{{ asset('assets/images/image.png') }}"
                                   class="img-circle" alt="">
                            @endif

                            <label><input type="checkbox" name="selected[]" class="s" value="{{ $child->id }}"></label>
                          </div>

                          <div class="media-body">
                            <h6 class="media-heading"><strong class="js-actor-name">{{ $child->account()->name }}</strong></h6>
                            <p class="js-comment-content">{!!  $child->content  !!}</p>
                            @if ($child->image)
                            <img src="http://ucontent.icheck.vn/{{ $child->image }}_original.jpg" class="img-responsive" />
                            @endif
                            <div class="media-annotation mt-5 js-action-time">{{ $child->createdAt }}</div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="col-md-offset-3 col-md-6">
            {!! $comments->appends(Request::all())->links() !!}
          </div>
        </form>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection

@push('js_files_foot')
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
  <script>
  $(document).ready(function () {
    $(".js-help-icon").popover({
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

    $('#batch-form').on('submit', function () {

      var conf = confirm("Bạn chắc chắn muốn thực hiện hành động này?");
      return conf;
    });

    $('#select-all').on('click', function () {
      $('.s').prop('checked', true);
    });

    $('#unselect-all').on('click', function () {
      $('.s').prop('checked', false);
    });

    // Initialize with options
    $(".js-categories-select").select2({
      templateResult: function (item) {
        if (!item.id) {
          return item.text;
        }

        var originalOption = item.element,
            prefix = "----------".repeat(parseInt($(item.element).data('level'))),
            item =  (prefix ? prefix + '| ' : '') + item.text;

        return item;
      },
      templateSelection: function (item) {
        return item.text;
      },
      escapeMarkup: function (m) {
        return m;
      },
      dropdownCssClass: 'border-primary',
      containerCssClass: 'border-primary text-primary-700'
    });

    // Initialize with options
    $(".js-select").select2();

    // Checkboxes, radios
    $(".js-radio").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
        fileButtonClass: "action btn btn-default"
    });

  });
  </script>
@endpush
