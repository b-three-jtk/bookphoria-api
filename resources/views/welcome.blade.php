<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ page: 'landing', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" :class="{ 'dark bg-gray-900': darkMode === true }">
    <div class="flex h-screen overflow-hidden">
        <div class="relative flex flex-col flex-1 gap-10 w-full h-full overflow-x-hidden overflow-y-auto px-12">
            @include('partials.navbar')
            <div class="flex flex-row gap-5 h-full w-full mb-16">
                <div class="col-lg-10 col-md-8 col-sm-10 flex items-center justify-center h-full w-full pt-40">
                    <div
                        class="bg-lighter-100 dark:bg-gray-800 rounded-lg shadow-lg flex flex-col space-y-16 px-8 justify-center w-full h-screen">
                        <h1 class="text-6xl font-bold mb-4">Book<span>Phoria</span>:<br> Books for All</h1>
                        <p class="text-gray-600 dark:text-gray-400 font-manrope mb-6">Build your personal library, track
                            your reading journey, and share the joy of books with friends.</p>
                        <div class="flex row gap-2">
                            <a href="{{ route('signin') }}"
                                class="bg-transparent text-black border border-black px-6 py-4 rounded-full hover:bg-blue-600 transition duration-300">Get
                                Started</a>
                            <a href="{{ route('signup') }}"
                                class="flex items-center bg-brand-500 text-white px-6 py-4 rounded-full hover:bg-gray-700 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M5 12h14M13 18l6-6-6-6" />
                                </svg>
                            </a>
                        </div>
                        <div class="">
                            <p class="text-xl text-gray-500 font-normal font-italic font-manrope mb-3">OUR COMMUNITY</p>
                            <div class="flex flex-row items-center gap-2">
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-12 h-12 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                        <img src="{{ asset('images/user/user-22.jpg') }}" alt="user" />
                                    </div>
                                    <div
                                        class="w-12 h-12 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                        <img src="{{ asset('images/user/user-23.jpg') }}" alt="user" />
                                    </div>
                                    <div
                                        class="w-12 h-12 overflow-hidden border-2 border-white rounded-full dark:border-gray-900">
                                        <img src="{{ asset('images/user/user-24.jpg') }}" alt="user" />
                                    </div>
                                </div>
                                <div class="flex flex-col items-start">
                                    <p class="text-black text-xl font-manrope font-bold">18</p>
                                    <p class="text-gray-500 font-manrope text-xs">Book lovers joined</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-2 flex items-center justify-center h-full w-full pt-40">
                    <div class="bg-light-100 dark:bg-gray-800 rounded-lg shadow-lg h-screen w-full">
                        <img src="{{ asset('images/illustration/landing-1.png') }}" alt="Landing Image"
                            class="w-6xl -ml-14 h-auto">
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-8 col-sm-10 flex items-center justify-center h-full w-full pt-52">
                <div class="w-full">
                    <div class="grid grid-cols-3">
                        <div class="gap-5 flex flex-col">
                            <div class="col-lg-4 h-64 bg-polo-blue-200 rounded-l-lg shadow-lg"></div>
                            <div class="col-lg-4 h-80 bg-polo-blue-200 rounded-r-lg shadow-lg mr-5 overflow-hidden">
                                <img class="-mb-20" src="{{ asset('images/illustration/landing-2.png') }}"
                                    alt="">
                            </div>
                        </div>
                        <div class="gap-5 flex flex-col">
                            <div
                                class="col-lg-4 h-80 bg-polo-blue-200 rounded-b-lg flex flex-col justify-center items-center relative z-10">
                                <div class="text-center space-y-4">
                                    <h2 class="text-4xl font-bold">Top Trending Books</h2>
                                    <p class="text-gray-500 text-lg">Most Popular Reads of the Year</p>
                                    <div class="flex justify-center gap-4 mt-4 z-20">
                                        <button
                                            class="border border-black rounded-full px-6 py-2 hover:bg-black hover:text-white transition">
                                            View All
                                        </button>
                                        <button
                                            class="bg-brand-500 text-white rounded-full p-2 hover:bg-gray-800 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <path d="M5 12h14M13 18l6-6-6-6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 h-80 bg-polo-blue-200 rounded-r-lg shadow-lg overflow-hidden">
                                <img class="-pb-20 mt-10 w-80 mx-auto h-auto"
                                    src="{{ asset('images/illustration/landing-3.png') }}" alt="">
                            </div>
                        </div>
                        <div class="gap-5 flex flex-col">
                            <div class="col-lg-4 h-64 bg-polo-blue-200 rounded-r-lg shadow-lg"></div>
                            <div class="col-lg-4 h-80 bg-polo-blue-200 rounded-r-lg shadow-lg ml-5 overflow-hidden">
                                <img class="-pb-20 mt-10 w-60 mx-auto h-auto"
                                    src="{{ asset('images/illustration/landing-4.png') }}" alt="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex flex-row h-full w-full pt-52 gap-10">
                <div class="col-lg-6 h-full gap-5 w-full flex flex-col">
                    <div
                        class="bg-lighter-100 overflow-hidden dark:bg-gray-800 rounded-lg shadow-lg flex flex-col space-y-16 justify-center w-full h-1/2">
                        <img class="w-full h-auto" src="{{ asset('images/illustration/landing-6.jpg') }}"
                            alt="">
                    </div>
                    <div
                        class="bg-lighter-100 dark:bg-gray-800 rounded-lg shadow-lg flex flex-col space-y-16 px-8 justify-center w-full h-1/2">
                        <h3 class="text-3xl font-bold mb-2">Books for All</h3>
                        <p class="text-gray-600 text-sm dark:text-gray-400 font-manrope mb-2">Build your personal
                            library, track
                            your reading journey, and share the joy of books with friends.</p>
                        <div class="flex row gap-2">
                            <a href="{{ route('signin') }}"
                                class="bg-transparent text-black border border-black px-4 py-2 rounded-full hover:bg-blue-600 transition duration-300">Get
                                Started</a>
                            <a href="{{ route('signup') }}"
                                class="flex items-center bg-black text-white px-4 py-2 rounded-full hover:bg-gray-700 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M5 12h14M13 18l6-6-6-6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 w-full h-full">
                    <div
                        class="bg-lighter-100 h-full dark:bg-gray-800 rounded-lg shadow-lg flex flex-col space-y-16 overflow-hidden justify-center w-full">
                        <img class="w-full h-auto" src="{{ asset('images/illustration/landing-5.jpg') }}"
                            alt="">
                    </div>
                </div>
            </div>

            <div class="flex flex-col h-screen relative items-center justify-center text-center w-full py-50">
                <div class="rounded-full absolute -z-10 left-10 -mb-2 overflow-hidden w-20 h-20">
                    <img src="{{ asset('images/user/user-01.jpg') }}" alt="">
                </div>
                <div class="rounded-full absolute -z-10 overflow-hidden -mt-28 w-24 h-24">
                    <img src="{{ asset('images/user/user-02.jpg') }}" alt="">
                </div>
                <div class="rounded-full absolute right-7 -z-10 overflow-hidden -mt-16 w-16 h-16">
                    <img src="{{ asset('images/user/user-03.jpg') }}" alt="">
                </div>
                <p class="text-gray-600 dark:text-gray-400 font-manrope text-5xl mb-6">Connect with fellow book lovers,
                    share your thoughts, and discover new reads.</p>
                <div class="flex flex-row gap-5">
                    <a href="{{ route('signin') }}"
                    class="bg-transparent text-black border border-black px-6 py-4 rounded-full hover:bg-polo-blue-600 transition duration-300">Get
                    Started</a>
                <a href="{{ route('signup') }}"
                    class="flex items-center bg-black text-white px-6 py-4 rounded-full hover:bg-gray-700 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 12h14M13 18l6-6-6-6" />
                    </svg>
                </a>
                </div>
            </div>

            <div class="flex flex-row justify-between py-12">
                <div class="flex flex-row items-center justify-start gap-6">
                    <h1>BookPhoria</h1>
                    <ul class="flex flex-row gap-5 font-manrope text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('signin') }}">About Us</a></li>
                        <li><a href="{{ route('signin') }}">Contact</a></li>
                        <li><a href="{{ route('signin') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="flex flex-row items-center justify-end gap-6">
                    <a href="#"
                        class="text-gray-600 dark:text-gray-400 hover:text-blue-600 transition duration-300">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                clip-rule="evenodd" />
                        </svg>

                    </a>
                    <a href="#"
                        class="text-gray-600 dark:text-gray-400 hover:text-blue-600 transition duration-300">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12.006 2a9.847 9.847 0 0 0-6.484 2.44 10.32 10.32 0 0 0-3.393 6.17 10.48 10.48 0 0 0 1.317 6.955 10.045 10.045 0 0 0 5.4 4.418c.504.095.683-.223.683-.494 0-.245-.01-1.052-.014-1.908-2.78.62-3.366-1.21-3.366-1.21a2.711 2.711 0 0 0-1.11-1.5c-.907-.637.07-.621.07-.621.317.044.62.163.885.346.266.183.487.426.647.71.135.253.318.476.538.655a2.079 2.079 0 0 0 2.37.196c.045-.52.27-1.006.635-1.37-2.219-.259-4.554-1.138-4.554-5.07a4.022 4.022 0 0 1 1.031-2.75 3.77 3.77 0 0 1 .096-2.713s.839-.275 2.749 1.05a9.26 9.26 0 0 1 5.004 0c1.906-1.325 2.74-1.05 2.74-1.05.37.858.406 1.828.101 2.713a4.017 4.017 0 0 1 1.029 2.75c0 3.939-2.339 4.805-4.564 5.058a2.471 2.471 0 0 1 .679 1.897c0 1.372-.012 2.477-.012 2.814 0 .272.18.592.687.492a10.05 10.05 0 0 0 5.388-4.421 10.473 10.473 0 0 0 1.313-6.948 10.32 10.32 0 0 0-3.39-6.165A9.847 9.847 0 0 0 12.007 2Z"
                                clip-rule="evenodd" />
                        </svg>

                    </a>
                    <a href="#"
                        class="text-gray-600 dark:text-gray-400 hover:text-blue-600 transition duration-300">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M22 5.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.343 8.343 0 0 1-2.605.981A4.13 4.13 0 0 0 15.85 4a4.068 4.068 0 0 0-4.1 4.038c0 .31.035.618.105.919A11.705 11.705 0 0 1 3.4 4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 6.1 13.635a4.192 4.192 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 2 18.184 11.732 11.732 0 0 0 8.291 20 11.502 11.502 0 0 0 19.964 8.5c0-.177 0-.349-.012-.523A8.143 8.143 0 0 0 22 5.892Z"
                                clip-rule="evenodd" />
                        </svg>

                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
<script>
    function navToogle() {
        var x = document.getElementById("nav-items");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    window.onscroll = function() {
        myFunction()
    };

    var navlist = document.querySelector("#nav-list");
    var sticky = navlist.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navlist.classList.add("sticky")
        } else {
            navlist.classList.remove("sticky");
        }
    }
</script>

</html>
