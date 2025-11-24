<a href="{{ url('/') }}">
<img
src="{{ asset('images/a1.jpg') }}"
alt="{{ config('app.name', 'Laravel') }} Logo"
{{ $attributes->merge(['class' => 'h-20 w-20 rounded-full object-cover']) }}
style="height: 8rem; width: 8rem;"
>
</a>