@extends('_layouts/staff')

@section('content')
    <style>
        .result-xpath {
            list-style: none;
        }
        .result-xpath li {
            color:blue;
            padding:5px;
        }
        #errorXpathUrl{
            color: red;
            font-weight: bold;
        }
        #errorAcceptedRegex{
            color: red;
            font-weight: bold;
        }
        #errorDetailRegex{
            color: red;
            font-weight: bold;
        }

    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="{{ route('Staff::Craw::website@index') }}" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    {{ isset($website) ? 'Sửa website ' . $website->name : 'Thêm website' }}
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
                <form method="POST" enctype="multipart/form-data"
                      action="{{ isset($website) ? route('Staff::Craw::website@update', [$website->id]) : route('Staff::Craw::website@store') }}">
                    {{ csrf_field() }}
                    @if (isset($website))
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                        <label for="product_name" class="control-label text-semibold">Tên</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="name" name="name" class="form-control"
                                               value="{{ (isset($website->name)) ? $website->name : old('name')}}"
                                               autocomplete='off'
                                        />

                                        @if ($errors->has('name'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('url') ? 'has-error has-feedback' : '' }}">
                                        <label for="product_name" class="control-label text-semibold">URL</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="url" name="url" class="form-control"
                                               autocomplete='off'
                                               value="{{ (isset($website->url)) ? $website->url : old('url')}}"/>

                                        @if ($errors->has('url'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('url') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('acceptedRegex') ? 'has-error has-feedback' : '' }}">
                                        <label for="acceptedRegex" class="control-label text-semibold">acceptedRegex</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="acceptedRegex" name="acceptedRegex" class="form-control"
                                               autocomplete='off'
                                               value="{{ (isset($website->acceptedRegex)) ? $website->acceptedRegex : old('acceptedRegex')}}"/>
                                        <div class="help-block" id="errorAcceptedRegex"></div>
                                        @if ($errors->has('acceptedRegex'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('acceptedRegex') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('ignoredRegex') ? 'has-error has-feedback' : '' }}">
                                        <label for="ignoredRegex" class="control-label text-semibold">ignoredRegex</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="ignoredRegex" name="ignoredRegex" class="form-control"
                                               autocomplete='off'
                                               value="{{ (isset($website->ignoredRegex)) ? $website->ignoredRegex : old('ignoredRegex')}}"/>

                                        @if ($errors->has('ignoredRegex'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('ignoredRegex') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('detailRegex') ? 'has-error has-feedback' : '' }}">
                                        <label for="detailRegex" class="control-label text-semibold">detailRegex</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="detailRegex" name="detailRegex" class="form-control"
                                               autocomplete='off'
                                               value="{{ (isset($website->detailRegex)) ? $website->detailRegex : old('detailRegex')}}"/>
                                        <div class="help-block" id="errorDetailRegex"></div>
                                        @if ($errors->has('detailRegex'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('detailRegex') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('delayTime') ? 'has-error has-feedback' : '' }}">
                                        <label for="delayTime" class="control-label text-semibold">delayTime</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="number" id="delayTime" name="delayTime" class="form-control"
                                               autocomplete='off'
                                               value="{{ (isset($website->delayTime)) ? $website->delayTime : old('delayTime')}}"/>

                                        @if ($errors->has('delayTime'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('delayTime') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('xpathUrl') ? 'has-error has-feedback' : '' }}">
                                        <label for="product_name" class="control-label text-semibold">Url sản phẩm check xpath</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <input type="text" id="xpathUrl" name="xpathUrl" class="form-control"
                                               autocomplete='off'
                                               value="{{ (isset($website->xpathUrl)) ? $website->xpathUrl : old('xpathUrl')}}"
                                        />
                                        <div class="help-block" id="errorXpathUrl"></div>
                                        @if ($errors->has('xpathUrl'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathUrl') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('xpathName') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathName" class="control-label text-semibold">xpathName</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathName" id="xpathName" autocomplete=“off” class="form-control checkXpath">{{(isset($website->xpathName)) ? $website->xpathName : old('xpathName')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>

                                        @if ($errors->has('xpathName'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathName') }}</div>
                                        @endif
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-body">

                                    <div class="form-group {{ $errors->has('xpathPrice') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathPrice" class="control-label text-semibold">xpathPrice</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathPrice" id="xpathPrice" class="form-control checkXpath">{{(isset($website->xpathPrice)) ? $website->xpathPrice : old('xpathPrice')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>

                                    @if ($errors->has('xpathPrice'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathPrice') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('xpathImage') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathImage" class="control-label text-semibold">xpathImage</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathImage" id="xpathImage" class="checkXpath form-control">{{(isset($website->xpathImage)) ? $website->xpathImage : old('xpathImage')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>
                                        @if ($errors->has('xpathImage'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathImage') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('xpathKeyword') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathKeyword" class="control-label text-semibold">xpathKeyword</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathKeyword" id="xpathKeyword" class="form-control checkXpath">{{(isset($website->xpathKeyword)) ? $website->xpathKeyword : old('xpathKeyword')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>
                                        @if ($errors->has('xpathKeyword'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathKeyword') }}</div>
                                        @endif
                                    </div>


                                    <div class="form-group {{ $errors->has('xpathDescription') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathDescription" class="control-label text-semibold">xpathDescription</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathDescription" id="xpathDescription" class="form-control checkXpath">{{(isset($website->xpathDescription)) ? $website->xpathDescription : old('xpathDescription')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>
                                        @if ($errors->has('xpathDescription'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathDescription') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('xpathParameter') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathParameter" class="control-label text-semibold">xpathParameter</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathParameter" id="xpathParameter" class="form-control checkXpath">{{(isset($website->xpathParameter)) ? $website->xpathParameter : old('xpathParameter')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>
                                        @if ($errors->has('xpathParameter'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathParameter') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('xpathBarCode') ? 'has-error has-feedback' : '' }}">
                                        <label for="xpathParameter" class="control-label text-semibold">xpathBarCode</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon"
                                           data-content="Tên của sản phẩm"></i>
                                        <textarea name="xpathBarCode" id="xpathBarCode" class="form-control checkXpath">{{(isset($website->xpathBarCode)) ? $website->xpathBarCode : old('xpathBarCode')}}</textarea>
                                        <ul class="result-xpath">

                                        </ul>
                                        @if ($errors->has('xpathBarCode'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('xpathBarCode') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('isActive') ? 'has-error has-feedback' : '' }}">
                                        <label for="isActive" class="control-label text-semibold">isActive</label>
                                        <i class="icon-help text-muted text-size-mini cursor-pointer js-help-icon" data-content="currency của sản phẩm"></i>
                                        <select id="isActive" name="isActive"  class="js-select">
                                                <option @if(isset($website) and $website->isActive == 1) selected="selected"  @endif value="1">Active</option>
                                                <option @if(isset($website) and $website->isActive == 0) selected="selected"  @endif value="0">UnActive</option>
                                        </select>
                                        @if ($errors->has('isActive'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('isActive') }}</div>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <button type="submit"
                                                class="btn btn-primary">{{ isset($website) ? 'Cập nhật' : 'Thêm mới' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script src="//cdn.ckeditor.com/4.5.10/full/ckeditor.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/media/fancybox.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/barcoder.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
    $(document).ready(function () {

        $('.btn-xoa').click(function (e) {
            if (confirm('Bạn có chắn chắn XÓA SẢN PHẨM NÀY trên hệ thống, Hành động này sẽ KHÔNG THỂ hoàn tác!!')) {

            } else {
                e.preventDefault();
            }
        });
        $(".js-help-icon").popover({
            html: true,
            trigger: "hover",
            delay: {"hide": 1000}
        });


        // Initialize with options
        $(".js-select").select2();

        // Checkboxes, radios
        $(".js-radio").uniform({radioClass: "choice"});

        // File input
        $(".js-file").uniform({
            fileButtonClass: "action btn btn-default"
        });


        // Image lightbox
        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });

        $(document).on('click', '.btn-remove-image', function (e) {
            e.preventDefault();

            $(this).parents('.col-md-2').remove();
        });
        $('.checkXpath').blur(function(){
            var value = $(this).val();
            var parent = $(this).parent();
            var xpathUrl = $('#xpathUrl').val();
            if(!xpathUrl){
                $('#errorXpathUrl').html('');
                $('#errorXpathUrl').append('Vui lòng điền xpath URL');
            }
            if(value && xpathUrl){
                var url = '{{route('Staff::Craw::website@checkXpath')}}';
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    data: {
                        'xPath' : value,
                        'xPathUrl' : xpathUrl
                    },
                    dataType:'json',
                    success: function (data) {
                        if(data.data){
                            parent.find('.result-xpath').html('');
                            $.each(data.data,function(key,value){

                                var string = '<li>'+value+'</li>';
                                parent.find('.result-xpath').append(string);
                            });
                        }
                    },
                    error: function (error) {
                        var message = JSON.parse(error.responseText);
//                       if(message.message){
//                           alert(message.message);
//                       }

                    }
                });
            }
        });
        $('#acceptedRegex').blur(function(){
           var val = $(this).val();
            var valUrl =  $('#url').val();
            var patt = new RegExp(val);
            $('#errorAcceptedRegex').html('');
            if(patt.test(valUrl) == false){

                $('#errorAcceptedRegex').append('Url không khớp với acceptedRegex');
            }
        });
        $('#detailRegex').blur(function(){
            var val = $(this).val();
            var valUrl =  $('#xpathUrl').val();
            $('#errorDetailRegex').html('');
            try{
                var check = isRegex(val);
                if(check==false){
                    $('#errorDetailRegex').html('');
                    $('#errorDetailRegex').append('Không phải chuỗi regex');
                }else{
                    var patt = new RegExp(val);
                    if(patt.test(valUrl) == false){
                        $('#errorDetailRegex').html('');
                        $('#errorDetailRegex').append('DetailRegex không khớp với url sản phẩm');
                    }
                }

            }catch(e){
                $('#errorDetailRegex').html('');
                $('#errorDetailRegex').append('Không phải chuỗi regex');
            }

        });

        function isRegex(str) {
            return str != null ? !str.match(str) : false;
        }
    });


</script>
@endpush
