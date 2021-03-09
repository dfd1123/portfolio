@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">

        <h2 class="title mb-5">{{ __('myp.mypage_sentence1') }}</h2>

        <div class="alarm_setting_group">

            <form action="{{route('mypage.alarm_setting_update')}}" method="post">

                @csrf

                <div class="form-group boxing mb-2">

                    <span>{{ __('myp.event_marketing') }}</span>

                    <div class="alarm_group">
                        <label for="event_alarm_email" class="mr-3">{{ __('myp.email') }} <input id="event_alarm_email" class="ml-1 grayCheckbox" type="checkbox" name="alarm_email" {{$user->alarm_email == 1 ? 'checked' : ''}}></label>
                        <label for="event_alarm_sms">SMS <input id="event_alarm_sms" class="ml-1 grayCheckbox" type="checkbox" name="alarm_sms" {{$user->alarm_sms == 1 ? 'checked' : ''}}></label>
                    </div>

                </div>

                <div class="form-group boxing">

                    <span>{{ __('myp.outalr')}}</span>

                    <div class="alarm_group">
                        <label for="out_alarm_email" class="mr-3">{{ __('myp.email') }} <input id="out_alarm_email" class="ml-1 grayCheckbox" type="checkbox" name="alarm_io_email" {{$user->alarm_io_email == 1 ? 'checked' : ''}}></label>
                        <label for="out_alarm_sms">SMS <input id="out_alarm_sms" class="ml-1 grayCheckbox" type="checkbox" name="alarm_io_sms" {{$user->alarm_io_sms == 1 ? 'checked' : ''}}></label>
                    </div>

                </div>

                <div class="form-group mt-4">

                    <button class="btn_style">
                    {{ __('myp.change_notice_setting') }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection