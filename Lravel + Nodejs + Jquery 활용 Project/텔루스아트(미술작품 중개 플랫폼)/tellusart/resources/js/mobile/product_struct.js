function glist_element(id, ca_id, ca_name, img, profile_img, title, artist_name, art_width_size, art_height_size, get_like, review_cnt, cash_price, coin_price){	
	
	var elem = document.createElement('div');
	
	elem.className = 'grid-item';
	

	var str = '<div><a href="/products/' + id + '"><p class="is-loading"><img src="/storage/image/product/'+img+'" alt=""/></p></a>';
    str += '<span>'+ca_name+'<strong>'+title+'</strong></span></a>';
	str += '<div class="price en">';
	str += '<ul>';
	str += '<li><em class="coinic">c</em> '+numberWithCommas(parseFloat(coin_price).toFixed(3))+'</li>';
	str += '<li><em class="kric">￦</em> '+numberWithCommas(cash_price)+'</li>';
	str += '</ul>';
    str += '</div>';
    str += '</div>';
	
	elem.innerHTML  = str;
	
	return elem;
}


function sblist_element(id, ca_id, ca_name, img, profile_img, title, artist_name, art_width_size, art_height_size, get_like, review_cnt, cash_price, coin_price, batting_yn, batting_status, is_batting, is_guest){	
	
	var elem = document.createElement('div');
	
	elem.className = 'grid-item';
	

	var str = '<div><a href="/products/' + id + '"><p class="is-loading"><img src="/storage/image/product/'+img+'" alt=""/></p><span>'+ca_name+'<strong>'+title+'</strong></span></a>';
	str += '<div class="price en"><ul>';
	str += '<li><em class="coinic">c</em> '+numberWithCommas(parseFloat(coin_price).toFixed(3))+'</li><li><em class="kric">￦</em> '+numberWithCommas(cash_price)+'</li>';
	str += '</ul>';
	if(batting_yn){
		if(batting_status == 0){
			str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt not kr" onclick="alert(\'베팅을 신청하지 않은 작품입니다.\')">불가</button>';
		}else if(batting_status == 1){
			if(is_guest){
				str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt kr" onclick="alert(\'로그인을 하셔야 베팅이 가능합니다.\');location.href=\'/login\';">베팅</button>';
			}else{
				if(is_batting > 0){
					str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt ok kr" onclick="batting_load('+id+')">완료</button>';
				}else{
					str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt kr" onclick="batting_do('+id+')">베팅</button>';
				}
			}
		}else if(batting_status == 2){
			str += '<button type="button" id="batting_btn'+id+'" class="batting_btn betbt end kr" onclick="alert(\'베팅이 종료된 작품입니다.\')">종료</button>';
		}
	}else{
		str += '<button type="button" id="batting_btn{{$product->id}}" class="batting_btn betbt not kr" onclick="alert(\'베팅을 신청하지 않은 작품입니다.\')">불가</button>';
	}
	str += '</div></div>';
	
	elem.innerHTML  = str;
	
	return elem;
}