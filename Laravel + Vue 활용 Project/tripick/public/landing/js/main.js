$(function(){
    $("#t_fullpage").fullpage({
        licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',
        menu: '#t_gnb',
        anchors: ['main', 'appdown1', 'appdown2', 'appdown3', 'plannerreg']
        ,
        afterLoad: function(origin, destination, direction){
            if(destination.index==0){
                $("#t_hd").removeClass("white navy logowhite");
                $(".bg_circle").removeClass("active");
                $("#t_main").addClass("active");
            }
            else if(destination.index==1){
                $("#t_hd").removeClass("navy logowhite");
                $("#t_hd").addClass("white");
                $("t_main, .bg_airplane, .tab__img--ms, #t_main, .tab__img--bg").removeClass("active");
                $("#t_appdown1 .bg_airplane, .bg_circle").addClass("active");
            }
            else if(destination.index==2){
                $("#t_hd").removeClass("white");
                $("#t_hd").addClass("navy logowhite");
                $(".bg_airplane, .bgimg__pop, .bg_circle, .bgimg__bg").removeClass("active");
                $("#t_appdown2 .bg_airplane, .tab__img--ms, .tab__img--bg").addClass("active");
                var prace_num=0;
                prace();
                function prace(){
                    prace_num++;
                    if(prace_num<=100){
                        $(".tr_prace").each(function(){
                            count_max=$(this).attr("data-num");
                            const result_count = numberWithCommas(Math.ceil(count_max*prace_num/100));
                            $(this).text(result_count);
                        })
                        $(".tr_star").each(function(){
                            count_max=$(this).attr("data-num");
                            const result_count = numberWithCommas(Math.max(count_max*prace_num/1000));
                            $(this).text(result_count);
                        })
                    }
                else{return false;}
                setTimeout(prace,10)
                }
            }
            else if(destination.index==3){
                $("#t_hd").removeClass("navy");
                $("#t_hd").addClass("white logowhite");
                $(".bg_airplane, .tab__img--ms, .plan--title, .tab__img--bg").removeClass("active");
                $(".next_arrow, #t_appdown3 .bg_airplane, .bgimg__pop, .bgimg__bg").addClass("active");
            }
            else if(destination.index==4){
                $("#t_hd").removeClass("navy");
                $("#t_hd").addClass("white logowhite");
                $(".next_arrow, .bg_airplane, .bgimg__pop, .bgimg__bg").removeClass("active");
                $("#t_plannerreg .bg_airplane, .plan--title").addClass("active");
            }
        }
        //,
        // onLeave: function(origin, destination, direction){
        // }
    })
    
    //tab콘텐츠 넘기기 + 숫자카운트
    $(".tab1__title>a").on("click",function(){
        $(".tab1__title").removeClass("active");
        $(this).parent(".tab1__title").addClass("active");
        var tab_eq=$(this).parent(".tab1__title").index();
        $(".travel1--tab").removeClass("active");
        $(".travel1--tab"+(tab_eq+1)).addClass("active");
        $(".tab__img1").removeClass("active");
        $(".tab__img1_"+(tab_eq+1)).addClass("active");
    })
    $(".tab2__title>a").on("click",function(){
        $(".tab2__title").removeClass("active");
        $(this).parent(".tab2__title").addClass("active");
        var tab_eq=$(this).parent(".tab2__title").index();
        console.log(tab_eq);
        $(".travel2--tab").removeClass("active");
        $(".travel2--tab"+(tab_eq+1)).addClass("active");
        var prace_num=0;
        prace();
        function prace(){
            prace_num++;
            if(prace_num<=100){
                $(".tr_prace").each(function(){
                    count_max=$(this).attr("data-num");
                    const result_count = numberWithCommas(Math.ceil(count_max*prace_num/100));
                    $(this).text(result_count);
                })
                $(".tr_star").each(function(){
                    count_max=$(this).attr("data-num");
                    const result_count = numberWithCommas(Math.max(count_max*prace_num/1000));
                    $(this).text(result_count);
                })
            }
        else{return false;}
        setTimeout(prace,10)
        }
    })
})

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}