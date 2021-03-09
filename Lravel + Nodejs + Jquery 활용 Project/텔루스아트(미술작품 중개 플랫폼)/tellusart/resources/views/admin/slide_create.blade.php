@extends('admin.layouts.app')

@section('content')

<form enctype="multipart/form-data" class="event_edit_adm_form" id="event_add_form" method="post"
    action="{{route('admin.slide_store')}}">

    @csrf

    <div class="card mb-3 tsa-card">
        <div class="card-header">
            슬라이드 추가
        </div>
        <div class="card-body">
            <div class="table-responsive tsa-event-table" style="width:1000px;margin:0 auto;">
                <table class="table table-bordered cate_adm_table" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <th style="width:20%;">설명</th>
                            <td><input type="text" name="info" class="form-control tsa-input-st" required="required" /></td>
                        </tr>
                        <tr>
                            <th>이미지파일</th>
                            <td>
                                <div class="filebox">
                                    <label for="file" class="myButton use">선택</label>
                                    <input type="file" id="file" name="file" />
                                    <span class="filename">
                                        (이미지파일 없음)
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="org_btn_group">
                <button type="submit" class="btn btn-default org_btn">등록</button>
                <button type="button" class="btn btn-default org_btn"
                    onclick="location.href='{{url()->previous()}}'">목록</button>
            </div>
        </div>
    </div>
</form>

@endsection

@section('script')

<script>
    $('#file').change(function(e){
		var input = this;
		if (input.files && input.files[0]) {
			$(input).parent().find('.filename').text(input.files[0].name);
		}
	});
</script>

@endsection