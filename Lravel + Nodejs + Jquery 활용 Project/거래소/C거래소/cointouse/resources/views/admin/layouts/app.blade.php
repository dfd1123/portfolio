<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cointouse {{ __('layout.sys')}}</title>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-6jHF7Z3XI3fF4XZixAuSu0gGKrXwoX/w3uFPxC56OtjChio7wtTGJWRW53Nhx6Ev"
        crossorigin="anonymous">
    <!-- Bootstrap core CSS-->
    <link href="{{asset('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="/vendor/jquery-ui/jquery-ui-datepicker.min.css??{{ time() }}" rel="stylesheet" type="text/css">
    <link href="/vendor/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="/vendor/chart.js/Chart.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/vendor/lib.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/admin/sb-admin.css?'.time()) }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap core JavaScript-->
    <script src="/js/vendor/vendor.js"></script>
    <script src="{{asset('/js/admin/common.js?'.time()) }}"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="/vendor/datatables/jquery.dataTables.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="/vendor/jquery-ui/jquery-ui-datepicker.min.js"></script>
    <script src="/vendor/moment/moment.min.js"></script>
    <script src="/vendor/chart.js/Chart.min.js"></script>
    <script src="/vendor/chart.js/chartjs-plugin-datalabels.min.js"></script>
    <script src="/vendor/summernote/summernote-bs4.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-ko-KR.js"></script>
</head>

<body id="page-top">
    @include('admin.layouts.header') @yield('content') @yield('script')
    @include('admin.layouts.footer')


    <!-- Custom scripts for all pages-->
    <script src="{{asset('/js/admin/market_admin.js?'.time()) }}"></script>

    <script>
        @if(session()->has('jsAlert'))
        
        $.alert({
            title: "{{ __(layout.alr)}}",
            content: "{{ session()->get('jsAlert') }}",
        });

    @endif

    </script>
</body>

</html>