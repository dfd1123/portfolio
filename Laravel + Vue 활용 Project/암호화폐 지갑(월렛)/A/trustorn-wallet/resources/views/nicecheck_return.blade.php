<script>
	window.parent.Vue.prototype.$EventBus.$emit('nice-check-result', {status: '{{ $status }}', message: {
		message: decodeURI('{{$message}}'),
		name: decodeURI('{{$name}}'),
		mobile_no: decodeURI('{{$mobile_no}}')
	}});
</script>