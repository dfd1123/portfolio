@extends('pc.layouts.app')

@section('content')

<div class="sub_spot create" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
	<h2>작품등록</h2>
	<div class="search_form">
		<form id="search_item" method="get" action="{{route('products.search_list',-1)}}">
			<input type="text" name="keyword" placeholder="작가명, 회화명, 분야 등으로 검색"/>
			<span><button type="submit">search<i class="fas fa-search"></i></button></span>
		</form>
	</div>
</div>
<div id="container">
	<div class="createbox">
		<form enctype="multipart/form-data" method="POST" action="{{ route('products.store') }}">
			
			@csrf
			
			<dl>
				<dt>작품사진</dt>
				<dd>
					<div id="div0" idx="0">
						@for($i=1; $i<=5; $i++)
							<div id="art_img_li{{$i}}" {{ ($i != 1)? 'style=display:none':'' }}>
								<div id="art_img_inp{{$i}}" class="filebox">
									<label for="ex_file{{$i}}">사진 등록하기 <i class="fal fa-image"></i></label>
									<input type="file" id="ex_file{{$i}}" name="images[]" value="{{ old('image'.$i) }}" class="input_imgs" {{ ($i == 1)? 'required="required"':'' }} >
								</div>
								<input type="hidden" name="img_rotate{{$i}}"/>
								<button type="button" class="rotate_img_input" data-index="{{$i}}"><i class="fal fa-undo"></i></button>
								<button type="button" class="add_file_input" data-index="{{$i}}"><i class="fal fa-plus"></i></button>
								@if($i != 1)
									<button type="button" class="remove_file_input" data-index="{{$i}}"><i class="fal fa-minus"></i></button>
								@endif
								<div id="preview_art_img{{$i}}" class="imgs_wrap"></div>	
							</div>
						@endfor
					</div>
				</dd>
			</dl>
			<dl>
				<dt>작품명</dt>
				<dd><input type="text" id="title" name="title" value="{{ old('title') }}" required="required" /></dd>
			</dl>
			<dl>
				<dt class="w100">작품소개</dt>
				<dd class="w100">
					<textarea name="introduce" required="required"></textarea>
				</dd>
			</dl>
			<dl>
				<dt>작가이름</dt>
				<dd><input type="text" name="artist_name" value="{{ old('artist_name') }}" required="required" /></dd>
			</dl>
			<dl>
				<dt>작가사진</dt>
				<dd>
					<img id="preview_artist_img" class="artist_img" src="{{asset('storage/image/default_profile.png')}}" />
					<input type="hidden" name="img_rotate_artist"/>
					<label for="artist_img" class="artist_img_label">등록하기</label>
					<input type="file" name="artist_img" id="artist_img" value="{{ old('artist_img') }}" class="hidden" />
					<button type="button" class="artist_img rotate_img_input" style="height:38px; float: initial; margin-top: initial;"><i class="fal fa-undo"></i></button>
				</dd>
			</dl>
			<dl>
				<dt class="w100">작가소개</dt>
				<dd class="w100"><textarea name="artist_intro" required="required">{{ old('artist_intro') }}</textarea></dd>
			</dl>
			<dl>
				<dt class="w100">작가약력</dt>
				<dd class="w100"><textarea name="artist_career">{{ old('artist_career') }}</textarea></dd>
			</dl>
			<dl class="ty02">
				<dt>작품가로사이즈</dt>
				<dd>
					<input type="number" name="art_width_size" value="{{ old('art_width_size') }}" required="required" class="din" />
					<div class="select w20 din">
						<select name="date_m" required="required">
							<option value="cm" selected="selected">cm</option>
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
				<dt class="left10">작품세로사이즈</dt>
				<dd><input type="number" name="art_height_size" value="{{ old('art_height_size') }}" required="required" class="din"/>
					<div class="select w20 din">
						<select name="date_m" required="required">
							<option value="cm" selected="selected">cm</option>
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
			</dl>
			<dl>
				<dt>제작년월</dt>
				<dd>
					<div class="select w50">
						<select name="date_y" required="required">
							<option value="2019">2019</option>
							<option value="2018">2018</option>
							<option value="2017">2017</option>
							<option value="2016">2016</option>
							<option value="2015">2015</option>
							<option value="2014">2014</option>
							<option value="2013">2013</option>
							<option value="2012">2012</option>
							<option value="2011">2011</option>
							<option value="2010">2010</option>
							<option value="2009">2009</option>
							<option value="2008">2008</option>
							<option value="2007">2007</option>
							<option value="2006">2006</option>
							<option value="2005">2005</option>
							<option value="2004">2004</option>
						</select>
						<div class="select__arrow"></div>
					</div>
					<div class="select w20">
						<select name="date_m" required="required">
							<option value="12">12</option>
							<option value="11">11</option>
							<option value="10">10</option>
							<option value="09">09</option>
							<option value="08">08</option>
							<option value="07">07</option>
							<option value="06">06</option>
							<option value="05">05</option>
							<option value="04">04</option>
							<option value="03">03</option>
							<option value="02">02</option>
							<option value="01">01</option>
						</select>
						<div class="select__arrow"></div>
					</div>
					<div class="select w20">
						<select name="date_d" required="required">
							@for($i=1;$i<=31;$i++)
								<option value="{{sprintf("%02d", $i)}}">{{sprintf("%02d", $i)}}</option>
							@endfor
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
			</dl>
			<dl>
				<dt>카테고리</dt>
				<dd>
					<div class="select w50" >
						<select name="category" required="required">
							@foreach($categorys as $category)
								<option value="{{$category->id}}">{{$category->ca_name}}</option>
							@endforeach
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
			</dl>
			<dl>
				<dt>현금 판매가</dt>
				<dd><input type="text" name="cash_price" value="{{ old('cash_price') }}" placeholder="원화로 기재하여 주세요." class="num_format" required="required" /></dd>
			</dl>
			<dl>
				<dt>코인 판매가</dt>
				<dd><input type="text" name="coin_price" value="{{ old('coin_price') }}" placeholder="TLG로 기재하여 주세요." class="num_format" required="required" /></dd>
			</dl>
			<dl>
				<dt>배송비</dt>
				<dd><input type="text" name="delivery_price" value="{{ old('delivery_price') }}" placeholder="원화로 기재하여 주세요. (코인결제에는 적용되지 않습니다)" class="num_format" required="required" /></dd>
			</dl>
			<dl>
				<dt>베팅진행</dt>
				<dd>
					<input type="radio" id="batting_y" class="ing" name="batting_yn" value="1"  checked/><label for="batting_y"><span></span>진행</label>
					<input type="radio" id="batting_n" class="ing" name="batting_yn" value="0" /><label for="batting_n"><span></span>진행하지않음</label>
				</dd>
			</dl>
			<button type="submit" class="regist_bt">작품등록하기</button>
		</form>
	</div>
