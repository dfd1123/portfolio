<div class="content myinfobox" style="max-height: none;">
	<h3 class="tit">내 정보 수정</h3>
	<div class="modifybox myinfor">
		<ul>
			<li><input type="text" id="mb_id" name="mb_id" tabindex="2" title="아이디" class="required kr dis" placeholder="홍길동" value="" disabled/></li>
			<li>
				<div class="inp_with_btn">
					<input type="text" name="mb_nickname" id="nickname" tabindex="2" title="닉네임" class="required kr"  placeholder="닉네임" value="" onblur="chkchar(this)" required="required"/><button type="button" id="nickname_certify_btn" class="certify_btn">중복검사</button>
					<input type="hidden" id="nickname_certify" name="nickname_certify" value="0">
				</div>									
			</li>
			<li><input type="text" id="mb_hp" name="mb_hp" tabindex="2" title="비밀번호" class="required kr" placeholder="핸드폰번호" value="" required="required" /></li>
			<li><input type="text" id="post_num" name="post_num" tabindex="2" title="우편번호" class="required kr" placeholder="우편번호" value="" style="width:60%;" onclick="Postcode();" readonly="readonly"  required="required"/><a href="javascript:void(0);" onclick="Postcode();return false;" class="add_search">주소검색</a></li>
			<li><input type="text" id="mb_addr1" name="mb_addr1" tabindex="2" title="주소" class="required kr" placeholder="주소" value=""  required="required" /></li>
			<li><input type="text" id="mb_addr2" name="mb_addr2" tabindex="2" title="상세주소" class="required kr" placeholder="상세주소"  value="" required="required"/></li>
			<li style="display:none;"><input type="text" id="extra_addr" name="extra_addr" tabindex="2" title="참고주소" class="required kr" placeholder="참고주소"  value="" /><span id="guide" style="color:#999;display:none"></span></li>
		</ul>
		<button type="submit" class="joinbt" onclick="mobile_mypage_my_info_update();">회원수정하기</button>
	</div>
	<div class="secession_btn">
			<button type="button" id="user_delete">회원 탈퇴</button>
	</div>
</div>