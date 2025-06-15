@props(['active'])


<li>
    <a
    @class([
        'hover:bg-gray-100 md:p-0 md:hover:bg-transparent md:hover:text-secondary-900 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700',
        'block py-2 px-3 border-b-2 border-accent-light-500 font-bold' => $active,
        'block py-2 px-3 text-gray-900' => !$active,
        ]) {{ $attributes }}>{{ $slot }}</a>
</li>
