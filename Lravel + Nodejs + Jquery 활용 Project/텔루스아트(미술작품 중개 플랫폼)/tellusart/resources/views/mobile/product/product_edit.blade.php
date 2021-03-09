@extends('mobile.layouts.app')

@section('content')
<div class="sub-container">
	
	<div class="createbox">
		<h3 class="tit">작품등록</h3>
		<form enctype="multipart/form-data" method="POST" action="{{ route('product.updates',$product->id) }}">
			@csrf		
			<dl>
				<dt>작품사진</dt>
				<dd>
					<div id="div0" idx="0">
						@for($i=1; $i<=5; $i++)
							<div id="art_img_li{{$i}}" {{ ($i != 1)? 'style=display:none':'' }}>
								<div id="art_img_inp{{$i}}" class="filebox">
									<label for="ex_file{{$i}}">사진 수정하기</label>
									@if($product['image'.$i] != NULL)
										<input type="file" id="ex_file{{$i}}" name="images[]" value="{{ $product['image'.$i] }}" class="input_imgs" accept="image/*"  >
									@else
										<input type="file" id="ex_file{{$i}}" name="images[]" class="input_imgs" >
									@endif
								</div>
								<input type="hidden" name="img_rotate{{$i}}"/>
								<button type="button" class="rotate_img_input" data-index="{{$i}}" style="display: none"><i class="fal fa-undo"></i></button>
								<button type="button" class="add_file_input" data-index="{{$i}}"><i class="fal fa-plus"></i></button>
								@if($i != 1)
									<button type="button" class="remove_file_input" data-index="{{$i}}"><i class="fal fa-minus"></i></button>
								@endif
								<div id="preview_art_img{{$i}}" class="preview_art_img_mobile imgs_wrap">
									@if($product['image'.$i] != NULL)
										<p>
											<img src="{{ asset('/storage/image/product/'.$product['image'.$i]) }}"/>
										</p>
									@endif
								</div>
							</div>
						@endfor
					</div>
				</dd>
			</dl>
			<dl>
				<dt>작품명</dt>
				<dd><input type="text" id="title" name="title" value="{{ $product->title }}" required="required"></dd>
			</dl>
			<dl>
				<dt class="w100">작품소개</dt>
				<dd class="w100">
					<textarea name="introduce" required="required">{{ $product->introduce }}</textarea>
				</dd>
			</dl>
			<dl>
				<dt>작가이름</dt>
				<dd><input type="text" name="artist_name" value="{{ $product->artist_name }}" required="required"></dd>
			</dl>
			<dl>
				<dt>작가사진</dt>
				<dd>
					<img id="preview_artist_img" class="artist_img" src="{{asset('storage/image/'.$product->artist_img)}}" data-default="true"/>
					<input type="hidden" name="img_rotate_artist"/>	
					<label for="artist_img" class="artist_img_label">수정하기</label>
					<input type="file" name="artist_img" id="artist_img" value="{{ $product->artist_img }}" class="hidden" accept="image/*" />
					<button type="button" class="artist_img rotate_img_input" style="display: none; float: initial; margin-top: initial; margin-bottom: initial;"><i class="fal fa-undo"></i></button>
				</dd>
			</dl>
			<dl>
				<dt class="w100">작가소개</dt>
				<dd class="w100"><textarea name="artist_intro" required="required">{{ $product->artist_intro }}</textarea></dd>
			</dl>
			<dl>
				<dt class="w100">작가약력</dt>
				<dd class="w100"><textarea name="artist_career">{{ $product->artist_career }}</textarea></dd>
			</dl>
			<dl class="ty02">
				<dt>작품가로사이즈</dt>
				<dd>
					<input type="number" name="art_width_size" value="{{ $product->art_width_size }}" required="required" class="din">
					<div class="select w20 din">
						<select name="date_m" required="required">
							<option value="cm" selected="selected">cm</option>
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
				<dt>작품세로사이즈</dt>
				<dd><input type="number" name="art_height_size" value="{{ $product->art_height_size }}" required="required" class="din">
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
							@for($i=2004;$i<=2019;$i++)
								<option value="{{$i}}">{{$i}}</option>
							@endfor
						</select>
						<div class="select__arrow"></div>
					</div>
					<div class="select w20">
						<select name="date_m" required="required">
							@for($i=1;$i<=12;$i++)
								<option value="{{$i}}" {{ ($date_m == $i)?'selected="selected"':'' }}>{{$i}}</option>
							@endfor
						</select>
						<div class="select__arrow"></div>
					</div>
					<div class="select w20">
						<select name="date_d" required="required">
							@for($i=1;$i<=31;$i++)
								<option value="{{sprintf("%02d", $i)}}"  {{ ($date_d == $i)?'selected="selected"':'' }}>{{sprintf("%02d", $i)}}</option>
							@endfor
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
			</dl>
			<dl>
				<dt>카테고리</dt>
				<dd>
					<div class="select">
						<select name="category" required="required">
							@foreach($categorys as $category)
								<option value="{{$category->id}}" {{ ($product->ca_id == $category->id)?'selected="selected"':'' }}>{{$category->ca_name}}</option>
							@endforeach
						</select>
						<div class="select__arrow"></div>
					</div>
				</dd>
			</dl>
			<dl>
				<dt>현금 판매가</dt>
				<dd><input type="text" name="cash_price" value="{{ $product->cash_price }}" placeholder="원화로 기재하여 주세요." class="num_format" required="required" /></dd>
			</dl>
			<dl>
				<dt>코인 판매가</dt>
				<dd><input type="text" name="coin_price" value="{{ $product->coin_price }}" placeholder="TLC로 기재하여 주세요. (배송비 포함)" class="num_format" required="required" /></dd>
			</dl>
			<dl>
				<dt>배송비</dt>
				<dd><input type="text" name="delivery_price" value="{{ $product->delivery_price }}" placeholder="원화로 기재하여 주세요." class="num_format" required="required" /></dd>
			</dl>
			@if($product->batting_yn == 1)
				@if($product->batting_status == 0)
					<dl>
						<dt>베팅진행</dt>
						<dd>
							<input type="radio" id="batting_yn1" class="ing" name="batting_yn" value="1"  {{ ($product->batting_yn == 1)?'checked="checked"':'' }}/><label for="batting_yn1"><span></span>진행</label>
							<input type="radio" id="batting_yn0" class="ing" name="batting_yn" value="0"  {{ ($product->batting_yn == 0)?'checked="checked"':'' }} /><label for="batting_yn0"><span></span>진행하지않음</label>
						</dd>
					</dl>
				@elseif($product->batting_status == 2)
					<dl>
						<dt>베팅진행</dt>
						<dd>
							<input type="radio" id="batting_yn2" class="ing" name="batting_yn" value="2"  /><label for="batting_yn2"><span></span>재진행</label>
							<input type="radio" id="batting_yn0" class="ing" name="batting_yn" value="0" checked="checked" /><label for="batting_yn0"><span></span>진행하지않음</label>
						</dd>
					</dl>
				@endif
			@else
				<dl>
					<dt>베팅진행</dt>
					<dd>
						<input type="radio" class="ing" id="batting_yn1" name="batting_yn" value="1"  {{ ($product->batting_yn == 1)?'checked="checked"':'' }}/><label for="batting_yn1"><span></span>진행</label>
						<input type="radio" class="ing" id="batting_yn0" name="batting_yn" value="0"  {{ ($product->batting_yn == 0)?'checked="checked"':'' }} /><label for="batting_yn0"><span></span>진행하지않음</label>
					</dd>
				</dl>
			@endif
			<button type="submit" class="regist_bt kr">작품수정하기</button>
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
				$('.rotate_img_input[data-index=' + img_num +']').show();
				$("#art_img_inp"+img_num+" label").html('사진 수정하기');
			}
			reader.readAsDataURL(f);
		});
	});

	$('.artist_img.rotate_img_input').click(function(e){
		var index = $(this).data("index");
		var preview = $("#preview_artist_img");

		if(preview.data('default') === true){
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
		$("#preview_artist_img").data('default', false).data('rotate', 0).css('transform', 'rotate(' + String(0) + 'deg)');
		$('[name=img_rotate_artist]').val(String(0));
		$('.artist_img.rotate_img_input').show();
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
				var img_html = "<p><img src=\"" + e.target.result + "\"/></p>";
				$(".imgs_wrap").html(img_html);
				$(".filebox label").html('사진 수정하기');
			}
			reader.readAsDataURL(f);
		});
	}

	function readURLartist(input) {
		
		if (input.files && input.files[0]) {
			var reader = new FileReader();
		
			reader.onload = function (e) {
				$('img.artist_img').attr('src', e.target.result);
			}
		
			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#artist_img").change(function(){
		readURLartist(this);
	});
</script>
@endsection