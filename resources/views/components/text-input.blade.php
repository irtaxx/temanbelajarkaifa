@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-3.5 py-2.5 text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm placeholder:text-gray-400']) }}>
