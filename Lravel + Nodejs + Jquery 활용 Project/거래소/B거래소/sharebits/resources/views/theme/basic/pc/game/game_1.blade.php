@extends(session('theme').'.pc.layouts.app')

@section('content')

<div class="game_wrap">
    
    <div class="hd_bg"></div>
    
    <div class="game_inner">
        
        <h2>击球游戏</h2>
        
        <div class="game_group">
            
            <div class="form-group">
                
                {{-- 나의 UCSS 잔액 --}}
                <label>我的 UCSS 余额</label>
                
                <input type="text" name="" value="25,000.00" class="" readonly="readonly">
                
                <span class="currency">UCSS</span>
                
            </div>
            
            <div class="form-group">
                
                {{-- 변환할 UCSS --}}
                <label>可转换 UCSS</label>
                
                <input type="text" name="" placeholder="0.00" class="">
                
                <span class="currency">UCSS</span>
                
            </div>
            
            {{-- 게임머니로 전환하기 버튼 --}}
            <button type="button" class="btn_style cancel_btn">
                <i></i>
                转换成游戏币
            </button>
            
            {{-- 게임 시작하기 버튼 --}}
            <a href="#" target="_blank" class="btn_style">
                GAME START
            </a>
            
            
            
        </div>
        
    </div>
    
</div>

@endsection