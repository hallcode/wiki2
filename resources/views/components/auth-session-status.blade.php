@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert green']) }}>
        {{ $status }}
    </div>
@endif
