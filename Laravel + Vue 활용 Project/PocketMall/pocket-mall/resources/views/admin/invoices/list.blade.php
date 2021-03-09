@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">견적서 요청 리스트</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.item_options')}}">견적서 요청</a></li>
        <li class="breadcrumb-item active">견적서 요청 관리</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">no</th>
              <th scope="col">이름</th>
              <th scope="col">전화번호</th>
              <th scope="col">이메일</th>
              <th scope="col">요청 날짜</th>
            </tr>
          </thead>
          <tbody>
            @foreach($invoices as $invoice)
            <tr>
              <th scope="row">{{$invoice->rq_id}}</th>
              <td><a href="/check-invoice?rq_id={{$invoice->rq_id}}" target="_blank">{{$invoice->req_name}}</a></td>
              <td><a href="/check-invoice?rq_id={{$invoice->rq_id}}" target="_blank">{{$invoice->req_tel}}</a></td>
              <td><a href="/check-invoice?rq_id={{$invoice->rq_id}}" target="_blank">{{$invoice->req_email}}</a></td>
              <td><a href="/check-invoice?rq_id={{$invoice->rq_id}}" target="_blank">{{$invoice->created_at}}</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
          @if($invoices)
            <div style="text-align:center; margin: 30px 0;">
              {!! $invoices->render() !!}
            </div>
          @endif
        
        </div>
    </div>
</div>

@endsection
