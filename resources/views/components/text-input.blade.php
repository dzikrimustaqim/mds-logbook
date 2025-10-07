@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-4 border-mds-black bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-mds-primary focus:ring-mds-primary shadow-chunky-sm rounded-none']) }}>
