<button {{ $attributes->merge(['type' => 'submit', 'class' => 'red']) }}>
    {{ $slot }}
</button>
