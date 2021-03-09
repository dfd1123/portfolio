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

    <title>{{ __('auth2.page1')}}</title>

    <!-- Bootstrap core CSS-->
    <link rel="dns-prefetch" href="kit.fontawesome.com">
    <link href="{{asset('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Page level plugin CSS-->
    <link href="/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="/vendor/jquery-ui/jquery-ui-datepicker.min.css??{{ time() }}" rel="stylesheet" type="text/css">
    <link href="/vendor/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="/vendor/chart.js/Chart.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/vendor/lib.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/admin/sb-admin.css?'.time()) }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap core JavaScript-->
    <script src="https://kit.fontawesome.com/a1eec22bfc.js"></script>
    <script src="/js/vendor/vendor.js"></script>
    <script src="{{asset('/js/admin/common.js?'.time()) }}"></script>
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

  <body class="bg-dark">

    <div class="container">
      <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login</div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.login.form') }}">
				@csrf
            <div class="form-group">
              <div class="form-label-group">
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
                <label for="inputEmail">Email address</label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required="required">
                <label for="inputPassword">Password</label>
              </div>
            </div>
            <button type="submit">Login</button>
          </form>
        </div>
      </div>
    </div>

  </body>

</html>
