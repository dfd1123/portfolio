@extends('layouts.app')
@section('script')

<script>
	opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}','{{ $mobile_number }}','{{ $name_utf8 }}');
	self.close();
</script>
    

@endsection