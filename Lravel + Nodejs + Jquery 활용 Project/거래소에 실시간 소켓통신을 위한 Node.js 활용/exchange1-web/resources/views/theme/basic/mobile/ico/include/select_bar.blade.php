<div class="select_wrap">
    <div class="select_type">
        <label for="ico_type_check">
            <span class="type_tit">
                @if($category=='all') {{ __('icoo.all')}}
                @elseif($category=='1') {{ __('icoo.ing')}}
                @elseif($category=='3') {{ __('icoo.end')}}
                @elseif($category=='2') {{ __('icoo.will_ing')}}
                @elseif($category=='4') {{ __('icoo.sold_out')}}
                @endif
            </span>
            <i class="fal fa-angle-down point_clr_2"></i>
        </label>
    </div>
        
    <input id="ico_type_check" type="checkbox" class="hide">
    
    <ul class="type_list">
        <li>
            <a href="{{ ($pagename=='ico')?route('ico_list','all'):route('my_ico','all') }}">
            {{ __('icoo.all')}}
            </a>
        </li>
        <li>
            <a href="{{ ($pagename=='ico')?route('ico_list','1'):route('my_ico','1') }}">
            {{ __('icoo.ing')}}
            </a>
        </li>
        <li>
            <a href="{{($pagename=='ico')?route('ico_list','3'):route('my_ico','3') }}">
            {{ __('icoo.end')}}
            </a>
        </li>
        <li>
            <a href="{{($pagename=='ico')?route('ico_list','2'):route('my_ico','2') }}">
            {{ __('icoo.will_ing')}}
            </a>
        </li>
        <li>
            <a href="{{($pagename=='ico')?route('ico_list','4'):route('my_ico','4') }}">
            {{ __('icoo.sold_out')}}
            </a>
        </li>
    </ul>
</div>
    