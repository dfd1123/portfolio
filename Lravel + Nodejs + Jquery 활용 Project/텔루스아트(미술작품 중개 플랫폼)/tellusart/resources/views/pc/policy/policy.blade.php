@extends('pc.layouts.app')

@section('content')

<style>
	.sub_spot.privacy{position:absolute;background:#413e70 !important;height:60px;top:0;padding:0;left:0;}
	#header .gnb{margin-top:10px;}
	#container.private{background:#f9f9f9;margin-top:60px;padding:40px 0;}
	.prtxt{display:block;width:100%;margin:0 auto;padding:15px 0;max-width:1000px;min-height:700px;background: #fff;padding:30px 15px;box-sizing:border-box;overflow-y: scroll;}
	#container.private h3{display:block;width:100%;font-size:30px;color:#222;padding:10px 0 20px 0;letter-spacing:-1px;font-weight:400;overflow:hidden;}
	.prtxt>p{display:block;border:1px solid #ddd;padding:10px;border-radius:3px;background:#fff;height:650px;overflow-y:scroll;text-align:left;}
	.prtab{display:block;width:100%;margin:0 auto;padding:15px 0;max-width:1000px;}
	.prtab ul{padding:0px;margin:0px;}
	.prtab ul li{width:50%;float:left;}
	.prtab ul li a{display:block;width:100%;box-sizing:border-box;text-align:center;font-size:15px;padding:15px 0;color:#fff;border-left:1px solid rgba(256,256,256,0.3);letter-spacing:-1px;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #e3e3e3), color-stop(1, #a6a3a3));
	background: -moz-linear-gradient(top, #e3e3e3 0%, #a6a3a3 100%);
	background: -webkit-linear-gradient(top,  #e3e3e3 0%, #a6a3a3 100%);
	background: -o-linear-gradient(top,  #e3e3e3 0%, #a6a3a3 100%);
	background: -ms-linear-gradient(top,  #e3e3e3 0%, #a6a3a3 100%);
	background: linear-gradient(top,  #e3e3e3 0%, #a6a3a3 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e3e3e3', endColorstr='#a6a3a3', gradientType=0);}
	.prtab ul li:first-child a{border-left:none;}
	.prtab ul li a.active{background:#fea803;font-weight:bold}
	.prtxt::-webkit-scrollbar{
		background-color:transparent;
	}
	.prtxt::-webkit-scrollbar-thumb{
		background-color:rgba(255,255,255,0.4);
	}
</style>
<div class="sub_spot privacy">
	
</div>
<div id="container" class="private">
		<h3>{{$policy->title}}</h3>
		<div class="prtab">
			<ul>
                <li><a href="{{route('policy.policy', 1)}}" {{($policy->id == 1)?"class=active":""}} >개인정보 취급방침</a></li>
                <li><a href="{{route('policy.policy', 2)}}"  {{($policy->id == 2)?"class=active":""}} >서비스 이용약관</a></li>
			</ul>
		</div>
		<div class="prtxt">
            <div style="text-align:left;">
                {!! $policy->mobile_contents !!}
            </div>
		</div>

</div>

@endsection