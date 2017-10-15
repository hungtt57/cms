@extends('_layouts/staff')

@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">

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
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div id="chart-per-day"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div class="panel panel-flat">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Site</th>
                                            <th>Product</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sites as $site)
                                            <tr>
                                                <td>{{$site->siteName}}</td>
                                                <td>{{$site->data->product}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
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


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.1/URI.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.2.5/highcharts.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
@endpush


@push('scripts_foot')
<script>





    $(document).ready(function () {
        var now = moment();

        Highcharts.setOptions({
            global : {
                useUTC : false
            }
        });



        var chart = new Highcharts.Chart({
            chart: {
                type: 'column',
                renderTo : 'chart-per-day'
            },
            title: {
                text: 'Các website đang chạy craw'
            },
            subtitle: {
//                text: 'Source: WorldClimate.com'
            },
            xAxis: {
                categories: [
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Số lần'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">site :  {point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    dataLabels: {
                        enabled: true,
                        crop: false,
                        overflow: 'none',
                        formatter:function() {
                            return this.point.y;
                        }
                    }
                }
            },
            series: []
        });
        getDataChart();


       function getDataChart(){
           var url = '{{route('Staff::Craw::website@getWebsiteInCraw')}}';
           $.ajax({
               method:"get",
               url: url,
               headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}'
               },
               dataType:'json',
               success: function (data) {

                   while( chart.series.length > 0 ) {
                       chart.series[0].remove( false );
                   }
//                   chart.redraw();
                   chart.xAxis[0].setCategories(data.sites);
                   if(data.product){
                       chart.addSeries({
                           'name':'Product',
                           data : data.product,
                           color: '#d24519'
                       });
                   }
                   if(data.queue){
                       chart.addSeries({
                           'name':'Queue',
                           data : data.queue,
                           color: '#20b350'
                       });
                   }
                   if(data.visited){
                       chart.addSeries({
                           'name':'Visited',
                           data : data.visited,
                           color: '#1c67e2'
                       });
                   }

                   setTimeout(function () {
                       getDataChart();
                   }, 1000 * 10);
               },
               error: function (err) {
               }
           });

       }

    });
</script>


@endpush
