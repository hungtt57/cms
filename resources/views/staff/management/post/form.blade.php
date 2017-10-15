@extends('_layouts/staff')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          <a href="" class="btn btn-link">
            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($post) ? 'Sửa Tin tức ' : 'Thêm Tin tức' }}
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
        <div class="row">
          <div class="col-md-offset-2 col-md-8">
            @if (session('success'))
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                {{ session('success') }}
              </div>
            @endif
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="{{ isset($post) ? route('Staff::Management::post@update', [$post->id] ): route('Staff::Management::post@store') }}">
                  {{ csrf_field() }}
                  @if (isset($post))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- Title------------>
                  <div class="form-group {{ $errors->has('title') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Title</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') ?: @$post->title }}" />
                    @if ($errors->has('title'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('title') }}</div>
                    @endif
                  </div>
                  <!------------------ Description--------------->
                    <div class="form-group {{ $errors->has('description') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Description</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="description" name="description" class="form-control" value="{{ old('description') ?: @$post->description }}" />
                      @if ($errors->has('description'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('description') }}</div>
                      @endif
                    </div>
                  <!------------------------- Content-------------------->

                    <div class="form-group {{ $errors->has('content') ? 'has-error has-feedback' : '' }}">
                      <label for="contact-info" class="control-label text-semibold">Content</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thông tin để liên hệ với Doanh nghiệp. VD: Email, SĐT"></i>
                      <textarea id="editor1" name="content" rows="5" cols="5" class="form-control">{{ old('content') ?: @$post->content }}</textarea>
                      @if ($errors->has('content'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('content') }}</div>
                      @endif
                    </div>

                  <!----- Upload Image Here ---->
                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                      <div class="display-block">
                        <label class="control-label text-semibold">Logo</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Logo của Doanh nghiệp. Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb"></i>
                      </div>
                      <div class="media no-margin-top">
                        <div class="media-left">
                          <img src="{{ (isset($post) and $post->image) ? get_image_url($post->image, 'thumb_small') : asset('assets/images/image.png') }}" style="width: 64px; height: 64px;" alt="">
                        </div>
                        <div class="media-body">
                          <input type="file" name="image" class="js-file">
                          <span class="help-block no-margin-bottom">Chấp nhận các định dạng file: gif, png, jpg. Kích thước file tối đa là 2Mb</span>
                        </div>
                      </div>
                      @if ($errors->has('image'))
                        <div class="help-block">{{ $errors->first('image') }}</div>
                      @endif
                    </div>
                  <!-------------Source--------------->

                    <div class="form-group {{ $errors->has('source') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Source</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="source" name="source" class="form-control" value="{{ old('source') ?: @$post->source }}" />
                      @if ($errors->has('source'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('source') }}</div>
                      @endif
                    </div>
                  <!-------------Categories--------------->

                  <div class="form-group {{ $errors->has('categories') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Danh mục</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <select id="category-filter"
                            class="select-border-color border-warning js-categories-select"
                            name="categories[]"
                    multiple="multiple">
                      @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                        @if(isset($selectedCategories) and in_array($category->id,$selectedCategories))
                          selected
                        @endif
                        >{{ $category->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('categories'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('categories') }}</div>
                    @endif
                  </div>

                  <!---------------Tags----------------->
                    <div class="form-group {{ $errors->has('tag') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Tags</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="tag" name="tag" class="form-control" value="{{ old('tag') ?: @$post->tag }}" />
                      @if ($errors->has('tag'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('tag') }}</div>
                      @endif
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($post) ? 'Cập nhật' : 'Thêm mới' }}</button>
                    </div>
                  </form>
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
  <script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  $(".js-categories-select").select2({
    templateResult: function (item) {
      if (!item.id) {
        return item.text;
      }

      var originalOption = item.element,
              prefix = "----------".repeat(parseInt($(item.element).data('level'))),
              item = (prefix ? prefix + '| ' : '') + item.text;

      return item;
    },
    templateSelection: function (item) {
      return item.text;
    },
    escapeMarkup: function (m) {
      return m;
    },
    closeOnSelect: false,
    dropdownCssClass: 'border-primary',
    containerCssClass: 'border-primary text-primary-700'
  });
  // Replace the <textarea id="editor1"> with a CKEditor
  // instance, using default configuration.
  CKEDITOR.replace( 'editor1' );
</script>
@endpush
