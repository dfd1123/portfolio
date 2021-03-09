<div id="fullscreen_modal" class="hide"></div>
<div class="posi_wrap" style="display:none;">
    <div>
        <div id="loading"></div>
    </div>
</div>
<div class="modal_popup p2p_modal_popup p2p_apply_popup" id="p2pApply1">
    
    <div id="cancel_btn">
        <span></span>
    </div>
    
    <h1 class="notosans mb-3" id="modal_title"></h1>

    <form method="post" action="{{route('p2p_apply')}}" id="p2p_apply">
        @csrf
            <input type="hidden" name="p_id" value="">
            <input type="hidden" name="type" value="">
        
        <div class="form-group">
            <label>{{__('ptop.seller')}}</label>
            <div class="form-control-group">
                <div class="form-control not_border">
                    <span id="pop_name"></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{__('ptop.coin_list')}}</label>
            <div class="form-control-group">
                <div class="form-control not_border">
                    <span id="coin_name"> </span> /
                    <span class="currency_clr modal_coin_type" > </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{__('ptop.quantity')}}</label>
            <div class="form-control-group">
                <div class="form-control not_border">
                    <span class="pr-2" id="modal_amount"></span><span class="currency modal_coin_type"></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{__('ptop.price')}}</label>
            <div class="form-control-group">
                <div class="form-control not_border">
                    <span class="pr-2" id="modal_price"></span><span class="currency" id="country_money"></span>
                </div>
            </div>
        </div>

        <div class="form-group not_flex mt-1">
            <label>{{__('ptop.wallet_address')}}</label>
            <div class="form-control-group wallet_address mt-2">
                <input type="text" name="coin_address" class="form-control added_line" placeholder="{{ __('ptop.inaddress') }}">
            </div>

        </div>

        <div class="form-group not_flex mt-1">
            <label>{{__('ptop.refund_account')}}</label>
            <div class="form-control-group mt-2">

                <input name="bank"  class="form-control mr-2 small_slc" placeholder="{{__('ptop.bank_name')}}">
                <input name="jp_bank" class="form-control mr-2 small_slc if_jp hide" placeholder="{{__('ptop.jp_bank')}}" >
                <input name="account_name" class="form-control mr-2 small_slc" placeholder="{{__('ptop.name')}}">
                <input type="number" name="account" placeholder="{{ __('ptop.inaccount') }}"  class="form-control">

            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn_style btn_fix_mb">
            {{__('ptop.apply')}}
            </button>
        </div>
        
    </form>

</div>

<script>
    
</script>