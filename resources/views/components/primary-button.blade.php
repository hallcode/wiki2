<button {{ $attributes->merge(['type' => 'submit', 'class' => 'primary']) }}>
    {{ $slot }}
</button>
