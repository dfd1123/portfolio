<div class="agree_modal_popup" id="agree1_popup">
    
    <h1 class="mt-2 mb-4">{{ __('login.agree_tit') }}</h1>
        
    <div class="form_div">
        
        <div class="form-control mb-4 agree_text_con ios-scroll" name="description">
            {!! $term->{'private_infor_term_'.config('app.country')} !!}
        </div>
        
    </div>
    
    <button type="submit" class="btn_style_confirm agree_check">
    {{ __('login.check') }}
    </button>

</div>