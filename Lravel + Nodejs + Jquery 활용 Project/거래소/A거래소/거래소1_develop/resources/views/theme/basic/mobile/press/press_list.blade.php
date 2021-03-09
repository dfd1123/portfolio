@extends(session('theme').'.mobile.layouts.app') 
@section('content')
	@include(session('theme').'.mobile.notice.include.sub_menu')
	
<!-- scrl_wrap -->
<div id="_wrap" class="scrl_wrap m_cs_wrap">
	<table id="notice_tbl" class="cs_table" >
		<tbody>
			
			<tr>
				<td class="list_tit">
					<a href="#">
						<span>예시 ) 보도기사 제목입니다.</span>
					</a>
				</td>
				<td class="date_ymd">
					2019-08-21
				</td>
            </tr>
            
		</tbody>
	</table>

</div>
<!-- //scrl_wrap -->

@endsection