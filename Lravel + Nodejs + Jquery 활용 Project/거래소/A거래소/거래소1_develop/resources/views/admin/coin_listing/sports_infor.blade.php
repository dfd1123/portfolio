@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
	스포츠코인 정보
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
	스포츠코인 정보 수정
	</div>
	<div class="card-body">
		<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post" action="{{route('admin.sports_infor_update', $coin->id)}}">
			@csrf
			<div class="table-responsive tsa-event-table">
				<table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
					<tbody>
						<tr>
							<th style="width:10%;">코인명</th>
							<td>
                                <input type="text" name="name" class="form-control tsa-input-st" value="{{$coin->name}}" readonly="readonly" required="required"/>
							</td>
						</tr>
						<tr>
							<th style="width:10%;">리그 종류</th>
							<td>
								<input type="text" name="league_name" class="form-control tsa-input-st" value="{{$coin->league_name}}" required="required"/>
							</td>
                        </tr>
                        <tr>
							<th style="width:10%;">리그 순위</th>
							<td>
                                <input type="number" name="league_rank" class="form-control tsa-input-st" value="{{$coin->league_rank}}"/>
							</td>
                        </tr>
                        <tr>
							<th style="width:10%;">구단 가치</th>
							<td>
                                <div>
                                    <label for="kr_value">한국</label>
                                    <input type="text" name="club_value_kr" class="form-control tsa-input-st" value="{{$coin->club_value_kr}}" placeholder="EX) 4조 3,000억원" required="required"/>
                                </div>
								<div>
                                    <label for="kr_value">中國</label>
                                    <input type="text" name="club_value_ch" class="form-control tsa-input-st" value="{{$coin->club_value_ch}}" required="required"/>
                                </div>
                                <div>
                                    <label for="kr_value">日本</label>
                                    <input type="text" name="club_value_jp" class="form-control tsa-input-st" value="{{$coin->club_value_jp}}" required="required"/>
                                </div>
                                <div>
                                    <label for="kr_value">In dollars</label>
                                    <input type="text" name="club_value_en" class="form-control tsa-input-st" value="{{$coin->club_value_en}}" required="required"/>
                                </div>
							</td>
                        </tr>
                        <tr>
							<th style="width:10%;">전세계 팬 수</th>
							<td>
                                <div>
                                    <label for="kr_value">한국</label>
                                    <input type="text" name="world_pan_kr" class="form-control tsa-input-st" value="{{$coin->world_pan_kr}}" placeholder="EX) 5억 1000명" required="required"/>
                                </div>
								<div>
                                    <label for="kr_value">中國</label>
                                    <input type="text" name="world_pan_ch" class="form-control tsa-input-st" value="{{$coin->world_pan_ch}}" required="required"/>
                                </div>
                                <div>
                                    <label for="kr_value">日本</label>
                                    <input type="text" name="world_pan_jp" class="form-control tsa-input-st" value="{{$coin->world_pan_jp}}" required="required"/>
                                </div>
                                <div>
                                    <label for="kr_value">In dollars</label>
                                    <input type="text" name="world_pan_en" class="form-control tsa-input-st" value="{{$coin->world_pan_en}}" required="required"/>
                                </div>
							</td>
                        </tr>
					</tbody>
				</table>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn" >
				{{ __('faq.edit')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{url()->previous()}}'">
				{{ __('event.gg')}}
				</button>
			</div>
		</form>
	</div>
</div>

@endsection
