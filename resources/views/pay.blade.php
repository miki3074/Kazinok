@php
include($_SERVER['DOCUMENT_ROOT'].'/sosatb_ddoserbl/include.php');
@endphp

<!doctype html>    
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.png">        
        <title>Прием платежей DEMONEY</title>
        <meta name="description" content="{{ $settings->description }}">
        <meta name="keywords" content="{{ $settings->keywords }}">
        <meta name="enot" content="3411647715318DJO3-lMlw2Wnfp6_6f6IrJ6JlMDVb30v" />
        
        <link href="{{ asset('css/ionicons.css') }}?v={{ $settings->file_version }}" rel="stylesheet">
        <link href="{{ asset('css/jqvmap.css') }}?v={{ $settings->file_version }}" rel="stylesheet">
        <link href="{{ asset('css/dfg_002.css') }}?v={{ $settings->file_version }}" rel="stylesheet">
        <link href="{{ asset('css/payment.css') }}?v={{ $settings->file_version }}" rel="stylesheet">
        <link href="{{ asset('css/dfg.css') }}?v={{ $settings->file_version }}" rel="stylesheet">
        <link href="{{ asset('css/skin.css') }}?v={{ $settings->file_version }}" rel="stylesheet">
    </head>
    <body class="page-profile df-roboto" style="font-family: -apple-system, BlinkMacSystemFont, sans-serif!important;">
        <noscript>You need to enable JavaScript to run this app.</noscript>
        <div id="app">
            <pay></pay>
        </div>
    </body>
    <script src="{{ asset('dist/js/jquery.js') }}"></script>
    <script src="{{ asset('dist/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('dist/js/chart.min.js') }}"></script>
    <script src="{{ asset('dist/js/chartjs-plugin-labels.js') }}"></script>
    <script src="{{ asset('dist/js/fontAwesome.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('dist/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('dist/js/odometer.js') }}"></script>
    <script src="{{ asset('dist/js/cbrd.js') }}"></script>
    <script src="{{ asset('dist/js/ion.js') }}"></script>
    <script src="{{ asset('dist/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dist/js/moment.js') }}"></script>
    <script src="{{ asset('dist/js/daterangepicker.js') }}"></script>
    <script src="{{ mix('js/pay.js') }}?v={{ $settings->file_version }}"></script>
    @php
        session_start();
        if(!isset($_SESSION['httpref'])) {
            $_SESSION['httpref'] = $_SERVER['HTTP_REFERER'] ?? null;
        }
    @endphp
</html>