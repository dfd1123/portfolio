<!DOCTYPE html>
<html lang="kr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SPOWIDE</title>
  <link rel="stylesheet" type="text/css" href="{{asset('/css/vendor/vendor.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('/css/pc/original/basic_market.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('/css/pc/spowide.css')}}">
  @if($pagename[1] == '' || $pagename[1] == 'main')
    <link rel="stylesheet" type="text/css" href="{{asset('/css/pc/main.css')}}"></link>
  @else
    <link rel="stylesheet" type="text/css" href="{{asset('/css/pc/'.$pagename[1].'.css')}}"></link>
  @endif

  <script src="{{asset('js/vendor/vendor.js')}}"></script>
</head>
<body>
    @include('theme.basic.pc.layouts.header')

    <script>
      if (typeof __ === 'undefined') { var __ = {}; }
			__.message = {
				@foreach(__('message') as $key => $value)
					'{{$key}}':'{{$value}}',
				@endforeach
			};
    </script>

    <div id="wrapper">
        <div id="container">
            @yield('content')
        </div>
    </div>
    
    @include('theme.basic.pc.layouts.footer')
    

    <script src="{{asset('/js/pc/original/basic.js')}}"></script>

    <script>

			@if(session()->has('jsAlert'))

				swal({ text: "{{ session()->get('jsAlert') }}",
					icon: "warning",
					button: '{{ __('message.ok') }}',
				});

			@endif

			@if(session()->has('jsCheck'))

				swal({
					text: "{{ session()->get('jsCheck') }}",
					icon: "success",
					button: '{{ __('message.ok') }}',
				});

			@endif
			
			@if(session()->has('jsError'))

				swal({
					text: "{{ session()->get('jsError') }}",
					icon: "error",
					button: '{{ __('message.ok') }}',
				});

			@endif
		</script>
</body>
</html>
