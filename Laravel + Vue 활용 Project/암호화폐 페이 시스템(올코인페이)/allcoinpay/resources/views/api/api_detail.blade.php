@extends('layouts.app')

@section('content')
@if(count($apis) < 3)
<div class="container ex_padding ex_container pg_wrapper pg_info">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_style">
				<div class="panel-body">
					<h3>API정보</h3>
					<span class="ment">API를 사용하기 위해, APIKEY를 발급받아 주세요.<br>만료기간은 총 3개월이니 3개월마다 갱신해주셔야합니다.<br>총 3개까지 발급받으실 수 있습니다.</span>
                    <form method="POST" action="{{ route('api_insert') }}">
                        @csrf
                        <div class="line_align_pg">
                            <input class="line_align_pg_inner" type = "text" placeholder="ex) https://pay.cointouse.com" name="site_url" required>
                        </div>
					    <input type = "submit" class="line_align_pg pg_btn"  value = "발급받기">
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
<div class="container ex_padding ex_container pg_wrapper pg_info">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_style">
				<div class="panel-body">
                    <h3>발급받은 API KEY</h3>
                    <span class="ment">발급받은 APIKEY 입니다. 총 3개까지 발급받으실 수 있습니다.</span>
                    <table  class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:12%;">사이트 URL</th>
                                <th style="width:28%;">PRIVATE_APIKEY</th>
                                <th style="width:28%;">PUBLIC_APIKEY</th>
                                <th style="width:10%;">만료기간</th>
                                <th style="width:7%;">보기</th>
                                <th style="width:7%;">재발급</th>
                                <th style="width:7%;">삭제</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apis as $api)
                            <tr>
                                <td>{{ $api->site_url }}</td>
                                <td>
                                    <input class="line_align_pg_inner" type="password" id="apikey{{ $api->id }}" value="{{ $api->apikey }}" style="padding:8px 5px;border:solid 1px;" readonly>
                                    <i class="far fa-copy" style="font-size:22px;cursor:pointer;"  data-clipboard-action="copy" data-clipboard-target="#apikey{{ $api->id }}"></i>
                                </td>
                                <td>
                                    <input class="line_align_pg_inner" type="password" id="public_apikey{{ $api->id }}" value="{{ $api->public_apikey }}" style="padding:8px 5px;border:solid 1px;" readonly>
                                    <i class="far fa-copy" style="font-size:22px;cursor:pointer;"  data-clipboard-action="copy" data-clipboard-target="#public_apikey{{ $api->id }}"></i>
                                </td>
                                <td>{{ $api->expiration_at }}</td>
                                <td>
                                    <i class="far fa-eye" style="font-size:22px;cursor:pointer;" onclick="show_text({{ $api->id }})"></i>
                                </td>
                                <td>
                                    <i class="fas fa-sync-alt" style="font-size:22px;cursor:pointer;" onclick="refresh_apikey({{ $api->id }})"></i>
                                </td>
                                <td>
                                    <i class="far fa-trash-alt" style="font-size:22px;cursor:pointer;" onclick="delete_apikey({{ $api->id }})"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">발급받은 API KEY 가 존재하지 않습니다.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
				</div>
			</div>
		</div>	
	</div>
</div>
<div class="container ex_padding ex_container pg_wrapper pg_info">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_style">
				<div class="panel-body">
					<h3>API문서</h3>
					<span class="ment">API를 사용하기 위한 참고 문서입니다.</span>
                    <div>
                        <h5>결제창</h5>
                        <textarea style="width:100%;height:300px;background: gray;color: white;" readonly>
--------------------------html 소스--------------------------
<form name="form_chk" method="post">
    <input type="hidden" name="symbol" value="COIN-SYMBOL"> //코인 기호 (BTC)
    <input type="hidden" name="cash_price" value="KRW-PRICE"> //판매할 가격 (원)
    <input type="hidden" name="apikey" value="YOUR-PUBLIC-API-KEY"> //PUBLIC APIKEY 입력
</form>
<button onclick="payPopup();">결제창 실행버튼</button>


