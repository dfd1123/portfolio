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
	{{ __('banner.b_edit')}}
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.banner_update', $banner->id)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">{{ __('banner.b_link')}}</th>
							<td>
								<input type="text" name="target_url"  class="form-control tsa-input-st" value="{{$banner->target_url}}"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_pc')}}</th>
							<td>
								<div class="filebox">
									<label for="file1" class="myButton use">{{ __('banner.b_up')}}</label> 
									<input type="file" id="file1" name="file1" accept="image/*"/>
									<span class="filename">
									@if(isset($banner))
										@if($banner->banner_url == NULL)
											({{ __('banner.noimage')}}
										@else
											<a href="{{asset('/storage/image/banner/'.$banner->banner_url)}}" target="_blank" class="myButton xbtn">이미지보기</a>
										@endif
									@endif
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_mo')}}</th>
							<td>
								<div class="filebox">
									<label for="file2" class="myButton use">{{ __('banner.b_up')}}</label>
									<input type="file" id="file2" name="file2" accept="image/*"/>
									<span class="filename">
									@if(isset($banner))
										@if($banner->banner_url == NULL)
										{{ __('banner.noimage')}}
										@else
											<a href="{{asset('/storage/image/banner/'.$banner->banner_mobile_url)}}" target="_blank" class="myButton xbtn">{{ __('banner.see')}}</a>
										@endif
									@endif
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_exp')}}</th>
							<td>
								<input type="text" name="detail"  class="form-control tsa-input-st" value="{{$banner->detail}}"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_pos')}}</th>
							<td>
								<select class="tsa-select" name="position">
									<option value="top" {{$banner->position == 'top' ? 'selected' : ''}}>{{ __('banner.b_top')}}</option>
									<option value="left" {{$banner->position == 'left' ? 'selected' : ''}}>{{ __('banner.b_left')}}</option>
									<option value="right" {{$banner->position == 'right' ? 'selected' : ''}}>{{ __('banner.b_right')}}</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_lan')}}</th>
							<td>
								<select class="tsa-select" name="lang">
									<option value="en" {{$banner->lang == 'en' ? 'selected' : ''}}>EN</option>
									<option value="kr" {{$banner->lang == 'kr' ? 'selected' : ''}}>KR</option>
									<option value="jp" {{$banner->lang == 'jp' ? 'selected' : ''}}>JP</option>
									<option value="ch" {{$banner->lang == 'ch' ? 'selected' : ''}}>CH</option>
								</select>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">{{ __('banner.b_ifuse')}}</th>
							<td>
								<select class="tsa-select" name="active">
									<option value="1" {{$banner->active == 1 ? 'selected' : ''}}>{{ __('banner.b_use')}}</option>
									<option value="0" {{$banner->active == 0 ? 'selected' : ''}}>{{ __('banner.b_nouse')}}</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('banner.b_cha')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('banner.b_list')}}
				</button>
			</div>
		</form>
	</div>
	<div class="card-footer small text-muted">
		{{ $datetime }}{{ __('banner.b_update')}}
	</div>
</div>

@endsection

@section('script')

@endsection
