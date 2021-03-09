@extends(session('theme').'.mobile.test.include.app')

@section('content')
<div class="server_test_div">
    <img src="{{ asset('/storage/image/homepage/test/server.png')}}" />
</div>

<style>
    .server_test_div{
        text-align:center;
        padding:5% 0;
    }
</style>
@endsection
