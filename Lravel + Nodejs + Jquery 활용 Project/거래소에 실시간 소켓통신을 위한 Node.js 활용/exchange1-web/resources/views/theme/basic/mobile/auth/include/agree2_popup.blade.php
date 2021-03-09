<div class="agree_modal_popup" id="agree2_popup">
    
    <h1 class="mt-2 mb-4">{{ __('login.agree_tit') }}</h1>
    
    <div class="form_div">
        
        <div class="form-control mb-4 agree_text_con ios-scroll" name="description">
            {!! $term->{'use_term_'.config('app.country')} !!}
        </div>
        
    </div>
    
    <button type="submit" class="btn_style agree_check">
        CHECK
    </button>
    
</div>