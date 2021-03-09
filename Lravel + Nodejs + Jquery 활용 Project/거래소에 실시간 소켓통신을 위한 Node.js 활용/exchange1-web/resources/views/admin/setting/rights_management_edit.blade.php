@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	{{ __('setting.admin') }}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('setting.add_ad') }}
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{ route('admin.rights_management_update', $admin->id) }}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('setting.email') }}</th>
							<td>
								<input type="text" name="email"  class="form-control tsa-input-st" value="{{ $admin->email }}"  required/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('setting.name') }}</th>
							<td>
								<input type="text" name="fullname"  class="form-control tsa-input-st" value="{{ $admin->fullname }}"  required/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('setting.home_num') }}</th>
							<td>
								<input type="text" name="mobile_number"  class="form-control tsa-input-st" value="{{ $admin->mobile_number }}"  required/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('setting.lv') }}</th>
							<td>
								<select class="tsa-select" name="level">
									<option value="5" {{ $admin->level == 5 ? 'selected="selected"' : '' }}>5</option>
									<option value="4" {{ $admin->level == 4 ? 'selected="selected"' : '' }}>4</option>
									<option value="3" {{ $admin->level == 3 ? 'selected="selected"' : '' }}>3</option>
									<option value="2" {{ $admin->level == 2 ? 'selected="selected"' : '' }}>2</option>
									<option value="1" {{ $admin->level == 1 ? 'selected="selected"' : '' }}>1</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('setting.change') }}
				</button>
				<button type="button" class="btn btn-default mint_btn mint_btn_cancel" onclick="location.href='{{url()->previous()}}'">
				{{ __('setting.cancel') }}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')

@endsection
