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

    <title>TellusArt::관리자페이지</title>

    <!-- Bootstrap core CSS-->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="/vendor/jquery-ui/jquery-ui-datepicker.min.css??{{ time() }}" rel="stylesheet" type="text/css">
    <link href="/vendor/summernote/summernote-bs4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/admin/sb-admin.css??{{ time() }}" rel="stylesheet" type="text/css">
    
    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="/vendor/chart.js/Chart.min.js"></script>
    <script src="/vendor/datatables/jquery.dataTables.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="/vendor/jquery-ui/jquery-ui-datepicker.min.js"></script>
    <script src="/vendor/moment/moment.min.js"></script>
    <script src="/vendor/summernote/summernote-bs4.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-ko-KR.js"></script>

  </head>

  <body id="page-top">
  	
	

  	@include('admin.layouts.header')
	
	@yield('content')
	
	@yield('script')




	@include('admin.layouts.footer')
	

    <!-- Custom scripts for all pages-->
    <script src="/js/admin/admin.js"></script>

    @if(session()->has('jsAlert'))
    <script>
        alert('{{ session()->get('jsAlert') }}');
    </script>
    @endif 

    <!-- Demo scripts for this page-->
    <!--<script src="/js/admin/datatables-demo.js"></script>-->
    <!--<script src="/js/admin/chart-area-demo.js"></script>-->
  </body>

</html>
