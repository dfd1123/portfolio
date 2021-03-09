@extends(session('theme').'.mobile.layouts.app')
@section('content')

<script>
	
	opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}');
	self.close();
   

</script>
    

@endsection