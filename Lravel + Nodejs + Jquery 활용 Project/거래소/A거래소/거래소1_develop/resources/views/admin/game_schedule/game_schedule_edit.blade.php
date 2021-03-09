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
    <form action="{{route('admin.game_schedule_update', $id)}}" method="post" enctype="multipart/form-data">
      @csrf
			<div class="schedule_right wow flipInX" data-wow-delay="0.6s">
        <h5>{{date('y.m.d', strtotime($game_schedule->date))}}({{$yoil_name}})</h5>
        
        <div class="schedule_li_wrap">
          <ul class="schedule_li_con">
            @forelse($schedule_lists as $index=>$schedule_list )
            <li class="schedule_li">
              <span>{{$schedule_list->game_time}}</span>
              
              <div class="verse_con">
                <div>
                  <div class="symbol_icon">
                    <img src="/storage/image/game_schedule{{$schedule_list->team1_symbol}}" alt="" />
                  </div>
                  <span>{{$schedule_list->team1}}</span>
                </div>
                <div>
                  <div class="symbol_icon">
                    <img src="/storage/image/game_schedule{{$schedule_list->team2_symbol}}" alt="" />
                  </div>
                  <span>{{$schedule_list->team2}}</span>
                </div>
              </div>
              
              <div>
                <span>{{ isset($schedule_list->game_type) ? $schedule_list->game_type : '축구' }}</span>
                <span>{{ isset($schedule_list->team1_score) ? $schedule_list->team1_score : 0 }} : {{ isset($schedule_list->team2_score) ? $schedule_list->team2_score : 0 }}</span>
                <a href="{{route('admin.game_schedule_detail', [$game_schedule->id,$index])}}">수정하기</a>
              </div>
            </li>
            @empty
            <li class="schedule_li">
              <span class="no_game_today">AM 00:00</span>
              <div class="verse_con">
                <div class="no_game_today">
                  경기 일정이 없습니다.
                </div>
              </div>
            </li>
            @endforelse
          </ul>
        </div>
      </div>
      <div class="game_li_wrap">
				<div class="game_li_con">
					<div class="game_time">
						<select name="hour">
              @for($i=0; $i<=23; $i++)
                <option value="{{sprintf('%02d',$i)}}">{{sprintf('%02d',$i)}}시</option>
              @endfor
            </select>
            :
            <select name="min">
              @for($i=0; $i<=60; $i++)
                <option value="{{sprintf('%02d',$i)}}">{{sprintf('%02d',$i)}}분</option>
              @endfor
            </select>
					</div>
          <div style="text-align: center;margin: 10px 0;">
            <select name="game_type" id="game_type" style="width: 300px;padding: 0 10px;" required>
              <option value="" >경기 종류를 선택해 주세요.</option>
              <option value="축구" >축구</option>
              <option value="농구" >농구</option>
              <option value="야구" >야구</option>
            </select>
            
            <input type="text" name="league_name" id="league_name" placeholder="리그 이름을 입력해주세요." style="width: 300px;padding: 0 10px;">
          </div>
					<div class="game_li">
						<div class="game_team_infor">
							<div>
                <label for="team1_symbol">
                  <img src="/storage/image/homepage/mobile_icon/no_symbol.svg" id="team1_symbol_section" alt="" />
                </label>
                <input type="file" name="team1_symbol[]" id="team1_symbol" style="display:none;" />
								<div>
                  <input type="text" name="team1" placeholder="구단1 이름 입력" />
                  <input type="number" name="team1_score" min="0" placeholder="구단1 점수 입력" />
								</div>
							</div>
						</div>
						<div class="game_team_infor">
							<div>
                <label for="team2_symbol">
                  <img src="/storage/image/homepage/mobile_icon/no_symbol.svg" id="team2_symbol_section" alt="" />
                </label>
                <input type="file" name="team2_symbol[]" id="team2_symbol" style="display:none;" />
								<div>
                  <input type="text" name="team2" placeholder="구단2 이름 입력" />
                  <input type="number" name="team2_score" min="0" placeholder="구단2 점수 입력" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mint_btn_group">
				<button type="submit" class="btn btn-default mint_btn">
				{{ __('notice.add2')}}
				</button>
				<button type="button" class="btn btn-default mint_btn" onclick="location.href='{{route('admin.game_schedule_list')}}'">
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