--------------------------script 소스--------------------------
function payPopup(){
    window.open('', 'popupPay', 'width=1000, height=800, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
    document.form_chk.action = "https://devpay.cointouse.com/api/payment_window";
    document.form_chk.target = "popupPay";
    document.form_chk.submit();
}
                        </textarea>
                    </div>
                    <div>
                        <h5>거래이력</h5>
                        <textarea style="width:100%;height:300px;background: gray; color: white;" readonly>
curl -X POST https://pay.cointouse.com/api/payment_history
-d '{"apikey": "YOUR-PRIVATE-API-KEY"}, //PRIVATE APIKEY 입력


--------------------------Return data--------------------------
'company_name' : 판매 회사 이름
'cointype' : '코인 기호 (BTC)'
'address' : '판매자 주소'
'status' : 'complete','refund','calculate' //결제완료, 환불, 정산완료
'cash_price' : 결제하려는 KRW 양
'coin_amt' : 결제해야될 코인 양
'coin_price' : 당시 코인 시세 (KRW)
'buyer_address' : 구매자 주소
'buyer_fullname' : 구매자 이름
'seller_fullname' : 판매자 이름
'created_dt' : 생성시간
'updated_dt' : 결제시간
                        </textarea>
                    </div>
                    <div>
                        <h5>환불</h5>
                        <textarea style="width:100%;height:300px;background: gray;color: white;" readonly>
curl -X POST https://pay.cointouse.com/api/payment_refund
-d '{"apikey": "YOUR-PRIVATE-API-KEY", "id" : "ID-to-refund"}, //PRIVATE APIKEY 입력


--------------------------Return data--------------------------
'status' : 'OK','error'

                        </textarea>
                    </div>
				</div>
                
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
	<script>
        $(function(){
            $('.fa-copy').on('click', function(e){
                var copy_id = $(this).data('clipboard-target');
                if($(copy_id).attr('type') == 'password'){
                    alert('비밀번호 눈모양 아이콘을 클릭해 텍스트가 나온 후 사용해 주세요.');
                }else{
                    select_all_and_copy(copy_id.substring(1));
                }
            });
        });

		function show_text(num){
            var type = $('#apikey'+num).attr('type');
            if(type == 'password'){
                $('#apikey'+num).attr('type','text');
                $('#public_apikey'+num).attr('type','text');
            }else{
                $('#apikey'+num).attr('type','password');
                $('#public_apikey'+num).attr('type','password');
            }
        }



        function refresh_apikey(id){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url : "/refresh_apikey",
                type : "POST",
                data : {_token:CSRF_TOKEN, id : id},
                dataType : "JSON"
            }).done(function(data) {
                console.log(data);
                if(data.status == 'OK'){
                    alert('APIKEY 갱신에 성공하셨습니다. 사이트의 APIKEY 값을 변경해주세요.');
                    location.reload();
                }else{
                    alert('APIKEY 갱신에 실패하셨습니다. 잠시 후 다시 시도해주세요.');
                }

            }).error(function(){
                console.log("error");
            });
        }

        function delete_apikey(id){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url : "/delete_apikey",
                type : "POST",
                data : {_token:CSRF_TOKEN, id : id},
                dataType : "JSON"
            }).done(function(data) {
                console.log(data);
                if(data.status == 'OK'){
                    alert('APIKEY 폐기에 성공하셨습니다. 이제 해당 APIKEY 는 사용할 수 없습니다.');
                    location.reload();
                }else{
                    alert('APIKEY 폐기에 실패하셨습니다. 잠시 후 다시 시도해주세요.');
                }

            }).error(function(){
                console.log("error");
            });
        }

        function select_all_and_copy(el) {
            // ++ added line for table
            var el = document.getElementById(el);
            // Copy textarea, pre, div, etc.
            if (document.body.createTextRange) {// IE 
                var textRange = document.body.createTextRange();
                textRange.moveToElementText(el);
                textRange.select();
                textRange.execCommand("Copy");   
                alert('Copied to clipboard');
            } else if (window.getSelection && document.createRange) {// non-IE
                var editable = el.contentEditable; // Record contentEditable status of element
                var readOnly = el.readOnly; // Record readOnly status of element
                el.contentEditable = true; // iOS will only select text on non-form elements if contentEditable = true;
                el.readOnly = false; // iOS will not select in a read only form element
                var range = document.createRange();
                range.selectNodeContents(el);
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range); // Does not work for Firefox if a textarea or input
                if (el.nodeName == "TEXTAREA" || el.nodeName == "INPUT") 
                    el.select(); // Firefox will only select a form element with select()
                if (el.setSelectionRange && navigator.userAgent.match(/ipad|ipod|iphone/i))
                    el.setSelectionRange(0, 999999); // iOS only selects "form" elements with SelectionRange
                el.contentEditable = editable; // Restore previous contentEditable status
                el.readOnly = readOnly; // Restore previous readOnly status 
                console.log(el);
                if (document.queryCommandSupported("copy"))
                {
                    var successful = document.execCommand('copy');  
                    if (successful) alert('Copied to clipboard');
                    else alert('Press Ctrl+C to copy');
                }
                else
                {
                    if (!navigator.userAgent.match(/ipad|ipod|iphone|android|silk/i))
                        alert('Press Ctrl+C to copy');
                }
            }
        }
	</script>
@endsection