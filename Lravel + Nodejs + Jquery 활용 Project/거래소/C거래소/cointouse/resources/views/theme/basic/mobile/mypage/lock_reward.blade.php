@extends(session('theme').'.mobile.layouts.app')
@section('content')
    @include(session('theme').'.mobile.mypage.include.mypage_hd')

<div class="m_mypage_wrap scrl_wrap m_mypage_wrap-3">

    {{-- 없을때 --}}
    @if($no_lock_reward == true)
    <h2 class="title mb-5">{{ __('myp.nolock') }}</h2>
    {{-- //없을때 --}}
    @else

    <div class="lock_reward_hd">

        {{-- 잠금중-잠금해제중-잠금가능 상태창 --}}
        <div class="lock_indicator">
            <ul>
                <li>
                    <p>{{ __('myp.locking') }}</p>
                    <span class="amount_span">{{$lock_amount}}<span class="currency">{{$coin}}</span></span>
                </li>
                <li>
                    <p>{{ __('myp.unlocking') }}</p>
                    <span class="amount_span">{{$unlocking_amount}}<span class="currency">{{$coin}}</span></span>
                </li>
                <li>
                    <p>{{ __('myp.lockable') }}</p>
                    <span class="amount_span">{{$available_amount}}<span class="currency">{{$coin}}</span></span>
                </li>
            </ul>
        </div>
        {{-- //잠금중-잠금해제중-잠금가능 상태창 --}}

        <input id="lock_coin" type="hidden" value="{{$coin}}">
        <input id="lock_amount" type="hidden" value="{{$lock_amount}}">
        <input id="available_amount" type="hidden" value="{{$available_amount}}"> {{-- 코인수량, 잠금-잠금해제 버튼 --}}
        <div class="lock_div">
            <form method="post" action="{{route('mypage.lock_reward_update', $coin)}}">

                @csrf

                <div class="coin_amount_bar">
                    <label>{{ __('myp.coin_quantity') }}</label>
                    <input id="amount_coin" type="text" name="amount" placeholder="0.00000000" class="mr-2">
                    <select id="select_coin" class="form-control">
                        @foreach($lock_coins as $lock_coin)
                        <option {{$lock_coin->coin == $coin ? 'selected' : ''}}>{{$lock_coin->coin}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="both_btn_group">
                    @if($lock_coin_status != 0)
                    <button type="submit" id="btn_lock" name="operation" value="lock" class="btn_style">
                        <i class="svgicon lock_icon"></i><span>{{ __('myp.lock') }}</span>
                    </button> @endif
                    <button type="submit" id="btn_unlock" name="operation" value="unlock" class="btn_style">
                        <i class="svgicon unlock_icon"></i><span>{{ __('myp.unlock') }}</span>
                    </button>
                </div>

            </form>
        </div>
        {{-- //코인수량, 잠금-잠금해제 버튼 --}}

    </div>

    {{-- 락리워드 내역 --}}
    <div class="lock_reward_group">

        <label class="label">
        {{ __('myp.reward_list') }}
        </label>

        <div class="lock_history_tab">
            <ul>
                <li class="active">{{ __('myp.lock_list') }}</li>
                <li>{{ __('myp.allocation_list') }}</li>
            </ul>
        </div>

        <div class="lock_history_table table_1">

            <table class="table_label">
                <thead>
                    <tr>
                        <th>{{ __('myp.action') }}</th>
                        <th>{{ __('myp.sum') }}</th>
                        <th>{{ __('myp.date') }}</th>
                    </tr>
                </thead>
            </table>

            <table id="lock_history" class="coin_chart_tbl">
                <tbody>
                    @forelse($lock_items as $lock_item) @if($lock_item->operation == 1)
                    <tr class="lock_status">
                        <td>{{ __('myp.lock') }}</td>
                        <td>{{$lock_item->amount}}</td>
                        <td>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($lock_item->created_dt)))}}</td>
                    </tr>
                    @else
                    <tr class="unlock_status">
                        <td>{{ __('myp.unlock') }}</td>
                        <td>{{$lock_item->amount}}</td>
                        <td>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($lock_item->created_dt)))}}</td>
                    </tr>
                    @endif @empty
                    <tr class="none_status">
                        <td colspan="3"><i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('myp.mypage_sentence4') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($lock_items_next_page > 0)
            <div class="table_view_more mt-3">
                <button id="lock_history_view_more" data-next-page="{{$lock_items_next_page}}" data-limit="{{$lock_items_limit}}"><i class="fal fa-plus"></i> {{ __('myp.more')}}</button>
            </div>
            @endif

        </div>


        <div class="lock_history_table table_2 hide">

            <table class="table_label">
                <thead>
                    <tr>
                        <th>{{ __('myp.sum') }}</th>
                        <th>{{ __('myp.date') }}</th>
                    </tr>
                </thead>
            </table>

            <table id="lock_dividend" class="coin_chart_tbl">
                <tbody>
                    @forelse($dividend_items as $dividend_item)
                    <tr>
                        <td>{{$dividend_item->amount}}</td>
                        <td>{{date("Y-m-d H:i:s", strtotime("+9 hours", strtotime($dividend_item->created_dt)))}}</td>
                    </tr>
                    @empty
                    <tr class="none_status">
                        <td colspan="2"><i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('myp.mypage_sentence5') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($dividend_items_next_page > 0)
            <div class="table_view_more mt-3">
                <button id="lock_dividend_view_more" data-next-page="{{$dividend_items_next_page}}" data-limit="{{$dividend_items_limit}}"><i class="fal fa-plus"></i> {{ __('myp.more') }}</button>
            </div>
            @endif

        </div>

    </div>
    {{-- //락리워드 내역 --}}
    @endif

</div>

<script>
    if (typeof __ === 'undefined') { var __ = {}; }
    __.myp = {
        @foreach(__('myp') as $key => $value)
            '{{$key}}':'{{$value}}',
        @endforeach
    }

</script>
@endsection