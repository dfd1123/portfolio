@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
    <li class="breadcrumb-item active">{{ __('admin_p2p.set')}}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
    <div class="card-header">{{ __('admin_p2p.history')}}</div>
    <div class="card-body">
        <ul class="nav nav-tabs">
            @if($types == 0)
                <li class="active"><a href="{{route('admin.p2p_list',0)}}">{{ __('admin_p2p.exchage')}}</a></li>
                <li><a href="{{route('admin.p2p_list',1)}}">{{ __('admin_p2p.ok1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',4)}}">{{ __('admin_p2p.deal1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',5)}}">{{ __('admin_p2p.deal2')}}</a></li>
                <li><a href="{{route('admin.p2p_list',6)}}">{{ __('admin_p2p.deal3')}}</a></li>
            @elseif($types == 1)
                <li><a href="{{route('admin.p2p_list',0)}}">{{ __('admin_p2p.exchage')}}</a></li>
                <li class="active"><a href="{{route('admin.p2p_list',1)}}">{{ __('admin_p2p.ok1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',4)}}">{{ __('admin_p2p.deal1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',5)}}">{{ __('admin_p2p.deal2')}}</a></li>
                <li><a href="{{route('admin.p2p_list',6)}}">{{ __('admin_p2p.deal3')}}</a></li>
            @elseif($types == 4)
                <li><a href="{{route('admin.p2p_list',0)}}">{{ __('admin_p2p.exchage')}}</a></li>
                <li><a href="{{route('admin.p2p_list',1)}}">{{ __('admin_p2p.ok1')}}</a></li>
                <li class="active"><a href="{{route('admin.p2p_list',4)}}">{{ __('admin_p2p.deal1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',5)}}">{{ __('admin_p2p.deal2')}}</a></li>
                <li><a href="{{route('admin.p2p_list',6)}}">{{ __('admin_p2p.deal3')}}</a></li>
            @elseif($types == 5)
                <li><a href="{{route('admin.p2p_list',0)}}">{{ __('admin_p2p.exchage')}}</a></li>
                <li><a href="{{route('admin.p2p_list',1)}}">{{ __('admin_p2p.ok1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',4)}}">{{ __('admin_p2p.deal1')}}</a></li>
                <li class="active"><a href="{{route('admin.p2p_list',5)}}">{{ __('admin_p2p.deal2')}}</a></li>
                <li><a href="{{route('admin.p2p_list',6)}}">{{ __('admin_p2p.deal3')}}</a></li>
                @elseif($types == 6)
                <li><a href="{{route('admin.p2p_list',0)}}">{{ __('admin_p2p.exchage')}}</a></li>
                <li><a href="{{route('admin.p2p_list',1)}}">{{ __('admin_p2p.ok1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',4)}}">{{ __('admin_p2p.deal1')}}</a></li>
                <li><a href="{{route('admin.p2p_list',5)}}">{{ __('admin_p2p.deal2')}}</a></li>
                <li class="active"><a href="{{route('admin.p2p_list',6)}}">{{ __('admin_p2p.deal3')}}</a></li>
            @endif
        </ul>
        <div class="table-responsive tsa-table-wrap">
            <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
	                    <th style="width: 5%;">ID</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.type')}}</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.name')}}</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.kind')}}</th>
	                    <th style="width: 10%;">{{ __('admin_p2p.coinprice')}}</th>
	                    <th style="width: 10%;">{{ __('admin_p2p.qua')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.adrs')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.buy')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.sell')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.cf')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($p2ps as $p2p)
                    <tr>
                        <td>{{$p2p->id}}</td>
                        <td>{{$p2p->type}}</td>
                        <td>{{$p2p->name}}</td>
                        <td>{{$p2p->coin_type}}</td>
                        <td>{{$p2p->coin_price." ".$p2p->country_money}}</td>
                        <td>{{$p2p->coin_amount." ".$p2p->coin_type}}</td>
                        <td>{{$p2p->b_coin_address}}</td>
                        <td>{{$p2p->b_account}}</td>
                        <td>{{$p2p->s_account}}</td>
                        <td>
                            <button class="myButton navy" onclick="location.href='{{route('admin.p2p_detail',$p2p->id)}}'">{{ __('admin_p2p.dealinfo')}}</button>
                        @if($p2p->confirm > 0 && $p2p->confirm < 4 && $p2p->deleted != 1 && $p2p->state != 'stop')
                            <button class="myButton xbtn" onclick="location.href='{{route('admin.p2p_stop',$p2p->id)}}'">{{ __('admin_p2p.stop')}}</button>
                        @endif
                        </td>                  
                    </tr>
                    @empty
                    <tr>
                    	<td colspan="10" >{{ __('admin_p2p.nohere')}}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
            
    </div>
    <div class="card-footer small text-muted">{{ $datetime }}{{ __('admin_p2p.up')}}</div>
</div>
@endsection

@section('script')



@endsection