@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
    <div class="private">
        <div class="prtxt" style="height:auto;">
            @if($howtouse->mb_img1 != NULL)
                <img src="{{asset('/storage/image/howtouse/'.$howtouse->mb_img1)}}" alt="" />
            @endif
            @if($howtouse->mb_img2 != NULL)
                <img src="{{asset('/storage/image/howtouse/'.$howtouse->mb_img2)}}" alt="" />
            @endif
            @if($howtouse->mb_img3 != NULL)
                <img src="{{asset('/storage/image/howtouse/'.$howtouse->mb_img3)}}" alt="" />
            @endif
        </div>
    </div>
</div>

@endsection