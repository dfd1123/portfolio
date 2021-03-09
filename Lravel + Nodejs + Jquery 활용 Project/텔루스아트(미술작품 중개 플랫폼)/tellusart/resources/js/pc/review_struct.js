var str = '';

function normal_review_struct(login_yn, uid, writer_id, review_id, review_body, profile_img, nickname, updated_at, recomend, unrecomend){
    str = '';
    str += '<div class="colist">';
    str += '<p><img src="/storage/image/'+profile_img+'" alt=""/></p>';
    str += '<ul>';
    str += '<li class="none"></li>';
    str += '<li class="leftnone">'+nickname+'</li>';
    str += '<li>'+updated_at+'</li>';
    if(login_yn){
        if(uid == writer_id){
            str += '<li><button type="button" class="on" onclick="alert(\'본인의 코멘트를 추천할 수는 없습니다.\')"><i class="fal fa-thumbs-up"></i> '+recomend+'</button></li>';
            str += '<li><button type="button" onclick="alert(\'본인의 코멘트를 비추천할 수는 없습니다.\')"><i class="fal fa-thumbs-down"></i> '+unrecomend+'</button></li>';
            str += '<li><a href="javascript:void(0);" class="delete" onclick="review_delete('+review_id+')"><i class="far fa-times"></i></a></li>';
            str += '<li id="commnet_'+review_id+'"><a href="#comment_modal" class="modaltrigger  modify"><i class="fal fa-pencil"></i></a></li>';
        }else{
            str += '<li><button type="button" id="trecomend_btn'+review_id+'" class="on" onclick="recomend('+review_id+')"><i class="fal fa-thumbs-up"></i> '+recomend+'</button></li>';
            str += '<li><button type="button" id="tunrecomend_btn'+review_id+'" onclick="unrecomend('+review_id+')"><i class="fal fa-thumbs-down"></i> '+unrecomend+'</button></li>';
        }
    }else{
        str += '<li><button type="button" class="on" onclick="alert(\'로그인을 하셔야 이용 가능한 기능입니다.\');location.href=\'/login\'"><i class="fal fa-thumbs-up"></i> '+recomend+'</button></li>';
        str += '<li><button type="button" onclick="alert(\'로그인을 하셔야 이용 가능한 기능입니다.\');location.href=\'/login\'"><i class="fal fa-thumbs-down"></i> '+unrecomend+'</button></li>';
    }
    str += '</ul>';
    str += '<ul>';
    str += '<li id="treview_body'+review_id+'" class="contxt kr">'+review_body+'</li>';
    str += '</ul>';
    str += '</div>';

    return str;
}

function expert_review_struct(login_yn, uid, writer_id, review_id, review_body, profile_img, username, updated_at, recomend, unrecomend, rating){
    str = '';
    str += '<div class="colum">';
    str += '<div class="columlist">';
    str += '<p><img src="/storage/image/'+profile_img+'" alt=""/></p>';
    str += '<ul>';
    str += '<li>칼럼니스트 : <strong>'+username+'</strong></li>';
    str += '<li>'+review_body+'</li>';
    str += '<li class="star">';

    if(rating >= 0.5)
    {
        str += '<i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 1.0)
    {
        str += '<i class="fas fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 1.5)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 2.0)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 2.5)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 3.0)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fal fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 3.5)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 4.0)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fal fa-star"></i>';
    }
    else if(rating >= 4.5)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>';
    }
    else if(rating >= 5.0)
    {
        str += '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
    }

    str += '<span class="en">'+rating+'</span>';
    str += '</li>';
    str += '</ul>';

    if(login_yn){
        if(uid == writer_id){
            str += '<div class="expert_review_btn">';
            str += '<ul>';
            str += '<li data-id="'+review_id+'">';
            str += '<a href="#expert_comment_modal" class="modaltrigger modify"><i class="fal fa-pencil"></i></a>';
            str += '</li>';
            str += '<li>';
            str += '<a href="javascript:void(0);" class="delete" onclick="expert_review_delete('+review_id+')"><i class="far fa-times"></i></a>';
            str += '</li>';
            str += '</ul>';
            str += '</div>';
        }
    }

    str += '</div>';
    str += '</div>';

    return str;

}