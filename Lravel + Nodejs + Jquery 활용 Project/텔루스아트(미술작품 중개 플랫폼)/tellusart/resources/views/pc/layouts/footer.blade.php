	<div id="footer">
		<div class="footinfo">
			<ul>
				<!--<li><a href="">about</a></li>
				<li><a href="">business</a></li>-->
				<li><a href="http://tellusgold.com" target="_blank">회사소개</a></li>
				<li><a href="{{route('policy.policy', 2)}}" target="_blank">이용약관</a></li>
				<li><a href="{{route('policy.policy', 1)}}" target="_blank">개인정보취급방침</a></li>
			</ul>
		</div>
		<div class="footcenter">
			<ul>
				<li>customer center</li>
				<li>{{ str_replace("-",".",$company->phone_num)}}</li>
				<li>MON~FRI 09:00~18:00 / SAT, SUN, HOLIDAY OFF</li>
			</ul>
			<span>
				회사명: {{$company->company_name}}   /   대표자: {{$company->ceo_name}}   /   사업자 등록번호: {{$company->business_number}}   /   통신판매업 신고: {{$company->tellsell_number}} <a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=3498601174" target="_blank">[사업자정보확인] </a><br/>
				전화: {{$company->phone_num}}   /   팩스: {{$company->fax_num}}   /   주소: {{$company->address}}<br/>
				개인정보관리책임자: {{$company->infor_admin}}({{$company->company_email}})   /   Contact: {{$company->company_email}} for more information<br/>
				<em class="en">Copyright © {{$company->service_name}} All rights reserved</em>
			</span>
			
		</div>
	</div>