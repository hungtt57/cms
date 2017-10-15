@extends('_layouts/default')



@section('content')
    <script>
        (function(w,d,s,g,js,fs){
            g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
            js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
            js.src='https://apis.google.com/js/platform.js';
            fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
        }(window,document,'script'));
    </script>

    <div id="embed-api-auth-container"></div>
    <div id="view-selector-container"></div>
    <div id="main-chart-container"></div>
    <div id="breakdown-chart-container"></div>
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

    gapi.analytics.ready(function() {

        /**
         * Authorize the user immediately if the user has already granted access.
         * If no access has been created, render an authorize button inside the
         * element with the ID "embed-api-auth-container".
         */
        gapi.analytics.auth.authorize({
            container: 'embed-api-auth-container',
            clientid: '147760166958-roc9tv0b4270d21rc3s7rqli59106ckp.apps.googleusercontent.com'
        });


        /**
         * Create a new ViewSelector instance to be rendered inside of an
         * element with the id "view-selector-container".
         */
        var viewSelector = new gapi.analytics.ViewSelector({
            container: 'view-selector-container'
        });

        // Render the view selector to the page.
        viewSelector.execute();

        /**
         * Create a table chart showing top browsers for users to interact with.
         * Clicking on a row in the table will update a second timeline chart with
         * data from the selected browser.
         */
        var mainChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                'dimensions': 'ga:date,ga:eventAction,ga:dimension1',
                'metrics': 'ga:totalEvents',
                'filters' : 'ga:dimension1==8936031260286,ga:dimension1==8936031261009,ga:dimension1==8936031261702,ga:dimension1==8936031261764,ga:dimension1==8936031261825,ga:dimension1==8936031261832,ga:dimension1==8936031261856,ga:dimension1==8936031261863,ga:dimension1==8936031261894,ga:dimension1==8936031261900,ga:dimension1==8936031261931,ga:dimension1==8936031261986,ga:dimension1==8936031261993,ga:dimension1==8936031262013,ga:dimension1==8936031262037,ga:dimension1==8936031262075,ga:dimension1==8936031262082,ga:dimension1==8936031262099,ga:dimension1==8936031262112,ga:dimension1==8936031262136,ga:dimension1==8936031262150,ga:dimension1==8936031262167,ga:dimension1==8936031262204,ga:dimension1==8936031262228,ga:dimension1==8936031262242,ga:dimension1==8936031262259,ga:dimension1==8936031262297,ga:dimension1==8936031262334,ga:dimension1==8936031262341,ga:dimension1==8936031262358,ga:dimension1==8936031262396,ga:dimension1==8936031262419,ga:dimension1==8936031262433,ga:dimension1==8936031262440,ga:dimension1==8936031262457,ga:dimension1==8936031262464,ga:dimension1==8936031262594,ga:dimension1==8936031262617,ga:dimension1==8936031262624,ga:dimension1==8936031262631,ga:dimension1==8936031262679,ga:dimension1==8936031262754,ga:dimension1==8936031262778,ga:dimension1==8936031262792,ga:dimension1==8936031262808,ga:dimension1==8936031262822,ga:dimension1==8936031262839,ga:dimension1==8936031262846,ga:dimension1==8936031262853,ga:dimension1==8936031262907,ga:dimension1==8936031263027,ga:dimension1==8936031263034,ga:dimension1==8936031263102,ga:dimension1==8936031263119,ga:dimension1==8936031263126,ga:dimension1==8936031263133,ga:dimension1==8936031263140,ga:dimension1==8936031263171,ga:dimension1==8936031263188,ga:dimension1==8936031263195,ga:dimension1==8936031263201,ga:dimension1==8936031263225,ga:dimension1==8936031263270,ga:dimension1==8936031263300,ga:dimension1==8936031263379,ga:dimension1==8936031263386,ga:dimension1==8936031263393,ga:dimension1==8936031263409,ga:dimension1==8936031263430,ga:dimension1==8936031263447,ga:dimension1==8936031263461,ga:dimension1==8936031263485,ga:dimension1==8936031263492,ga:dimension1==8936031263508,ga:dimension1==8936031263515,ga:dimension1==8936031263539,ga:dimension1==8936031263553,ga:dimension1==8936031263560,ga:dimension1==8936031263584,ga:dimension1==8936031263591,ga:dimension1==8936031263607,ga:dimension1==8936031263621,ga:dimension1==8936031263638,ga:dimension1==8936031263652,ga:dimension1==8936031263690,ga:dimension1==8936031263706,ga:dimension1==8936031263713,ga:dimension1==8936031263720,ga:dimension1==8936031263737,ga:dimension1==8936031263744,ga:dimension1==8936031263751,ga:dimension1==8936031263768,ga:dimension1==8936031263775,ga:dimension1==8936031263782,ga:dimension1==8936031263829,ga:dimension1==8936031263836,ga:dimension1==8936031263850,ga:dimension1==8936031263874,ga:dimension1==8936031263904,ga:dimension1==8936031263911,ga:dimension1==8936031263935,ga:dimension1==8936031263942,ga:dimension1==8936031263959,ga:dimension1==8936031263966,ga:dimension1==8936031263980,ga:dimension1==8936031263997,ga:dimension1==8936031264000,ga:dimension1==8936031264017,ga:dimension1==8936031264048,ga:dimension1==8936031264055,ga:dimension1==8936031264178,ga:dimension1==8936031264208,ga:dimension1==8936031264222,ga:dimension1==8936031264239,ga:dimension1==8936031264260,ga:dimension1==8936031264277,ga:dimension1==8936031264499,ga:dimension1==8936031264512,ga:dimension1==8936031264536,ga:dimension1==8936031264543,ga:dimension1==8936031264567,ga:dimension1==8936031264574,ga:dimension1==8936031264635,ga:dimension1==8936031264642,ga:dimension1==8936031264659,ga:dimension1==8936031264666,ga:dimension1==8936031264673,ga:dimension1==8936031264680,ga:dimension1==8936031264697,ga:dimension1==8936031264734,ga:dimension1==8936031264758,ga:dimension1==8936031264765,ga:dimension1==8936031264802,ga:dimension1==8936031264833,ga:dimension1==8936031264840,ga:dimension1==8936031264864,ga:dimension1==8936031264871,ga:dimension1==8936031264895,ga:dimension1==8936031264901,ga:dimension1==8936031264918,ga:dimension1==8936031264932,ga:dimension1==8936031264949,ga:dimension1==8936031264963,ga:dimension1==8936031264994,ga:dimension1==8936031265014,ga:dimension1==8936031265069,ga:dimension1==8936031265113,ga:dimension1==8936031265144,ga:dimension1==8936031265175,ga:dimension1==8936031265182,ga:dimension1==8936031265236,ga:dimension1==8936031265243,ga:dimension1==8936031265250,ga:dimension1==8936031265267,ga:dimension1==8936031265304,ga:dimension1==8936031265311,ga:dimension1==8936031265328,ga:dimension1==8936031265335,ga:dimension1==8936031265359,ga:dimension1==8936031265366,ga:dimension1==8936031265380,ga:dimension1==8936031265427,ga:dimension1==8936031265441,ga:dimension1==8936031265533,ga:dimension1==8936031265540,ga:dimension1==8936031265557,ga:dimension1==8936031265564,ga:dimension1==8936031265588,ga:dimension1==8936031265595,ga:dimension1==8936031265618,ga:dimension1==8936031265625,ga:dimension1==8936031265632,ga:dimension1==8936031265649,ga:dimension1==8936031265656,ga:dimension1==8936031265663,ga:dimension1==8936031265670,ga:dimension1==8936031265687,ga:dimension1==8936031265694,ga:dimension1==8936031265700,ga:dimension1==8936031265731,ga:dimension1==8936031265755,ga:dimension1==8936031265762,ga:dimension1==8936031265786,ga:dimension1==8936031265809,ga:dimension1==8936031265830,ga:dimension1==8936031265847,ga:dimension1==8936031265854,ga:dimension1==8936031265861,ga:dimension1==8936031265878,ga:dimension1==8936031265892,ga:dimension1==8936031265908,ga:dimension1==8936031265922,ga:dimension1==8936031265939,ga:dimension1==8936031265946,ga:dimension1==8936031265984,ga:dimension1==8936031265991,ga:dimension1==8936031266004,ga:dimension1==8936031266011,ga:dimension1==8936031266028,ga:dimension1==8936031266042,ga:dimension1==8936031266059,ga:dimension1==8936031266066,ga:dimension1==8936031266073,ga:dimension1==8936031266080,ga:dimension1==8936031266097,ga:dimension1==8936031266103,ga:dimension1==8936031266134,ga:dimension1==8936031266141,ga:dimension1==8936031266158,ga:dimension1==8936031266172,ga:dimension1==8936031266189,ga:dimension1==8936031266196,ga:dimension1==8936031266202,ga:dimension1==8936031266219,ga:dimension1==8936031266226,ga:dimension1==8936031266233,ga:dimension1==8936031266257,ga:dimension1==8936031266264,ga:dimension1==8936031266271,ga:dimension1==8936031266301,ga:dimension1==8936031266325,ga:dimension1==8936031266332,ga:dimension1==8936031266349,ga:dimension1==8936031266356,ga:dimension1==8936031266363,ga:dimension1==8936031266370,ga:dimension1==8936031266387,ga:dimension1==8936031266394,ga:dimension1==8936031266400,ga:dimension1==8936031266431,ga:dimension1==8936031266493,ga:dimension1==8936031266509,ga:dimension1==8936031266530,ga:dimension1==8936031266561,ga:dimension1==8936031266578,ga:dimension1==8936031266585,ga:dimension1==8936031266592,ga:dimension1==8936031266707,ga:dimension1==8936031266721,ga:dimension1==8936031266752,ga:dimension1==8936031266769,ga:dimension1==8936031266776,ga:dimension1==8936031266783,ga:dimension1==8936031266820,ga:dimension1==8936031266837,ga:dimension1==8936031266875,ga:dimension1==8936031266899,ga:dimension1==8936031266905,ga:dimension1==8936031266912,ga:dimension1==8936031266929,ga:dimension1==8936031266936,ga:dimension1==8936031266943,ga:dimension1==8936031266974,ga:dimension1==8936031266981,ga:dimension1==8936031266998,ga:dimension1==8936031267025,ga:dimension1==8936031267049,ga:dimension1==8936031267056,ga:dimension1==8936031267063,ga:dimension1==8936031267070,ga:dimension1==8936031267087,ga:dimension1==8936031267094,ga:dimension1==8936031267100,ga:dimension1==8936031267117,ga:dimension1==8936031267131,ga:dimension1==8936031267148,ga:dimension1==8936031267155,ga:dimension1==8936031267162,ga:dimension1==8936031267179,ga:dimension1==8936031267186,ga:dimension1==8936031267193,ga:dimension1==8936031267230,ga:dimension1==8936031267247,ga:dimension1==8936031267278,ga:dimension1==8936031267308,ga:dimension1==8936031267339,ga:dimension1==8936031267407,ga:dimension1==8936031267414,ga:dimension1==8936031267438,ga:dimension1==8936031267445,ga:dimension1==8936031267452,ga:dimension1==8936031267469,ga:dimension1==8936031267551,ga:dimension1==8936031267575,ga:dimension1==8936031267582,ga:dimension1==8936031267643,ga:dimension1==8936031267667,ga:dimension1==8936031267674,ga:dimension1==8936031267681,ga:dimension1==8936031267711,ga:dimension1==8936031267872,ga:dimension1==8936031267902,ga:dimension1==8936031267940,ga:dimension1==8936031268077,ga:dimension1==8936031268183,ga:dimension1==8936031268190,ga:dimension1==8936031268213,ga:dimension1==8936031268220,ga:dimension1==8936031268244,ga:dimension1==8936031268138,ga:dimension1==8936031267735,ga:dimension1==8936031267834,ga:dimension1==8936031267827,ga:dimension1==8936031267537,ga:dimension1==8936031267704,ga:dimension1==8936031267728,ga:dimension1==8936031267810,ga:dimension1==8936031267803,ga:dimension1==8936031267520,ga:dimension1==8936031263577,ga:dimension1==8936031266738,ga:dimension1==8936031267995,ga:dimension1==8936031267919,ga:dimension1==8936031265410,ga:dimension1==8936031268176,ga:dimension1==8936031262952,ga:dimension1==8936031268107,ga:dimension1==8936031268114,ga:dimension1==8936031266714,ga:dimension1==8936031266851,ga:dimension1==8936031267698,ga:dimension1==8936031266950,ga:dimension1==8936031267957,ga:dimension1==8936031267759,ga:dimension1==8936031267841,ga:dimension1==8936031266868,ga:dimension1==8936031268039,ga:dimension1==8936031267315,ga:dimension1==8936031262006,ga:dimension1==8936031268145,ga:dimension1==8936031268091,ga:dimension1==8936031268060,ga:dimension1==8936031268046,ga:dimension1==8936031267926,ga:dimension1==8936031267292,ga:dimension1==8936031264154,ga:dimension1==8936031266745,ga:dimension1==8936031268121,ga:dimension1==8936031268206,ga:dimension1==8936031267032,ga:dimension1==8936031267858,ga:dimension1==8936031268015,ga:dimension1==8936031268084,ga:dimension1==8936031267896,ga:dimension1==8936031267742,ga:dimension1==8936031267971,ga:dimension1==8936031267018,ga:dimension1==8936031268169,ga:dimension1==8936031265519,ga:dimension1==8936031267988,ga:dimension1==8936031267889,ga:dimension1==8936031264796,ga:dimension1==8936031264796,ga:dimension1==8936031265458,ga:dimension1==8936031265458,ga:dimension1==8936031267766,ga:dimension1==8936031267766,ga:dimension1==8936031263898,ga:dimension1==8936031263898,ga:dimension1==8936031268008,ga:dimension1==8936031268008,ga:dimension1==8936031265274,ga:dimension1==8936031265274,ga:dimension1==8936031268329,ga:dimension1==8936031268329,ga:dimension1==8936031266967,ga:dimension1==8936031266967,ga:dimension1==8936031268312,ga:dimension1==8936031268312,ga:dimension1==8936031262648,ga:dimension1==8936031262648,ga:dimension1==8936031268374,ga:dimension1==8936031268374,ga:dimension1==8936031266448,ga:dimension1==8936031266448,ga:dimension1==8936031267544,ga:dimension1==8936031267544,ga:dimension1==8936031264987,ga:dimension1==8936031264987,ga:dimension1==8936031265120,ga:dimension1==8936031268756,ga:dimension1==8936031267636,ga:dimension1==8936031263799,ga:dimension1==8936031265038,ga:dimension1==8936031263003,ga:dimension1==8936031263423,ga:dimension1==8936031267773,ga:dimension1==8936031265977,ga:dimension1==8936031265397,ga:dimension1==8936031265045,ga:dimension1==8936031264727,ga:dimension1==8936031262310,ga:dimension1==8936031261221,ga:dimension1==8936031267285,ga:dimension1==8936031263867,ga:dimension1==8936031262174,ga:dimension1==8936031261689,ga:dimension1==8936031265229,ga:dimension1==8936031265168,ga:dimension1==8936031264925,ga:dimension1==8936031263928,ga:dimension1==8936031268442,ga:dimension1==8936031262211,ga:dimension1==8936031264031,ga:dimension1==8936031265199,ga:dimension1==8936031268336,ga:dimension1==8936031268350,ga:dimension1==8936031268367,ga:dimension1==8936031268343,ga:dimension1==8936031265403,ga:dimension1==8936031268411,ga:dimension1==8936031268404,ga:dimension1==8936031267650,ga:dimension1==8936031268435,ga:dimension1==8936031268480,ga:dimension1==8936031268428,ga:dimension1==8936031260644,ga:dimension1==8936031264161,ga:dimension1==8936031268695,ga:dimension1==8936031263416,ga:dimension1==8936031268459,ga:dimension1==8936031268534,ga:dimension1==8936031268473,ga:dimension1==8936031260293,ga:dimension1==8936031261887,ga:dimension1==8936031262105,ga:dimension1==8936031262198,ga:dimension1==8936031262389,ga:dimension1==8936031262426,ga:dimension1==8936031262785,ga:dimension1==8936031263263,ga:dimension1==8936031263287,ga:dimension1==8936031263454,ga:dimension1==8936031263522,ga:dimension1==8936031263645,ga:dimension1==8936031263669,ga:dimension1==8936031263843,ga:dimension1==8936031264192,ga:dimension1==8936031264468,ga:dimension1==8936031264598,ga:dimension1==8936031264741,ga:dimension1==8936031264857,ga:dimension1==8936031264888,ga:dimension1==8936031264970,ga:dimension1==8936031265137,ga:dimension1==8936031265342,ga:dimension1==8936031265571,ga:dimension1==8936031265748,ga:dimension1==8936031265816,ga:dimension1==8936031265885,ga:dimension1==8936031265953,ga:dimension1==8936031266035,ga:dimension1==8936031266127,ga:dimension1==8936031266165,ga:dimension1==8936031266240,ga:dimension1==8936031266318,ga:dimension1==8936031266424,ga:dimension1==8936031267001,ga:dimension1==8936031267124,ga:dimension1==8936031267223,ga:dimension1==8936031267322,ga:dimension1==8936031267421,ga:dimension1==8936031267476,ga:dimension1==8936031267599,ga:dimension1==8936031267865,ga:dimension1==8936031268022,ga:dimension1==8936031268152,ga:dimension1==8936031267964,ga:dimension1==8936031267933,ga:dimension1==8936031267780,ga:dimension1==8936031268053,ga:dimension1==8936031263614,ga:dimension1==8936031268565,ga:dimension1==8936031268596,ga:dimension1==8936031267391,ga:dimension1==8936031267989407,ga:dimension1==8936031268633,ga:dimension1==8936031268893,ga:dimension1==8936031268831,ga:dimension1==8936031268602,ga:dimension1==8936031268749,ga:dimension1==8936031268817,ga:dimension1==8936031268589,ga:dimension1==8936031268770,ga:dimension1==8936031268725,ga:dimension1==8936031268619,ga:dimension1==8936031268862,ga:dimension1==8936031268763,ga:dimension1==8936031268640,ga:dimension1==8936031268572,ga:dimension1==8936031268657,ga:dimension1==8936031268541,ga:dimension1==8936031268626,ga:dimension1==89360312622013,ga:dimension1==8936031267384,ga:dimension1==8936031268466,ga:dimension1==8936031268855,ga:dimension1==8936031268701,ga:dimension1==8936031268558,ga:dimension1==8936031267360,ga:dimension1==8936031268688,ga:dimension1==8936031268671,ga:dimension1==8936031268879,ga:dimension1==8936031268664,ga:dimension1==8936031267138,ga:dimension1==8936031268268,ga:dimension1==8936031268251,ga:dimension1==8936031268848,ga:dimension1==8936031268886,ga:dimension1==8936031268824,ga:dimension1==8936031268787,ga:dimension1==8936031269159,ga:dimension1==8936031268800,ga:dimension1==8936031269050,ga:dimension1==8936031267544 BB,ga:dimension1==8936031268992,ga:dimension1==8936031268985,ga:dimension1==8936031269135,ga:dimension1==8936031268718,ga:dimension1==8936031265076,ga:dimension1==8936031262716,ga:dimension1==8936031269067,ga:dimension1==8936031269081,ga:dimension1==8936031269036,ga:dimension1==8936031269197,ga:dimension1==8936031269227,ga:dimension1==8936031269470,ga:dimension1==8936031268909,ga:dimension1==8936031269005,ga:dimension1==8936031269074,ga:dimension1==8936031268954,ga:dimension1==8936031269142,ga:dimension1==8936031269234,ga:dimension1==8936031269456,ga:dimension1==8936031269449,ga:dimension1==8936031269432,ga:dimension1==8936031269531,ga:dimension1==8936031268527,ga:dimension1==8936031269104,ga:dimension1==8936031269401,ga:dimension1==8936031269319,ga:dimension1==8936031265021,ga:dimension1==8936031260000,ga:dimension1==8936031269425,ga:dimension1==8936031266684,ga:dimension1==8936031266684,ga:dimension1==8936031269395,ga:dimension1==8936031269395,ga:dimension1==8936031269463,ga:dimension1==8936031269463,ga:dimension1==8936031268510,ga:dimension1==8936031268510,ga:dimension1==8936031269210,ga:dimension1==8936031269210,ga:dimension1==8936031269203,ga:dimension1==8936031269203,ga:dimension1==8936031269173,ga:dimension1==8936031269173,ga:dimension1==8936031269180,ga:dimension1==8936031269180,ga:dimension1==8936031261757,ga:dimension1==8936031261757,ga:dimension1==8936031268794,ga:dimension1==8936031268794,ga:dimension1==8936031269326,ga:dimension1==8936031269524,ga:dimension1==8936031269524,ga:dimension1==8936031269333,ga:dimension1==8936031269333,ga:dimension1==8936031269265,ga:dimension1==8936031269265,ga:dimension1==8936031269258,ga:dimension1==8936031269340,ga:dimension1==8936031269340,ga:dimension1==8936031269272,ga:dimension1==8936031269272,ga:dimension1==8936031269289,ga:dimension1==8936031269357,ga:dimension1==8936031269357,ga:dimension1==8936031269616,ga:dimension1==8936031269609,ga:dimension1==8936031269623,ga:dimension1==8936031269302,ga:dimension1==8936031269388,ga:dimension1==8936031269388,ga:dimension1==8936031269630,ga:dimension1==8936031269630,ga:dimension1==8936031269807,ga:dimension1==8936031269807,ga:dimension1==8936031269418,ga:dimension1==8936031269418,ga:dimension1==8936031269777,ga:dimension1==8936031269777,ga:dimension1==8936031269746,ga:dimension1==8936031269746,ga:dimension1==8936031269722,ga:dimension1==8936031269722,ga:dimension1==8936031269739,ga:dimension1==8936031269739,ga:dimension1==8936031269852,ga:dimension1==8936031269852,ga:dimension1==8936031269920,ga:dimension1==8936031269920,ga:dimension1==8936031269951,ga:dimension1==8936031269951,ga:dimension1==8936031269753,ga:dimension1==8936031269753,ga:dimension1==8936031269760,ga:dimension1==8936031268923,ga:dimension1==8936031268923,ga:dimension1==8936031269937,ga:dimension1==8936031269791,ga:dimension1==8936031269791,ga:dimension1==8936031269296,ga:dimension1==8936031269296,ga:dimension1==8936031269869,ga:dimension1==8936031269869,ga:dimension1==8936031266844,ga:dimension1==8936031266844,ga:dimension1==8936031269944,ga:dimension1==8936031268916,ga:dimension1==8936031268916,ga:dimension1==8936031269661,ga:dimension1==8936031269678,ga:dimension1==8936031269692,ga:dimension1==8936031269708,ga:dimension1==8936031269715,ga:dimension1==8936031269722,ga:dimension1==8936130370015,ga:dimension1==8936130410018,ga:dimension1==8936031269999,ga:dimension1==8936031269968,ga:dimension1==8936031269975,ga:dimension1==8936031269999,ga:dimension1==8936031269876,ga:dimension1==8936031269883,ga:dimension1==8936031268961,ga:dimension1==8936031268978,ga:dimension1==8936031269654,ga:dimension1==8936031269647,ga:dimension1==8936031269593,ga:dimension1==8936031269586,ga:dimension1==8936031269579,ga:dimension1==8936031269982,ga:dimension1==8936031269913,ga:dimension1==8936031269012,ga:dimension1==8936031269029,ga:dimension1==8936031269890,ga:dimension1==8936130400026,ga:dimension1==8936031269906,ga:dimension1==8936031266653,ga:dimension1==8936031269173,ga:dimension1==8936031269180,ga:dimension1==8936031269197,ga:dimension1==8936031269203,ga:dimension1==8936031269210,ga:dimension1==8936031269227,ga:dimension1==8936031269784,ga:dimension1==8936031269814,ga:dimension1==8936031269821,ga:dimension1==8936031269838,ga:dimension1==8936031269845,ga:dimension1==8936031269098,ga:dimension1==8936031269111,ga:dimension1==8936031269128,ga:dimension1==8936031269166,ga:dimension1==8936031269685,ga:dimension1==8936031268497,ga:dimension1==8936031268732,ga:dimension1==8936031269241,ga:dimension1==8936031269364,ga:dimension1==8936031269371,ga:dimension1==8936031269487,ga:dimension1==8936031269494,ga:dimension1==8936031269500,ga:dimension1==8936031269517,ga:dimension1==8936031269548,ga:dimension1==8936031269555,ga:dimension1==8936031269562,ga:dimension1==8936131180019,ga:dimension1==8936131180026,ga:dimension1==8936131180033,ga:dimension1==8936131180040,ga:dimension1==8936130370015',
                'start-date': '7daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                type: 'TABLE',
                container: 'main-chart-container',
                options: {
                    width: '100%'
                }
            }
        });


        /**
         * Create a timeline chart showing sessions over time for the browser the
         * user selected in the main chart.
         */
        var breakdownChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                'dimensions': 'ga:date,ga:eventAction,ga:dimension1',
                'metrics': 'ga:totalEvents',
                'start-date': '7daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                type: 'LINE',
                container: 'breakdown-chart-container',
                options: {
                    width: '100%'
                }
            }
        });


        /**
         * Store a refernce to the row click listener variable so it can be
         * removed later to prevent leaking memory when the chart instance is
         * replaced.
         */
        var mainChartRowClickListener;


        /**
         * Update both charts whenever the selected view changes.
         */
        viewSelector.on('change', function(ids) {
            var options = {query: {ids: ids}};

            // Clean up any event listeners registered on the main chart before
            // rendering a new one.
            if (mainChartRowClickListener) {
                google.visualization.events.removeListener(mainChartRowClickListener);
            }

            mainChart.set(options).execute();
            breakdownChart.set(options);

            // Only render the breakdown chart if a browser filter has been set.
            if (breakdownChart.get().query.filters) breakdownChart.execute();
        });


        /**
         * Each time the main chart is rendered, add an event listener to it so
         * that when the user clicks on a row, the line chart is updated with
         * the data from the browser in the clicked row.
         */
        mainChart.on('success', function(response) {

            var chart = response.chart;
            var dataTable = response.dataTable;

            // Store a reference to this listener so it can be cleaned up later.
            mainChartRowClickListener = google.visualization.events
                    .addListener(chart, 'select', function(event) {

                        // When you unselect a row, the "select" event still fires
                        // but the selection is empty. Ignore that case.
                        if (!chart.getSelection().length) return;

                        var row =  chart.getSelection()[0].row;
                        var browser =  dataTable.getValue(row, 0);
                        var options = {
                            query: {
                                filters: 'ga:browser==' + browser
                            },
                            chart: {
                                options: {
                                    title: browser
                                }
                            }
                        };

                        breakdownChart.set(options).execute();
                    });
        });

    });
</script>
@endpush
