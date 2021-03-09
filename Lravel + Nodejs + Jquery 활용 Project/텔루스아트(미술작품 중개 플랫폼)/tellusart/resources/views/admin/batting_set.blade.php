@extends('admin.layouts.app')

@section('content')

<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">베팅 설정</li>
</ol>

<form method="post" action="{{route('admin.batting_set_update')}}">

	@csrf
<div class="tsa-out-box com" style="display:block;">
    <h3>베팅기간</h3>
  <div class="form-group">
    <label for="batting_term" class="tsa-label-st">베팅기간</label>
    <input type="number" id="batting_term" name="batting_term" value="{{ isset($batting_set) ? $batting_set->batting_term : '' }}" required="required" /> 일
  </div>
  <h3>베팅 배당 비율</h3>
  <div class="form-group">
    <label for="reward_seller" class="tsa-label-st">판매자 배당 비율</label>
    <input type="number" id="reward_seller" name="reward_seller" value="{{  isset($batting_set) ? $batting_set->reward_seller * 100 : '' }}" required="required" /> %
  </div>
  <div class="form-group">
    <label for="reward_review" class="tsa-label-st">리뷰 배당 비율</label>
    <input type="number" id="reward_review" name="reward_review" value="{{  isset($batting_set) ? $batting_set->reward_review * 100 : '' }}" required="required" /> %
  </div>
  <div class="form-group">
    <label for="reward_management" class="tsa-label-st">운영 배당 비율</label>
    <input type="number" id="reward_management" name="reward_management" value="{{  isset($batting_set) ? $batting_set->reward_management * 100 : '' }}" required="required" /> %
  </div>
  <div class="form-group">
    <label for="reward_welfare" class="tsa-label-st">문화복지 배당 비율</label>
    <input type="number" id="reward_welfare" name="reward_welfare" value="{{  isset($batting_set) ? $batting_set->reward_welfare * 100 : '' }}" required="required" /> %
  </div>
  <div class="form-group">
    <label for="reward_people" class="tsa-label-st">베팅자 배당 비율</label>
    <input type="number" id="reward_people" name="reward_people" value="{{  isset($batting_set) ? $batting_set->reward_people * 100 : '' }}" required="required" /> %
  </div>

  <button type="submit">적용</button>
</form>



@endsection