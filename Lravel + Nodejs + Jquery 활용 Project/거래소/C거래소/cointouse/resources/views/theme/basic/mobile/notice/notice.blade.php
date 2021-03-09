@extends(session('theme').'.mobile.layouts.app') 
@section('content')
	@include(session('theme').'.mobile.notice.include.sub_menu')
	
<!-- scrl_wrap -->
<div id="notice_wrap" class="scrl_wrap m_cs_wrap">
	<table id="notice_tbl" class="cs_table" data-offset="15" data-count="{{$count}}">
		<tbody>
			@forelse($notices as $notice)
			<tr>
				<td class="list_tit">
					<a href="{{route('notice_view',$notice->id)}}">
						<span>{{$notice->title}}</span>
					</a>
				</td>
				<td class="date_ymd">
					{{date("Y-m-d", $notice->created)}}
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="2" class="non_data">
					{{ __('support.support_sentence2') }}
				</td>
			</tr>
			@endforelse
		</tbody>
	</table>

</div>
<!-- //scrl_wrap -->

@endsection