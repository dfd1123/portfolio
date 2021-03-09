<nav class="navbar-sharebits">

	<div class="nav_container">

		<a href="{{ url('/?country='.config('app.country')) }}" class="logo"> <img src="{{ asset('/storage/image/homepage/sharebits_logo_sub.svg')}}" alt="logo"/> </a>

		<ul class="center_nav">

			<li class="{{request()->is('market*') ? 'active' : ''}} main_menu">
				<a href="{{ route('marketUCSS','btc') }}">{{ __('head.trademarket') }} </a>
			</li>
			<li class="{{request()->is('trans_wallet*') ? 'active' : ''}} main_menu">
				<a href="{{ route('trans_wallet') }}">{{ __('head.inout') }}</a>
			</li>
			<li class="{{request()->is('my_asset*') ? 'active' : ''}} main_menu">
				<a href="{{ route('my_asset.index') }}">{{ __('head.my_asset') }}</a>
			</li>
			<li class="{{request()->is('ico*') || request()->is('my_ico*') ? 'active' : ''}} main_menu drop_menu">
				<a href="{{ route('ico_list','all') }}">ICO</a>
				<ul class="drop_down">
					<li>
						<a href="{{ route('ico_list','all') }}">ICO</a>
					</li>
					<li>
						<a href="{{ route('my_ico','all') }}">{{ __('head.myico')}}</a>
					</li>
					<li>
						<a href="{{ route('ico_history') }}">{{ __('head.with')}}</a>
					</li>
				</ul>
			</li>
			<li class="{{request()->is('p2p*') ? 'active' : ''}} main_menu drop_menu">
				<a href="{{ route('p2p_list','all') }}">{{ __('head.out_trade') }}</a>
				<ul class="drop_down">
					<li>
						<a href="{{ route('p2p_list','all') }}">{{ __('head.out_trade') }}</a>
					</li>
					<li>
						<a href="{{ route('p2p_onprogress','all') }}">{{ __('head.info') }}</a>
					</li>
					<li>
						<a href="{{ route('p2p_history') }}">{{ __('head.complete') }}</a>
					</li>
				</ul>
			</li>
			@if( config('app.country') == 'ch' )
			<li class="main_menu">
				<a href="{{ route('game_ch') }}">游戏</a>
			</li>
			@else
			
			@endif
			<li class="{{(request()->is('notice*') || request()->is('event*') || request()->is('faq*') || request()->is('qna*')) ? 'active' : ''}} main_menu drop_menu">
				<a href="{{ route('notice') }}">{{ __('head.user_center') }}</a>
				<ul class="drop_down">
					<li>
						<a href="{{ route('notice') }}">{{ __('head.notice') }}</a>
					</li>
					<li>
						<a href="{{ route('event') }}">{{ __('head.event') }}</a>
					</li>
					<li>
						<a href="{{ route('faq') }}">FAQ</a>
					</li>
					<li>
						<a href="{{ route('qna_list') }}">{{ __('head.contact') }}</a>
					</li>
				</ul>
			</li>
		</ul>

		<ul class="right_nav">
			<li class="lang_list_btn">
				<a href="#">{{strtoupper(config('app.country'))}}</a>
				<ul class="lang_list">
					<li class="lang_li">
						<a href="/?country=en">ENG</a>
					</li>
					<li class="lang_li">
						<a href="/?country=kr">KR</a>
					</li>
					<li class="lang_li">
						<a href="/?country=jp">JP</a>
					</li>
					<li class="lang_li">
						<a href="/?country=ch">CH</a>
					</li>
				</ul>
			</li>
			@auth

			<li class="after_login_li">
				<img src="{{ asset('/storage/image/homepage/icon/mypage_icon.svg')}}" alt="mypage_icon" class="mypage_icon">
				<span>{{Auth::user()->fullname}}</span>
				<i class="fas fa-caret-down"></i>

				<ul class="mypage_toggle">
					<li>
						<a href="{{ route('mypage.alarm_setting') }}">{{ __('head.mypage') }}</a>
					</li>
					<li>
						<a href="{{ route('mypage.alarm_setting') }}">{{ __('head.notice_setting') }}</a>
					</li>
					<li>
						<a href="{{ route('mypage.lock_reward') }}">{{ __('head.lock_reward') }}</a>
					</li>
					<li>
						<a href="{{ route('mypage.password_change') }}">{{ __('head.change_password') }}</a>
					</li>
					<li>
						<a href="{{ route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LOGOUT</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</li>
				</ul>
			</li>
			@else
			<li>
				<a href="{{ route('login') }}"> LOGIN </a>
			</li>
			<li>
				<a href="{{ route('register_agree') }}"> JOIN US </a>
			</li>
			@endauth
		</ul>

	</div>

</nav>