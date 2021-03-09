<div class="mypage_hd">

    <h1>{{ __('myp.mypage')}}</h1>

</div>

<div class="sub_nav_bar">

    <ul>
        <li class="{{request()->is('mypage/alarm_setting*') ? 'active' : ''}}">
            <a href="{{ route('mypage.alarm_setting') }}">
                <span>
                {{ __('myp.notice_setting')}}
                </span>
            </a>
        </li>
        <li class="{{request()->is('mypage/security_setting*') ? 'active' : ''}}">
            <a href="{{ route('mypage.security_setting') }}">
                <span>
                {{ __('head.security_authentication2')}}
                </span>
            </a>
        </li>
        @if($status == 3)
            <li class="{{request()->is('mypage/otp_setting*') ? 'active' : ''}}">
                <a href="{{ route('mypage.otp_setting') }}">
                    <span>
                    {{ __('myp.enhanced_security')}}
                    </span>
                </a>
            </li>
        @endif
        <!--
        <li class="{{request()->is('mypage/lock_reward*') ? 'active' : ''}}">
            <a href="{{ route('mypage.lock_reward') }}">
                <span>
                {{ __('myp.lock_reword')}}
                </span>
            </a>
        </li>
        -->
        <li class="{{request()->is('mypage/password_change*') ? 'active' : ''}}">
            <a href="{{ route('mypage.password_change') }}">
                <span>
                {{ __('myp.change_password')}}
                </span>
            </a>
        </li>
        <li class="{{request()->is('mypage/event_coupon*') ? 'active' : ''}}">
            <a href="{{ route('mypage.event_coupon') }}">
                <span>
                이벤트 당첨코인
                </span>
            </a>
        </li>
        <li class="{{request()->is('mypage/member_out*') ? 'active' : ''}}">
            <a href="{{ route('mypage.member_out') }}">
                <span>
                회원탈퇴
                </span>
            </a>
        </li>
    </ul>

</div>