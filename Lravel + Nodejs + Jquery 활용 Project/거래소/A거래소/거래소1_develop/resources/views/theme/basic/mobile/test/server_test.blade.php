@extends(session('theme').'.mobile.test.include.app')

@section('content')
<div class="server_test_div">
<img src="http://spowide.net/wp-content/uploads/2019/06/logo-new-1-1.png" alt="스포와이드" class="logo-img" data-no-retina="data-no-retina" height="46">
<h1>[안내] 데이터베이스 서버 점검<br />(23시부터 최대 24시까지)</h1>
<p>원활한 서비스 이용을 위해 데이터베이스 </p><p>서버점검이 있겠습니다.</p><p>회원 여러분들은 많은 양해 부탁드립니다.</p>
<style>
    .server_test_div{
        text-align: center;
        padding: 33% 0;
    }

    h1{
        font-size: 18px;
    }

    p{
        font-size: 14px;
        margin: 10px 0;
    }
</style>
@endsection
