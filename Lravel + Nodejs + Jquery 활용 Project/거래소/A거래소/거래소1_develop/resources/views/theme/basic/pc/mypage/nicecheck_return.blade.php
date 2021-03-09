@extends(session('theme').'.pc.layouts.app') 
@section('content')

<script>
	
	opener.parent.nicecheck_alert('{{ $status }}','{{ $message }}');
	self.close();
   

</script>
    

@endsection