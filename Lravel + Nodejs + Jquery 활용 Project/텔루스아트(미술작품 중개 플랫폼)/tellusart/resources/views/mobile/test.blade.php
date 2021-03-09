

<form method="POST" action="{{ route('testtlc.create') }}" id="order_target">
	@csrf
	<button>tetestst</button>
</form>
{{$user_info->address->user_email}}<br>


<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{$user_info->address->address_tlc}}&choe=UTF-8"><br>
{{$user_info->address->address_tlc}}<br>
{{$listtransaction[0]['account']}}
<form>
	@csrf
	<input type="text" name="received_address">
	<input type="text" name="amount">
	
</form>

