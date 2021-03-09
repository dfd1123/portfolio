@extends('admin.layouts.app')

@section('content')

<style>
 @keyframes spin {
	to { -webkit-transform: rotate(360deg); }
  }
  @-webkit-keyframes spin {
	to { -webkit-transform: rotate(360deg); }
  }
</style>


<!-- Breadcrumbs-->
<ol class="breadcrumb tsa-top-tit">
<li class="breadcrumb-item active">{{ __('user.user_ad') }}</li>
</ol>

<!-- DataTables Example -->
<div class="card mb-3 tsa-card">
<!-- <div class="card-header">
    회원리스트</div> -->
    <div class="posi_wrap" style="position: absolute;top: 0;left: 0;z-index: 5;width: 100%;height: 100%;display: flex;flex-direction: column;background:gray;opacity:0.8">
        <div style="display: block;vertical-align: middle;text-align: center;height: 100%;padding-top: 300px;">
            <div id="loading" style="display: inline-block;width: 100px;height: 100px;border: 5px solid #fff;border-radius: 50%;border-top-color: #00b9ff;animation: spin 1s ease-in-out infinite;-webkit-animation: spin 1s ease-in-out infinite;"></div>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs">
            @foreach($coins as $coin)
                <li class="{{ ($coin->api == $api) ? 'active' : '' }}"><a href="{{route('admin.user_balance_coin', $coin->api )}}">{{ strtoupper($coin->api) }}</a></li>
            @endforeach
        </ul>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:;">이름</th>
                        <th>E-mail</th>
                        <th>{{ strtoupper($api) }}</th>
                    
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->{"available_balance_".$api} }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" >No data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
    
    <div class="card-footer small text-muted">{{ $datetime }} {{ __('user.user_sentence_2') }}</div>
</div>


@endsection

@section('script')
<script>
    $(document).ready(function() {
        
      $('#dataTable').DataTable({
          "lengthMenu" : [10,20,25,50,100],
          "pageLength" : 20
      });
      $('.posi_wrap').css('display','none');
    });
</script>
@endsection