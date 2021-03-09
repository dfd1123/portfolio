@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">
      경기일정 관리
	</li>
</ol>

<div class="card mb-3 tsa-card">
	<div class="card-header">
		경기일정 추가
	</div>
	<div class="card-body">
    <form action="{{route('admin.game_schedule_update_detail', [$id,$index])}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="game_li_wrap">
				<div class="game_li_con">
					<div class="game_time">
						<select name="hour">
              @for($i=0; $i<=23; $i++)
                <option value="{{sprintf('%02d',$i)}}" {{ isset($schedule_lists->game_time) ? (explode(" ", $schedule_lists->game_time)[1] == sprintf('%02d',$i) ? 'selected' : '') : '' }}>{{sprintf('%02d',$i)}}시</option>
              @endfor
            </select>
            :
            <select name="min">
              @for($i=0; $i<=60; $i++)
                <option value="{{sprintf('%02d',$i)}}" {{ isset($schedule_lists->game_time) ? (explode(" ", $schedule_lists->game_time)[3] == sprintf('%02d',$i) ? 'selected' : '') : '' }}>{{sprintf('%02d',$i)}}분</option>
              @endfor
            </select>
					</div>
          <div style="text-align: center;margin: 10px 0;">
            <select name="game_type" id="game_type" style="width: 300px;padding: 0 10px;" required>
              <option value="" >경기 종류를 선택해 주세요.</option>
              <option value="축구" {{ isset($schedule_lists->game_type) ? ($schedule_lists->game_type == '축구' ? 'selected' : '') : '' }}>축구</option>
              <option value="농구" {{ isset($schedule_lists->game_type) ? ($schedule_lists->game_type == '농구' ? 'selected' : '') : '' }}>농구</option>
              <option value="야구" {{ isset($schedule_lists->game_type) ? ($schedule_lists->game_type == '야구' ? 'selected' : '') : '' }}>야구</option>
            </select>
            
        <input type="text" name="league_name" id="league_name" placeholder="리그 이름을 입력해주세요." value = "{{ isset($schedule_lists->league_name) ? $schedule_lists->league_name : '' }}" style="width: 300px;padding: 0 10px;">
          </div>
					<div class="game_li">
						<div class="game_team_infor">
							<div>
                <label for="team1_symbol">
                  <img src="/storage/image/{{ isset($schedule_lists->team1_symbol) ? 'game_schedule'.$schedule_lists->team1_symbol : 'homepage/mobile_icon/no_symbol.svg' }}" id="team1_symbol_section" alt="" />
                </label>
                <input type="file" name="team1_symbol[]" id="team1_symbol" style="display:none;" />
								<div>
                  <input type="text" name="team1" placeholder="구단1 이름 입력" value="{{ isset($schedule_lists->team1) ? $schedule_lists->team1 : '' }}" />
                  <input type="number" name="team1_score" min="0" placeholder="구단1 점수 입력" value="{{ isset($schedule_lists->team1_score) ? $schedule_lists->team1_score : '' }}"/>
								</div>
							</div>
						</div>
						<div class="game_team_infor">
							<div>
                <label for="team2_symbol">
                  <img src="/storage/image/{{ isset($schedule_lists->team2_symbol) ? 'game_schedule'.$schedule_lists->team2_symbol : 'homepage/mobile_icon/no_symbol.svg' }}" id="team2_symbol_section" alt="" />
                </label>
                <input type="file" name="team2_symbol[]" id="team2_symbol" style="display:none;" />
								<div>
                  <input type="text" name="team2" placeholder="구단2 이름 입력" value="{{ isset($schedule_lists->team2) ? $schedule_lists->team2 : '' }}" />
                  <input type="number" name="team2_score" min="0" placeholder="구단2 점수 입력"  value="{{ isset($schedule_lists->team2_score) ? $schedule_lists->team2_score : '' }}"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				수정
                </button>
                <button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.game_schedule_delete_detail', [$id, $index])}}'">
                삭제
                </button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.game_schedule_edit', $id)}}'">
				{{ __('notice.cel')}}
				</button>
      </div>
    </form>
	</div>
</div>

<script>
function readURLSymbol1(input) {
 if (input.files && input.files[0]) {
  var reader = new FileReader();
 
  reader.onload = function (e) {
   $('#team1_symbol_section').attr('src', e.target.result);  
  }
 
  reader.readAsDataURL(input.files[0]);
  }
}

function readURLSymbol2(input) {
 if (input.files && input.files[0]) {
  var reader = new FileReader();
 
  reader.onload = function (e) {
   $('#team2_symbol_section').attr('src', e.target.result);  
  }
 
  reader.readAsDataURL(input.files[0]);
  }
}

$("#team1_symbol").change(function(){
  readURLSymbol1(this);
});
 
$("#team2_symbol").change(function(){
  readURLSymbol2(this);
});
</script>

@endsection

