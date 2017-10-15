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
        <h5>Lượt tương tác</h5>
        <div class="row">
          <div class="col-md-7">
            <div class="panel panel-flat">
              <div class="panel-body">
                <div id="chart-per-minute"></div>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="panel panel-flat">
              <div class="panel-body">
                <div id="chart-per-second"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h5>Ai đang tương tác với các sản phẩm của bạn?</h5>
            <div id="user-activities"></div>
            <script type="text/template" id="scan-template">
              <div class="panel panel-flat border-left-xlg border-indigo">
                <div class="panel-body">
                  <div class="media">
                    <div class="media-left">
                      <img src="{{ asset('assets/images/image.png') }}" class="img-circle" alt="">
                    </div>

                    <div class="media-body">
                      <h6 class="media-heading"><strong class="js-actor-name">Huy TQ</strong> đã quét sản phẩm <strong class="js-product-name">Thuốc Kimochi</strong></h6>
                      <div class="media-annotation mt-5 js-action-time">vừa xong</div>
                    </div>
                  </div>
                </div>
              </div>
            </script>
            <script type="text/template" id="like-template">
              <div class="panel panel-flat border-left-xlg border-success">
                <div class="panel-body">
                  <div class="media">
                    <div class="media-left">
                      <img src="{{ asset('assets/images/image.png') }}" class="img-circle" alt="">
                    </div>

                    <div class="media-body">
                      <h6 class="media-heading"><strong class="js-actor-name">Huy TQ</strong> đã thích sản phẩm <strong class="js-product-name">Thuốc Kimochi</strong></h6>
                      <div class="media-annotation mt-5 js-action-time">vừa xong</div>
                    </div>
                  </div>
                </div>
              </div>
            </script>
            <script type="text/template" id="unlike-template">
              <div class="panel panel-flat border-left-xlg border-danger">
                <div class="panel-body">
                  <div class="media">
                    <div class="media-left">
                      <img src="{{ asset('assets/images/image.png') }}" class="img-circle" alt="">
                    </div>

                    <div class="media-body">
                      <h6 class="media-heading"><strong class="js-actor-name">Huy TQ</strong> đã bỏ thích sản phẩm <strong class="js-product-name">Thuốc Kimochi</strong></h6>
                      <div class="media-annotation mt-5 js-action-time">vừa xong</div>
                    </div>
                  </div>
                </div>
              </div>
            </script>
            <script type="text/template" id="comment-template">
              <div class="panel panel-flat border-left-xlg border-blue">
                <div class="panel-body">
                  <div class="media">
                    <div class="media-left">
                      <img src="{{ asset('assets/images/image.png') }}" class="img-circle" alt="">
                    </div>

                    <div class="media-body">
                      <h6 class="media-heading"><strong class="js-actor-name">Huy TQ</strong> đã bình luận về sản phẩm <strong class="js-product-name">Thuốc Kimochi</strong></h6>
                      <div class="border-left-lg border-grey-300 pl-10">
                        <p class="js-comment-content">Thuốc này dùng phê đấy</p>
                      </div>
                      <div class="media-annotation mt-5 js-action-time">vừa xong</div>
                    </div>
                  </div>
                </div>
              </div>
            </script>
            <script type="text/template" id="vote-template">
              <div class="panel panel-flat border-left-xlg border-orange">
                <div class="panel-body">
                  <div class="media">
                    <div class="media-left">
                      <img src="{{ asset('assets/images/image.png') }}" class="img-circle" alt="">
                    </div>

                    <div class="media-body">
                      <h6 class="media-heading"><strong class="js-actor-name">Huy TQ</strong> đã đánh giá <span class="text-orange js-vote-star"></span> cho sản phẩm <strong class="js-product-name">Thuốc Kimochi</strong></h6>
                      <div class="media-annotation mt-5 js-action-time">vừa xong</div>
                    </div>
                  </div>
                </div>
              </div>
            </script>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <h5>Sản phẩm được quét nhiều</h5>
                <div class="panel panel-flat">
                  <table class="table table-hover" id="top-scan-table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Sản phẩm</th>
                        <th>Số lượt quét</th>
                      </tr>
                    </thead>
                    <tbody id="top-scan">

                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <h5>Sản phẩm được thích nhiều</h5>
                <div class="panel panel-flat">
                  <table class="table table-hover" id="top-like-table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Sản phẩm</th>
                        <th>Số lượt thích</th>
                      </tr>
                    </thead>
                    <tbody id="top-like">

                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <h5>Sản phẩm được bình luận nhiều</h5>
                <div class="panel panel-flat">
                  <table class="table table-hover" id="top-comment-table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Sản phẩm</th>
                        <th>Số lượt bình luận</th>
                      </tr>
                    </thead>
                    <tbody id="top-comment">

                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <h5>Sản phẩm được bình chọn cao</h5>
                <div class="panel panel-flat">
                  <table class="table table-hover" id="top-like-vote">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Sản phẩm</th>
                        <th>Số lượt bình chọn</th>
                        <th>Số điểm trung bình</th>
                      </tr>
                    </thead>
                    <tbody id="top-vote">

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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.2.5/highcharts.js"></script>
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

    (function () {
      function loadUserActivities(since, util) {
        var $userActivities = $('#user-activities');
        var maxUserActivitiesItemShow = 30;

        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@userActivities') }}',
          data: since ? { since: since } : { util: util, limit: maxUserActivitiesItemShow }
        }).then(function (response) {
          var data = response.data.reverse();

          $userActivities.find('.js-action-time').each(function (child) {
            var $this = $(this),
                time = moment($this.data('time'));

            $this.text(time.fromNow());
          });

          data.forEach(function (data) {
            var $template = $('#' + data.action + '-template'),
                $item = $($template.html());

            $item.find('.js-actor-name').text(data.userName ? data.userName : data.userId);
            $item.find('.js-product-name').text(data.productName);

            if (data.data.hasOwnProperty('content')) {
              $item.find('.js-comment-content').text(data.data.content);
            }

            if (data.data.image) {
              $item.find('.js-comment-content').after('<img src="' + data.data.image + '" class="img-responsive" />');
            }

            if (data.action == 'vote') {
              var star = '<i class="icon-star-full2"></i>'.repeat(data.data.point) + '<i class="icon-star-empty3"></i>'.repeat(5 - data.data.point);
              $item.find('.js-vote-star')
                .html(star);
            }

            var time = moment(data.time);

            $item.find('.js-action-time')
              .data('time', time.toISOString())
              .attr('title', time.toISOString())
              .text(time.fromNow());

            $item.prependTo($userActivities).addClass('highlight');

            since = time.utc().unix() + 1;
          });

          var total = $userActivities.children().length;

          if (total > maxUserActivitiesItemShow) {
            $userActivities.children(':nth-last-child(-n+' + (total - maxUserActivitiesItemShow) + ')').remove();
          }

          if (!since) {
            since = now.clone().utc().unix();
          }

          setTimeout(function () {
            loadUserActivities(since);
          }, 1000 * 15);
        });
      }

      loadUserActivities(null, now.clone().utc().unix());
    })();

    (function () {
      var oldRanks = {};
      var oldScanCounts = {};

      function loadTopScan() {
        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@topScan') }}'
        }).then(function (response) {
          var data = response.data;
          var newRanks = {};
          var html = '';

          data.forEach(function (data, i) {
            var rank = i + 1;
            var rankIncrease = 0;
            var oldScanCount = oldScanCounts[data.gtinCode] || null;
            var scanIncrease = data.scanCount - oldScanCount;

            if (oldRanks[data.gtinCode]) {
              rankIncrease = oldRanks[data.gtinCode] - rank;
            }

            if (rankIncrease > 0) {
              html += '<tr class="green-highlight">';
              html += '<td class="text-green"><i class="icon-arrow-up5"></i> ' + rankIncrease + '</td>';
            } else if (rankIncrease < 0) {
              html += '<tr class="red-highlight">';
              html += '<td class="text-danger"><i class="icon-arrow-down5"></i> ' + rankIncrease + '</td>';
            } else {
              html += '<tr>';
              html += '<td class="text-grey"><i class="icon-dash"></i></td>';
            }

            html += '<td>' + data.productName + '</td>';
            html += '<td>' + data.scanCount + (oldScanCount !== null ? ' (<strong class="text-green">+' + scanIncrease + '</strong>)' : '') + '</td>';
            html += '</tr>';

            newRanks[data.gtinCode] = rank;
            oldScanCounts[data.gtinCode] = scanIncrease;
          });

          $('#top-scan').html(html);

          oldRanks = newRanks;

          setTimeout(loadTopScan, 1000 * 15);

        });
      }

      loadTopScan();
    })();

    (function () {
      var oldRanks = {};
      var oldLikeCounts = {};

      function loadTopLike() {
        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@topLike') }}'
        }).then(function (response) {
          var data = response.data;
          var newRanks = {};
          var html = '';

          data.forEach(function (data, i) {
            var rank = i + 1;
            var rankIncrease = 0;
            var oldLikeCount = oldLikeCounts[data.gtinCode] || null;
            var likeIncrease = data.likeCount - oldLikeCount;

            if (oldRanks[data.gtinCode]) {
              rankIncrease = oldRanks[data.gtinCode] - rank;
            }

            if (rankIncrease > 0) {
              html += '<tr class="green-highlight">';
              html += '<td class="text-green"><i class="icon-arrow-up5"></i> ' + rankIncrease + '</td>';
            } else if (rankIncrease < 0) {
              html += '<tr class="red-highlight">';
              html += '<td class="text-danger"><i class="icon-arrow-down5"></i> ' + rankIncrease + '</td>';
            } else {
              html += '<tr>';
              html += '<td class="text-grey"><i class="icon-dash"></i></td>';
            }

            html += '<td>' + data.productName + '</td>';
            html += '<td>' + data.likeCount + (oldLikeCount !== null ? ' (<strong class="text-green">+' + likeIncrease + '</strong>)' : '') + '</td>';
            html += '</tr>';

            newRanks[data.gtinCode] = rank;
            oldLikeCounts[data.gtinCode] = likeIncrease;
          });

          $('#top-like').html(html);

          oldRanks = newRanks;

          setTimeout(loadTopLike, 1000 * 15);

        });
      }

      loadTopLike();
    })();

    (function () {
      var oldRanks = {};
      var oldCommentCounts = {};

      function loadTopComment() {
        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@topComment') }}'
        }).then(function (response) {
          var data = response.data;
          var newRanks = {};
          var html = '';

          data.forEach(function (data, i) {
            var rank = i + 1;
            var rankIncrease = 0;
            var oldCommentCount = oldCommentCounts[data.gtinCode] || null;
            var commentIncrease = data.commentCount - oldCommentCount;

            if (oldRanks[data.gtinCode]) {
              rankIncrease = oldRanks[data.gtinCode] - rank;
            }

            if (rankIncrease > 0) {
              html += '<tr class="green-highlight">';
              html += '<td class="text-green"><i class="icon-arrow-up5"></i> ' + rankIncrease + '</td>';
            } else if (rankIncrease < 0) {
              html += '<tr class="red-highlight">';
              html += '<td class="text-danger"><i class="icon-arrow-down5"></i> ' + rankIncrease + '</td>';
            } else {
              html += '<tr>';
              html += '<td class="text-grey"><i class="icon-dash"></i></td>';
            }

            html += '<td>' + data.productName + '</td>';
            html += '<td>' + data.commentCount + (oldCommentCount !== null ? ' (<strong class="text-green">+' + commentIncrease + '</strong>)' : '') + '</td>';
            html += '</tr>';

            newRanks[data.gtinCode] = rank;
            oldCommentCounts[data.gtinCode] = commentIncrease;
          });

          $('#top-comment').html(html);

          oldRanks = newRanks;

          setTimeout(loadTopComment, 1000 * 15);

        });
      }

      loadTopComment();
    })();

    (function () {
      var oldRanks = {};
      var oldVoteCounts = {};

      function loadTopVote() {
        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@topVote') }}'
        }).then(function (response) {
          var data = response.data;
          var newRanks = {};
          var html = '';

          data.forEach(function (data, i) {
            var rank = i + 1;
            var rankIncrease = 0;
            var oldVoteCount = oldVoteCounts[data.gtinCode] || null;
            var voteIncrease = data.voteCount - oldVoteCount;

            if (oldRanks[data.gtinCode]) {
              rankIncrease = oldRanks[data.gtinCode] - rank;
            }

            if (rankIncrease > 0) {
              html += '<tr class="green-highlight">';
              html += '<td class="text-green"><i class="icon-arrow-up5"></i> ' + rankIncrease + '</td>';
            } else if (rankIncrease < 0) {
              html += '<tr class="red-highlight">';
              html += '<td class="text-danger"><i class="icon-arrow-down5"></i> ' + rankIncrease + '</td>';
            } else {
              html += '<tr>';
              html += '<td class="text-grey"><i class="icon-dash"></i></td>';
            }

            html += '<td>' + data.productName + '</td>';
            html += '<td>' + data.voteCount + (oldVoteCount !== null ? ' (<strong class="text-green">+' + voteIncrease + '</strong>)' : '') + '</td>';





            var base = data.voteAverage * 10;
            var fullStarCount = Math.floor(base / 10);
            var halfStarCount = 0;

            if ((base % 10) >= 5) {
              halfStarCount = 1;
            }

            var emptyStarCount = 5 - fullStarCount -halfStarCount;



            html += '<td>' + ('<i class="icon-star-full2"></i>'.repeat(fullStarCount)) + ('<i class="icon-star-half"></i>'.repeat(halfStarCount)) + ('<i class="icon-star-empty3"></i>'.repeat(emptyStarCount)) + '</td>';
            html += '</tr>';

            newRanks[data.gtinCode] = rank;
            oldVoteCounts[data.gtinCode] = voteIncrease;
          });

          $('#top-vote').html(html);

          oldRanks = newRanks;

          setTimeout(loadTopVote, 1000 * 15);

        });
      }

      loadTopVote();
    })();


    (function () {
      $.ajax({
        url: '{{ route('Ajax::analytics@realtime@currentTimestamp') }}',
        cache: false
      }).then(function (response) {
        var padding = parseInt(response.time) - now.clone().unix();

        console.log(padding);

        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@chartData') }}',
          data: { type: 'perMinute', util: now.clone().startOf('minute').unix() },
          cache: false
        }).then(function (response) {
          var chartMinuteData = response.data;

          function requestData(since, util) {
            if (since) {
              $.ajax({
                url: '{{ route('Ajax::analytics@realtime@chartData') }}',
                data: since ? { type: 'perMinute', since: since } : { type: 'perMinute', util: util },
                cache: false
              }).then(function (response) {
                var chart = $('#chart-per-minute').highcharts();
                var data = response.data;
                chart.series[0].addPoint(data.scan, true, true);
                chart.series[1].addPoint(data.like, true, true);
                chart.series[2].addPoint(data.unlike, true, true);
                chart.series[3].addPoint(data.comment, true, true);
                chart.series[4].addPoint(data.vote, true, true);

                setTimeout(function () {
                  requestData(moment(since * 1000).add(1, 'minutes').unix());
                }, 1000 * 60);
              });
            } else {
              setTimeout(function () {
                requestData(util.unix());
              }, 1000 * 60);
            }
          }

          $('#chart-per-minute').highcharts({
            chart: {
              type: 'column',
              events: {
                load: function () {
                  requestData(null, now.clone().utc().startOf('minute'));
                }
              }
            },
            title: {
              text: ''
            },
            subtitle: {
              text: 'Theo phút'
            },
            xAxis: {
              title: {
                text: ''
              },
              type: 'datetime',
              crosshair: true,
              tickInterval: 1000 * 60 * 5
            },
            yAxis: {
              min: 0,
              title: {
                text: ''
              },
              stackLabels: {
                enabled: false
              }
            },
            tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><strong>{point.y}</strong> lần</td></tr>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
            },
            plotOptions: {
              column: {
                stacking: 'normal',
                dataLabels: {
                  enabled: false
                }
              }
            },
            credits: {
              enabled: false
            },
            series: [{
              name: 'Quét',
              data: chartMinuteData.scan
            }, {
              name: 'Thích',
              data: chartMinuteData.like
            }, {
              name: 'Bỏ thích',
              data: chartMinuteData.unlike
            }, {
              name: 'Bình luận',
              data: chartMinuteData.comment
            }, {
              name: 'Bình chọn',
              data: chartMinuteData.vote
            }]
          });
        });

        $.ajax({
          url: '{{ route('Ajax::analytics@realtime@chartData') }}',
          data: { type: 'perSecond', util: now.clone().startOf('minute').unix() },
          cache: false
        }).then(function (response) {
          var chartMinuteData = response.data;

          function requestData(since, util) {
            if (since) {
              $.ajax({
                url: '{{ route('Ajax::analytics@realtime@chartData') }}',
                data: since ? { type: 'perSecond', since: since } : { type: 'perSecond', util: util },
                cache: false
              }).then(function (response) {
                var chart = $('#chart-per-second').highcharts();
                var data = response.data;
                chart.series[0].addPoint(data.scan, true, true);
                chart.series[1].addPoint(data.like, true, true);
                chart.series[2].addPoint(data.unlike, true, true);
                chart.series[3].addPoint(data.comment, true, true);
                chart.series[4].addPoint(data.vote, true, true);

                setTimeout(function () {
                  requestData(moment(since * 1000).add(1, 'seconds').unix());
                }, 1000);
              });
            } else {
              setTimeout(function () {
                requestData(util.unix() + padding);
              }, 1000);
            }
          }

          $('#chart-per-second').highcharts({
            chart: {
              type: 'column',
              events: {
                load: function () {
                  requestData(null, now.clone().utc().startOf('second'));
                }
              }
            },
            title: {
              text: ''
            },
            subtitle: {
              text: 'Theo giây'
            },
            xAxis: {
              title: {
                text: ''
              },
              type: 'datetime',
              crosshair: false,
              tickInterval: 1000 * 15
            },
            yAxis: {
              min: 0,
              title: {
                text: ''
              },
              stackLabels: {
                enabled: false
              }
            },
            tooltip: {
              enabled: false,
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><strong>{point.y}</strong> lần</td></tr>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
            },
            plotOptions: {
              column: {
                stacking: 'normal',
                dataLabels: {
                  enabled: false
                }
              }
            },
            credits: {
              enabled: false
            },
            series: [{
              name: 'Quét',
              data: chartMinuteData.scan
            }, {
              name: 'Thích',
              data: chartMinuteData.like
            }, {
              name: 'Bỏ thích',
              data: chartMinuteData.unlike
            }, {
              name: 'Bình luận',
              data: chartMinuteData.comment
            }, {
              name: 'Bình chọn',
              data: chartMinuteData.vote
            }]
          });
        });
      });
    })();




    //$('#chart_container').highcharts(chartOptions);
  });
  </script>
@endpush
