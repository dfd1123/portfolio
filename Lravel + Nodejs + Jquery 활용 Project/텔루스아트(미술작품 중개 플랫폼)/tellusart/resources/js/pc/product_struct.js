function glist_element(id, ca_id, ca_name, img, profile_img, title, artist_name, art_width_size, art_height_size, get_like, review_cnt, cash_price, coin_price){	
	
	var elem = document.createElement('div');
	
	elem.className = 'item';
	

	var str = '<a href="/products/' + id + '"><p class="is-loading"><img src="/storage/image/product/'+img+'" alt=""/></p></a>';
	str += '<div class="peo_txt">';
	str += '<p><img src="/storage/image/'+profile_img+'" alt="작가프로필사진"/></p>';
	str += '<ul>';
	str += '<li>'+title+'</li>';
	str += '<li><em>작가명 : '+artist_name+'</em><em>사이즈 : '+art_width_size+' X '+art_height_size+'cm</em></li>';
	str += '</ul>';
	str += '</div>';
	str += '<div class="action_rv en">';
	str += '<ul>';
	str += '<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>'+get_like+'</li>';
	str += '<li><a href=""><i class="far fa-comment-alt"></i></a> '+review_cnt+'</li>';
	str += '</ul>';
	str += '</div>';
	str += '<div class="price en">';
	str += '<ul>';
	str += '<li><em class="coinic">c</em> '+numberWithCommas(parseFloat(coin_price).toFixed(3))+'</li>';
	str += '<li><em class="kric">￦</em> '+numberWithCommas(cash_price)+'</li>';
	str += '</ul>';

	str += '</div>';
	elem.innerHTML  = str;
	
	return elem;
}

function sblist_element(id, ca_id, ca_name, img, profile_img, title, artist_name, art_width_size, art_height_size, get_like, review_cnt, cash_price, coin_price, batting_yn, batting_status, is_batting, is_guest){	
	
	var elem = document.createElement('div');
	
	elem.className = 'item';
	

	var str = '<a href="/products/' + id + '"><p class="is-loading"><img src="/storage/image/product/'+img+'" alt=""/></p></a>';
	str += '<div class="peo_txt">';
	str += '<p><img src="/storage/image/'+profile_img+'" alt="작가프로필사진"/></p>';
	str += '<ul>';
	str += '<li><span class="category">'+ca_name+'</span>'+title+'</li>';
	str += '<li><em>작가명 : '+artist_name+'</em><em>사이즈 : '+art_width_size+' X '+art_height_size+'cm</em></li>';
	str += '</ul>';
	str += '</div>';
	str += '<div class="action_rv en">';
	str += '<ul>';
	str += '<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>'+get_like+'</li>';
	str += '<li><a href=""><i class="far fa-comment-alt"></i></a> '+review_cnt+'</li>';
	str += '</ul>';
	str += '</div>';
	str += '<div class="price en">';
	str += '<ul>';
	str += '<li><em class="coinic">c</em> '+numberWithCommas(parseFloat(coin_price).toFixed(3))+'</li>';
	str += '<li><em class="kric">￦</em> '+numberWithCommas(cash_price)+'</li>';
	str += '</ul>';
	if(batting_yn){
		if(batting_status == 0){
			str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt not kr" onclick="alert(\'베팅을 신청하지 않은 작품입니다.\')">불가</button>';
		}else if(batting_status == 1){
			if(is_guest){
				str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt kr" onclick="alert(\'로그인을 하셔야 베팅이 가능합니다.\');location.href=\'/login\';">베팅</button>';
			}else{
				if(is_batting > 0){
					str += '<a href="#popupcux" id="batting_btn'+id+'" class="modaltrigger batting_btn betbt ok kr" onclick="batting_load('+id+')">완료</button>';
				}else{
					str += '<a href="#popupcux" id="batting_btn'+id+'" class="modaltrigger batting_btn betbt kr" onclick="batting_do('+id+')">베팅</button>';
				}
			}
		}else if(batting_status == 2){
			str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt end kr" onclick="alert(\'베팅이 종료된 작품입니다.\')">종료</button>';
		}
	}else{
		str += '<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt not kr" onclick="alert(\'베팅을 신청하지 않은 작품입니다.\')">불가</button>';
	}
	str += '</div>';
	
	elem.innerHTML  = str;
	
	return elem;
}

function blist_element(id, ca_id, ca_name, img, profile_img, title, artist_name, art_width_size, art_height_size, get_like, review_cnt, status, cash_price, coin_price, is_batting, is_login){	
	
	var elem = document.createElement('div');
	
	elem.className = 'item';
	

	var str = '<a href="/products/' + id + '"><p class="is-loading"><img src="/storage/image/product/'+img+'" alt=""/></p></a>';
	str += '<div class="peo_txt">';
	str += '<p><img src="/storage/image/'+profile_img+'" alt="작가프로필사진"/></p>';
	str += '<ul>';
	str += '<li>'+title+'</li>';
	str += '<li><em>작가명 : '+artist_name+'</em><em>사이즈 : '+art_width_size+' X '+art_height_size+'cm</em></li>';
	str += '</ul>';
	str += '</div>';
	str += '<div class="action_rv en">';
	str += '<ul>';
	str += '<li><a href=""><i class="far fa-heart"></i><i class="fas fa-heart"></i></a>'+get_like+'</li>';
	str += '<li><a href=""><i class="far fa-comment-alt"></i></a> '+review_cnt+'</li>';
	str += '</ul>';
	str += '</div>';
	str += '<div class="price en">';
	str += '<ul>';
	str += '<li><em class="coinic">c</em> '+numberWithCommas(parseFloat(coin_price).toFixed(3))+'</li>';
	str += '<li><em class="kric">￦</em> '+numberWithCommas(cash_price)+'</li>';
	str += '</ul>';
	console.log(status+"awdawd");

	if(status == 1){
		if(is_login){
			if(is_batting > 0){
				str += '<a href="#popupcux"  id="batting_btn'+id+'" class="modaltrigger batting_btn betbt ok kr" onclick="batting_load('+id+')">완료</a>';
			}else{
				str += '<a href="#popupcux" id="batting_btn'+id+'" class="modaltrigger batting_btn betbt kr" onclick="batting_do('+id+')">베팅</a>';
			}
		}else{
			str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt kr" onclick="alert(\'로그인을 하셔야 베팅이 가능합니다.\');location.href=\'/login\';">베팅</button>';
		}
	}else if(status == 2){
		str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt end kr" onclick="alert(\'베팅이 종료된 작품입니다.\')">종료</button>';
	}
	
	str += '</div>';
	
	elem.innerHTML  = str;
	
	return elem;
}