</div>
<script type="text/javascript">
	var sel_files = [];
	
	$('.add_file_input').click(function(e){
		var index = $(this).data("index");
		if(index == 5){
			alert('최대 사진 업로드 갯수는 5개입니다.');
		}else{
			$('#art_img_li'+(index+1)).show();	
		}
	});

	$('.rotate_img_input').click(function(e){
		var index = $(this).data("index");
		var preview = $('#preview_art_img' + index);

		if(!preview.data('rotate')) {
			preview.data('rotate', 0);
		};
		
		var curRot = preview.data('rotate');
		var nextRot = curRot - 90;

		if(nextRot <= -360) {
			nextRot = 0;
		}
		preview.find('img').css('transform', 'rotate(' + String(nextRot) + 'deg)');
		preview.data('rotate', nextRot);
		$('[name=img_rotate' + index +']').val(String(nextRot));
	});
	
	$('.remove_file_input').click(function(e){
		var index = $(this).data("index");
		if(index == 1){
			alert('작품사진 1개는 필수 등록사항입니다.');	
		}else{
			$('#art_img_li'+(index)).hide();
			
			$("#preview_art_img"+index).html("");
	               	
           	$("#ex_file"+index).val(null);
		}
	});

	$(".input_imgs").change(function(e){
		var files = e.target.files;
		var filesArr = Array.prototype.slice.call(files);
		
		var img_num = $(this).attr('id');
		img_num = img_num.charAt(img_num.length-1);
		
	
		filesArr.forEach(function(f) {
			if(!f.type.match("image.*")) {
				alert("확장자는 이미지 확장자만 가능합니다.");
				return;
			}
	
			sel_files.push(f);
	
			var reader = new FileReader();
			reader.onload = function(e) {
				var img_html = "<p><img src=\"" + e.target.result + "\" /></p>";
				$("#preview_art_img"+img_num).html(img_html).data('rotate', 0);
				$('[name=img_rotate' + img_num +']').val(String(0));
				$("#art_img_inp"+img_num+" label").html('사진 수정하기 <i class="fal fa-image"></i>');
			}
			reader.readAsDataURL(f);
		});
	});

	$('.artist_img.rotate_img_input').click(function(e){
		var index = $(this).data("index");
		var preview = $("#preview_artist_img");

		if(preview.attr('src') === '{{asset('storage/image/default_profile.png')}}'){
			return;
		}

		if(!preview.data('rotate')) {
			preview.data('rotate', 0);
		};
		
		var curRot = preview.data('rotate');
		var nextRot = curRot - 90;

		if(nextRot <= -360) {
			nextRot = 0;
		}
		preview.css('transform', 'rotate(' + String(nextRot) + 'deg)');
		preview.data('rotate', nextRot);
		$('[name=img_rotate_artist]').val(String(nextRot));
	});

	$('#artist_img').change(function(e){
		$("#preview_artist_img").data('rotate', 0);
		$('[name=img_rotate_artist]').val(String(0));
	});

	function handleImgsFilesSelect(e) {
	var files = e.target.files;
	var filesArr = Array.prototype.slice.call(files);

	filesArr.forEach(function(f) {
		if(!f.type.match("image.*")) {
			alert("확장자는 이미지 확장자만 가능합니다.");
			return;
		}

		sel_files.push(f);

		var reader = new FileReader();
		reader.onload = function(e) {
			var img_html = "<p><img src=\"" + e.target.result + "\" /></p>";
			$(".imgs_wrap").html(img_html);
			$(".filebox label").html('사진 수정하기 <i class="fal fa-image"></i>');
		}
		reader.readAsDataURL(f);
	});
	}


</script>
@endsection