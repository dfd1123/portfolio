<div class="modal_popup hide p2p_modal_popup" id="p2pCreate">

    <h1 class="notosans mb-5">{{ __('ptop.entollment_out_trade') }}</h1>

    <form action="{{route('p2p_insert')}}" method="POST" id="p2p_create">

        @csrf
        <div class="form-group">
            <label>{{__('ptop.trade_kind')}}</label>
            <div class="form-control-group">
                <input type="radio" id="p2p_type_buy" name="p2p_type" value="buy"  class="hide" checked>
                <label for="p2p_type_buy" class="p2p_type_btn mr-2 p2pTypebuy">{{ __('ptop.buy') }}</label>

                <input type="radio" id="p2p_type_sell" name="p2p_type" value="sell"  class="hide">
                <label for="p2p_type_sell" class="p2p_type_btn p2pTypesell">{{ __('ptop.sell') }}</label>
            </div>
        </div>

        <div class="form-group">
            <label>{{__('ptop.coin_list')}}</label>
            <div class="form-control-group">
                <select name="coin_type" class="form-control coin_type_slc" id="selectBoxs" >
                    <option value='' selected>{{ __('ptop.choice') }}</option>
                    <option value="btc">{{ __('ptop.btc') }}</option>
                    <option value="eth">{{ __('ptop.eth') }}</option>
                    <option value="rsdc">{{ __('ptop.rsdc') }}</option>
                    <option value="omg">{{ __('ptop.omg') }}</option>
                    <option value="icx">{{ __('ptop.icx') }}</option>
                    <option value="gnt">{{ __('ptop.gmt') }}</option>
                    <option value="zil">{{ __('ptop.zil') }}</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('ptop.quantity') }}</label>
            <div class="form-control-group">
                <input type="number" name="coin_amount" class="form-control" placeholder="0"  step="any">
                <input class="form-control ml-2 coin_type_choiced" id="this_coin" readonly="readonly">
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('ptop.price') }}</label>
            <div class="form-control-group">
                <select name="country_money" id="selectBoxes"  class="form-control mr-2 small_slc">
                    <option value='' selected>{{__('ptop.choice_money')}}</option>
                    <option value="KRW">KRW</option>
                    <option value="JPY">JPY</option>
                </select>
                <input type="number" name="coin_price"   class="form-control">
            </div>
        </div>

        <div class="form-group"> 
            <label></label>
            <div class="form-control-group">
                <input type="text" name="wt_coin_address" class="form-control" placeholder="{{ __('ptop.inaddress') }}" >
            </div>
        </div>

        <div class="form-group"> 
            <label>{{ __('ptop.account_info') }}</label>
            <div class="form-control-group">
                <input name="wt_bank"  class="form-control mr-2 small_slc" placeholder="{{__('ptop.bank_name')}}">
                <input name="jp_bank" class="form-control mr-2 small_slc if_jp hide" placeholder="{{__('ptop.jp_bank')}}" ><br class="if_jp hide">
                <input name="wt_account_name" class="form-control mr-2 small_slc" placeholder="{{__('ptop.name')}}">
                <input type="number" name="wt_account" placeholder="{{ __('ptop.inaccount') }}"   class="form-control">
            </div>
        </div>

        <div class="form-group">
            <textarea placeholder="{{ __('ptop.contents') }}" type="text" class="form-control" name="wt_cont" ></textarea>
        </div>

        <div class="both_btn_group pl-5 pr-5 mt-4">

            <button type="submit" name="create" class="btn_style">
            {{ __('ptop.registration') }}
            </button>

            <a href="#" class="btn_style cancel_btn">
            {{ __('ptop.cancel') }}
            </a>

        </div>


    </form>

</div>