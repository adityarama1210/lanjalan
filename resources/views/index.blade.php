@if(session('error'))
	<h4>{{ session('error') }}</h4>
@endif
<form action="{{ route('search') }}" method="GET">
    <input type="text" name="query"/>
    <input type="submit"/>
</form>