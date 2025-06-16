<nav class="sticky nav-bar font-manrope border-gray-200 dark:bg-gray-900 px-10">
    <div class="flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('welcome') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img class="max-h-12" src="{{ asset('images/Logo.png') }}" alt="" srcset="">
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse items-center">
            @auth
            <a href="{{ route('dashboard') }}" class="inline-block text-sm px-6 py-2.5 leading-none border rounded-lg hover:bg-gray-200 mr-5 font-medium border-transparent lg:mt-0">
            Dashboard</a>
            <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start" class="w-10 h-10 mr-4 rounded-lg cursor-pointer object-cover" src="{{ auth()->user()->avatar ? asset( auth()->user()->avatar) : asset('images/Avatar.svg') }}" alt="User dropdown">

            <div id="userDropdown" class="z-10 hidden divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                <div>{{ Auth::user()->name }}</div>
                <div class="font-medium truncate">{{ Auth::user()->email }}</div>
                </div>
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                {{-- <li>
                    <a href="{{ route('user.show', ['user' => auth()->user()]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
                </li> --}}
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                </li>
                </ul>
                {{-- <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                    <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</button>
                    </form>
                </div> --}}
            </div>
            @endauth

            @guest                
            <a href="{{ route('signin') }}"
                class="inline-block text-sm px-8 py-2.5 leading-none border mr-1 md:mr-3 rounded-full font-bold bg-gray-200 text-dark-800 border-transparent hover:bg-gray-300 lg:mt-0">
                Sign in</a>
            @endguest

            <button data-collapse-toggle="navbar-cta" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-cta" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="justify-between hidden w-full md:flex items-center md:w-auto md:order-1" id="navbar-cta">
            <ul
                class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">Home</x-nav-link>
            </ul>
        </div>
    </div>
</nav>
