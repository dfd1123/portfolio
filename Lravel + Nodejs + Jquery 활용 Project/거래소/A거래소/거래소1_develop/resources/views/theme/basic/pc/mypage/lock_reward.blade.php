@extends(session('theme').'.pc.layouts.app') 
@section('content')
    @include(session('theme').'.pc.mypage.include.mypage_hd')

<div class="mypage_wrap">

    <div class="mypage_inner">
        @if($no_lock_reward == true)
        <h2 class="title mb-5">{{ __('myp.nolock') }}</h2>
        @else
        <div class="lock_reward_hd">

            <input id="lock_coin" type="hidden" value="{{$coin}}">
            <input id="lock_amount" type="hidden" value="{{$lock_amount}}">
            <input id="available_amount" type="hidden" value="{{$available_amount}}">

            <form method="post" action="{{route('mypage.lock_reward_update', $coin)}}">

                @csrf

                <div class="form-group coin_amount_bar">
                    <label>{{ __('myp.coin_quantity') }}</label>
                    <input id="amount_coin" type="text" name="amount" placeholder="0.00000000" class="mr-2">
                    <select id="select_coin" class="form-control select_style">
                        @foreach($lock_coins as $lock_coin)
                        <option {{$lock_coin->coin == $coin ? 'selected' : ''}}>{{$lock_coin->coin}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="both_btn_group">
                    @if($lock_coin_status != 0)
                    <button type="submit" id="btn_lock" name="operation" value="lock" class="btn_style point_lock_clr lock_icon" onclick="func_confirm()">
                        {{ __('myp.lock') }}</span>
                    </button> @endif
                    <button type="submit" id="btn_unlock" name="operation" value="unlock" class="btn_style unlock_icon">
                        <span>{{ __('myp.unlock') }}</span>
                    </button>
                </div>

            </form>

            <div class="lock_indicator mt-3 mb-5">
                <ul>
                    <li>
                        <p class="mb-2">{{ __('myp.locking') }}</p>
                        <span class="amount_span">{{$lock_amount}}<span class="currency">{{$coin}}</span></span>
                    </li>
                    <li>
                        <p class="mb-2">{{ __('myp.unlocking') }}</p>
                        <span class="amount_span">{{$unlocking_amount}}<span class="currency">{{$coin}}</span></span>
                    </li>
                    <li>
                        <p class="mb-2">{{ __('myp.lockable') }}</p>
                        <span class="amount_span">{{$available_amount}}<span class="currency">{{$coin}}</span></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="lock_reward_group">

            <div class="lock_history_tab mt-3 after_line">
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
                            <th>{{ __('myp.operating_date') }}</th>
                        </tr>
                    </thead>
                </table>

                <div class="lock_list_scroll">
                    <table id="lock_history" class="coin_chart_tbl">
                        <tbody>
                            @forelse($lock_items as $lock_item) @if($lock_item->operation == 1)
                            <tr class="lock_status">
                                <td>{{ __('myp.lock') }}</td>
                                <td>{{$lock_item->amount}}</td>
                                <td>{{$lock_item->created_dt}}</td>
                            </tr>
                            @else
                            <tr class="unlock_status">
                                <td>{{ __('myp.unlock') }}</td>
                                <td>{{$lock_item->amount}}</td>
                                <td>{{$lock_item->created_dt}}</td>
                            </tr>
                            @endif @empty
                            <tr class="none_status">
                                <td colspan="3"><img src="/images/icon_notice.svg" alt="" class="btn_notice align-text-top">{{ __('myp.mypage_sentence4') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lock_items_next_page > 0)
                <div class="table_view_more mt-3">
                    <button id="lock_history_view_more" data-next-page="{{$lock_items_next_page}}" data-limit="{{$lock_items_limit}}"> {{ __('myp.more')}}</button>
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
                            <td>{{$dividend_item->created_dt}}</td>
                        </tr>
                        @empty
                        <tr class="none_status">
                            <td colspan="2"><img src="/images/icon_notice.svg" alt="" class="btn_notice">{{ __('myp.mypage_sentence5') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($dividend_items_next_page > 0)
                <div class="table_view_more mt-3">
                    <button id="lock_dividend_view_more" data-next-page="{{$dividend_items_next_page}}" data-limit="{{$dividend_items_limit}}"> {{ __('myp.more') }}</button>
                </div>
                @endif

            </div>

        </div>
        @endif
    </div>

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