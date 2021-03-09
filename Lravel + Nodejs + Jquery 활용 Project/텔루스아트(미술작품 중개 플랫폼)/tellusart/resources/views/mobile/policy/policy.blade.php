@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
    <div class="private">
        <h3 >{{$policy->title}}</h3>
        <div class="prtab">
            <ul>
                <li><a href="{{route('policy.policy', 1)}}" {{($policy->id == 1)?"class=active":""}} >개인정보 취급방침</a></li>
                <li><a href="{{route('policy.policy', 2)}}"  {{($policy->id == 2)?"class=active":""}} >서비스 이용약관</a></li>
            </ul>
        </div>
        <div class="prtxt">
            <div>
                {!! $policy->mobile_contents !!}
            </div>
        </div>
    </div>
</div>

@endsection