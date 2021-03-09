@extends('admin.layouts.app')

@section('content')

<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
	<li class="breadcrumb-item active">{{ __('admin_p2p.ptop')}}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
	<div class="card-header">
    {{ __('admin_p2p.detail')}}
	</div>
	<div class="table-responsive tsa-table-wrap">
            <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
	                    <th style="width: 5%;">ID</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.type')}}</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.wr')}}</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.kind')}}</th>
	                    <th style="width: 10%;">{{ __('admin_p2p.price')}}</th>
	                    <th style="width: 10%;">{{ __('admin_p2p.qua')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.cf2')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.edit')}}</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td>{{$table->id}}</td>
                        <td>{{$table->type}}</td>
                        <td>{{$table->name}}</td>
                        <td>{{$table->coin_type}}</td>
                        <td>{{$table->coin_price." ".$table->country_money}}</td>
                        <td>{{$table->coin_amount." ".$table->coin_type}}</td>
                        <td>{{$table->start}}</td>
                        <td>{{$table->update_at}}</td>            
                    </tr>
                   
                </tbody>
            </table>
    </div>
    <div class="table-responsive tsa-table-wrap">
            <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
	                    <th style="width: 5%;">ID</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.type')}}</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.name')}}</th>
	                    <th style="width: 5%;">{{ __('admin_p2p.adrs')}}</th>
	                    <th style="width: 10%;">{{ __('admin_p2p.bookinfo')}}</th>
	                    <th style="width: 10%;">{{ __('admin_p2p.cf2')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.edit')}}</th>
                        <th style="width: 15%;">{{ __('admin_p2p.comeday')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($p2ps as $p2p)
                    <tr>
                        <td>{{$p2p->uid}}</td>
                        <td>{{$p2p->tr_type}}</td>
                        <td>{{$p2p->username}}</td>
                        <td>{{$p2p->coin_address}}</td>
                        <td>{{$p2p->account}}</td>
                        <td>{{$p2p->start_day}}</td>
                        <td>{{$p2p->update_at}}</td>
                        <td>{{$p2p->end_day}}</td>            
                    </tr>
                    @empty
                   @endforelse
                </tbody>
            </table>
    </div>
	<div class="card-footer small text-muted">{{$datetime}}{{ __('admin_p2p.update')}.</div>
</div>

@endsection

@section('script')



@endsection