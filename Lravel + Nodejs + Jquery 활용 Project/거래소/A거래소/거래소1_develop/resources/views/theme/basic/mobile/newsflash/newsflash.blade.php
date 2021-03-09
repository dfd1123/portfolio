@extends(session('theme').'.mobile.layouts.app') 
@section('content')
	@include(session('theme').'.mobile.notice.include.sub_menu')
	
<!-- scrl_wrap -->
<div id="newsplash_wrap" class="scrl_wrap m_cs_wrap">
	<table id="newsplash_tbl" class="cs_table" data-offset="15" data-count="{{$count}}">
		<tbody>
			@forelse($newsflashs as $newsflash)
			<tr>
				<td class="list_tit">
					<a href="{{route('newsflash_view',$newsflash->id)}}">
						<span>{{$newsflash->title}}</span>
					</a>
				</td>
				<td class="date_ymd">
					{{date("Y-m-d", $newsflash->created)}}
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="2" class="non_data">
					속보가 존재하지 않습니다.
				</td>
			</tr>
			@endforelse
		</tbody>
	</table>

</div>
<!-- //scrl_wrap -->

@endsection