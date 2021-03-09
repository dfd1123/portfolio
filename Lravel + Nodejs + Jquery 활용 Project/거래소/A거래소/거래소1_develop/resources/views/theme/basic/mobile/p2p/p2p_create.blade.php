<div class="modal_popup p2p_modal_popup p2p_create_popup" id="p2pCreate">
    
    <div id="cancel_btn">
        <span></span>
    </div>

    <h1 class="notosans">{{ __('ptop.entollment_out_trade') }}</h1>
    
    <div class="p-3 scrl_wrap">
        
            <form action="{{route('p2p_insert')}}" method="POST" id="p2p_create">
        
                @csrf
                <div class="form-group not_flex">
                    <label class="label">{{__('ptop.seller')}}</label>
                    <div class="form-control-group">
                        <input type="radio" id="p2p_type_buy" name="p2p_type" value="buy"  class="hide" checked>
                        <label for="p2p_type_buy" class="p2p_type_btn mr-2 p2pTypebuy">{{ __('ptop.buy') }}</label>
        
                        <input type="radio" id="p2p_type_sell" name="p2p_type" value="sell"  class="hide">
                        <label for="p2p_type_sell" class="p2p_type_btn p2pTypesell">{{ __('ptop.sell') }}</label>
                    </div>
                </div>
        
                <div class="form-group not_flex">
                    <label class="label">{{__('ptop.coin_list')}}</label>
                    <div class="form-group not_flex">
                        <select name="coin_type" class="form-control coin_type_slc" id="selectBoxs" >
                            <option value='' selected>{{ __('ptop.choice') }}</option>
                            @foreach($coins as $coin)
                            <option value="{{ $coin->api }}">{{ __('coin_name.'.$coin->api) }}/{{ $coin->symbol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="form-group not_flex">
                    <label class="label">{{ __('ptop.quantity') }}</label>
                    <div class="form-control-group">
                        <input type="number" name="coin_amount" class="form-control" placeholder="0"  step="any">
                        <input class="form-control ml-2 coin_type_choiced" id="this_coin" readonly="readonly">
                    </div>
                </div>
        
                <div class="form-group not_flex">
                    <label class="label">{{ __('ptop.price') }}</label>
                    <div class="form-control-group">
                        <select name="country_money" id="selectBoxes"  class="form-control mr-2 small_slc">
                            <option value='' selected>{{__('ptop.choice_money')}}</option>
                            <option value="KRW">KRW</option>
                            <option value="JPY">JPY</option>
                        </select>
                        <input type="number" name="coin_price" class="form-control" >
                    </div>
                </div>
        
                <div class="form-group not_flex"> 
                    <label class="label">{{ __('ptop.wallet_address') }}</label>
                    <input type="text" name="wt_coin_address" class="form-control wallet_line" placeholder="{{ __('ptop.inaddress') }}" >
                </div>
        
                <div class="form-group not_flex"> 
                    <label class="label">{{ __('ptop.account_info') }}</label>
                    <div class="form-control-group">
                        <input name="wt_bank" class="form-control mr-2 small_slc" placeholder="{{__('ptop.bank_name')}}">
                        <input name="jp_bank" class="form-control mr-2 small_slc if_jp hide" placeholder="{{__('ptop.jp_bank')}}" ><br class="if_jp hide">
                        <input name="wt_account_name" class="form-control mr-2 small_slc" placeholder="{{__('ptop.name')}}">
                        <input type="number" name="wt_account" placeholder="{{ __('ptop.inaccount') }}"   class="form-control">
                    </div>
                </div>
        
                <div class="form-group not_flex">
                    <label class="label">{{ __('ptop.contents') }}</label>
                    <textarea placeholder="{{ __('ptop.contents') }}" type="text" class="form-control" name="wt_cont" ></textarea>
                </div>
                
                
                <button type="submit" name="create" class="btn_style btn_fix_mb">
                    {{ __('ptop.registration') }}
                </button>
        
        
            </form>
        
    </div>

</div>