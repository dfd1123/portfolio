@extends('layouts.app')

@section('content')
<script>
        $(document).ready(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $("#review_submit").click(function(){
                $.ajax({
	                    url: 'review_test/store',
	                    type: 'POST',
	                    /* send the csrf-token and the input to the controller */
	                    data: {_token: CSRF_TOKEN, id:{{Auth::user()->id}}, review_body:$("input[name='review_body']").val(), rating:$("input[name='rating']").val()},
	                    dataType: 'JSON',
	                    /* remind that 'data' is the response of the AjaxController */
	                    success: function (data) { 
	                    alert('댓글작성완료!'); 
	                    
	                    $('.review_after').append(data.review_body);
	                }
	            }); 
       		 });
   });    
</script>
    
댓글내용
<input type="text" name="review_body" />

평가점수
<input type="text" name="rating" />

<button type="button" id="review_submit">작성</button>


<br />

<br />

<div class="review_after"></div>




@endsection