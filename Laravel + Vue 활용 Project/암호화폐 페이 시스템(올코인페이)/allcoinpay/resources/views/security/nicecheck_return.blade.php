@extends('layouts.app')
@section('script')

<script>
	opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}');
	self.close();
</script>
    

@endsection