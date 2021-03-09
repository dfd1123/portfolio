@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('coinlock.lockset')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('coinlock.lockedit')}}
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.coin_lock_update', $coin->id)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('coinlock.lockkind')}}</th>
							<td>{{strtoupper($coin->coin)}}</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('coinlock.fee')}}</th>
							<td>
								<input type="number" step="0.0001" class="form-control" name="ratio" value="{{$coin->ratio}}" required/>
								<span>{{ __('coinlock.weekcoin')}}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button id="btn_add" type="submit" class="btn btn-default mint_btn">
				{{ __('coinlock.chan')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('coinlock.gg')}}
				</button>
			</div>
		</form>
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('coinlock.update')}}
	</div>
</div>

@endsection

@section('script')
<script>
	$(function() {
		$('#btn_add').click(function() {
			return validate();
		});
	});

	function validate() {
		var ratio_input = $('input[name=ratio]').val();
		
		if(ratio_input == '') {
			alert("{{ __('coinlock.insert')}}");
			return false;
		}
		
		if(ratio_input.length > 6) {
			alert("{{ __('coinlock.lot')}}");
			return false;
		}
		
		var ratio = parseFloat(ratio_input);
		if(ratio == NaN) {
			alert("{{ __('coinlock.wrong')}}");
			return false;
		}
		
		if(ratio <= 0) {
			alert("{{ __('coinlock.nofee1')}}");
			return false;
		}
		
		if(ratio > 1) {
			alert("{{ __('coinlock.nofee2')}}");
			return false;
		}
		
    	return true;
	}
</script>
@endsection
