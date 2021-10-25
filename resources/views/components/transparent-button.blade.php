<button
    type="submit" {{ $attributes->merge(['class' => 'text-sm font-semibold hover:text-white py-2 px-3 border hover:border-transparent rounded']) }}>
    {{ $slot }}
</button>
