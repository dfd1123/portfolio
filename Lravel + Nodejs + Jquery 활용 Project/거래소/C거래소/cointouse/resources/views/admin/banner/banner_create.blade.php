@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
		{{ __('banner.b_set')}}
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	{{ __('banner.b_add')}}
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.banner_store')}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('banner.b_link')}}</th>
							<td>
								<input type="text" name="target_url"  class="form-control tsa-input-st" value=""/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_pc')}}</th>
							<td>
								<div class="filebox">
									<label for="file1" class="myButton use">{{ __('banner.up')}}</label> 
									<input type="file" id="file1" name="file1" accept="image/*"/>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_mo')}}</th>
							<td>
								<div class="filebox">
									<label for="file2" class="myButton use">{{ __('banner.b_up')}}</label> 
									<input type="file" id="file2" name="file2" accept="image/*"/>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_exp')}}</th>
							<td>
								<input type="text" name="detail"  class="form-control tsa-input-st" value=""/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_pos')}}</th>
							<td>
								<select class="tsa-select" name="position">
									<option value="top">{{ __('banner.b_top')}}</option>
									<option value="left">{{ __('banner.b_left')}}</option>
									<option value="right">{{ __('banner.b_right')}}</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_lan')}}</th>
							<td>
								<select class="tsa-select" name="lang">
									<option value="en">EN</option>
									<option value="kr">KR</option>
									<option value="jp">JP</option>
									<option value="ch">CH</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_ifuse')}}</th>
							<td>
								<select class="tsa-select" name="active">
									<option value="1">{{ __('banner.b_use')}}</option>
									<option value="0">{{ __('banner.b_nouse')}}</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('banner.add')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('banner.cancel')}}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('script')

@endsection
