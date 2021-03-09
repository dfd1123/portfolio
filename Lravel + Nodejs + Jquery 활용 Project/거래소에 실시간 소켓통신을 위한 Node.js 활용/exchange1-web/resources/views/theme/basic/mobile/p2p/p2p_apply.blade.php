@extends(session('theme').'.mobile.layouts.app')

@section('content')
<form action="{{route('p2p_insert')}}" method="POST">
    @csrf

    <p>{{__('p2p.'.$p2ps->type.'s')}}</p>
    <p>{{__('ptop.people')}} : {{$p2ps->name}}</p>
    <p>{{__('ptop.coin_list')}} : {{__('coin_name.'.$p2ps->coin_type)}} ({{strtoupper($p2ps->coin_type)}})</p>
    <p>{{__('ptop.coinage')}} : {{$p2ps->coin_amount}} {{strtoupper($p2ps->coin_type)}}</p>
    <p>{{__('ptop.coinprice')}} : {{$p2ps->coin_price}} {{strtoupper($p2ps->country_money)}}</p>
    <p>{{strtoupper($p2ps->coin_type)}} {{__('ptop.wallet_address')}} : <input type="text" name="coin_address" required></p>
    <p>{{__('ptop.account_info')}} : <input type="text" name="bank" placeholer="{{ __('ptop.inbank') }}" required><input type="text" name="account" placeholder="{{ __('ptop.inaccount') }}" required></p>
    
    
    <button type="submit">{{__('p2p.'.$p2ps->type.'s')}} {{__('ptop.apply1')}}</button>
 </form> 

@endsection