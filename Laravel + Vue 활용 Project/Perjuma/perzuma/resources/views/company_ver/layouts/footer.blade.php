    <footer id="footer">
        <div class="tab" id="home">
            <img id="homeimg" src="{{asset('/images/1.svg')}}" alt="menu">
            <p>홈</p>
        </div>
        <div class="tab" id="req">
            <img id="reqimg" src="{{asset('/images/4.svg')}}" alt="menu">
            <p>요청</p>
        </div>
        <div class="tab" id="bid">
            <img id="bidimg" src="{{asset('/images/3.svg')}}" alt="menu">
            <p>입찰</p>
        </div>
        <div class="tab" id="profile">
            <img id="profileimg" src="{{asset('/images/2.svg')}}" alt="menu">
            <p>프로필</p>
        </div>
    </footer>
<script>
    $(document).ready(function(){
        var urlpath = window.location.pathname.split('/')[2];
        if(urlpath==undefined || urlpath=='detail'){
            $('#home').addClass('active');
            $('#homeimg').attr('src','{{asset('/images/11.svg')}}');
        }
        else if(urlpath=='company_request'
        ||urlpath=='company_bidding_detail'){
            $('#req').addClass('active');
            $('#reqimg').attr('src','{{asset('/images/44.svg')}}');
        }
        else if(urlpath=='company_bidding'
        ||urlpath=='company_bidding_regist'){
            $('#bid').addClass('active');
            $('#bidimg').attr('src','{{asset('/images/33.svg')}}');
        }
        else if(urlpath=='company_myagent'){
            $('#profile').addClass('active');
            $('#profileimg').attr('src','{{asset('/images/22.svg')}}');
        }

    });
    var originalSize = $(window).width() + $(window).height();
    $(window).resize(function() {
            
        // 처음 사이즈와 현재 사이즈가 변경된 경우
        // 키보드가 올라온 경우
        if($(window).width() + $(window).height() != originalSize) {
            $('#footer').hide();
        }else{
            $('#footer').show();
        }
    });

    $('#home').on('click',function(){
        location.href='/company_ver';
    });
    $('#req').on('click',function(){
        location.href='/company_ver/company_request';
    });
    $('#bid').on('click',function(){
        location.href='/company_ver/company_bidding';
    });
    $('#profile').on('click',function(){
        location.href='/company_ver/company_myagent';
    });
</script>
<style>
    #footer{
        background-color:#fff;
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        z-index:5;
        box-shadow: 0 -30px 30px 0 rgba(0, 0, 0, 0.1);
        display:flex;
    }
    .tab{
        width:25%;
        padding:0.8em;
        text-align:center;
    }
    .tab img{
        width:1.7em;
        height:1.7em;
        margin-bottom:0.5em;
    }
    .tab p{
        color:#5d5d5d;
        font-size:0.7em;
    }
    .tab p.active{
        color:#007bd2;
    }
</style>