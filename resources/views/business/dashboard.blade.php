@extends('_layouts/default')

@section('content')
<style>
@keyframes yellow-fade {
  0% {
    background: #ffffb3;
    max-height: 0;
  }
  70% {
    max-height: 9999px;
  }
  100% {
    background: inherit;
  }
}

@keyframes green-fade {
  0% {
    background: #86cc69;
    max-height: 0;
  }
  70% {
    max-height: 9999px;
  }
  100% {
    background: inherit;
  }
}

@keyframes red-fade {
  0% {
    background: #ffd6cc;
    max-height: 0;
  }
  70% {
    max-height: 9999px;
  }
  100% {
    background: inherit;
  }
}

.highlight {
  overflow: hidden;
  -webkit-animation: yellow-fade 2s ease-in 1;
  animation: yellow-fade 2s ease-in 1;
}
.green-highlight {
  -webkit-animation: green-fade 2s ease-in 1;
  animation: green-fade 2s ease-in 1;
}
.red-highlight {
  -webkit-animation: red-fade 2s ease-in 1;
  animation: red-fade 2s ease-in 1;
}
</style>

  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>
          Tổng quan
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
          <div class="col-md-6 col-md-offset-3">

            <!-- Search Form -->
            <form role="form">

              <!-- Search Field -->
              <div class="row">
                <div class="form-group">

                </div>
              </div>

            </form>
            <!-- End of Search Form -->

          </div>
        </div>


        <div class="row">

        </div>



        <!-- /quick stats boxes -->
      </div>

      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection

@push('js_files_foot')


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.1/URI.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.2.5/highcharts.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
@endpush

@push('scripts_foot')


@endpush
