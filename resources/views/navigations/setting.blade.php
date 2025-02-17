<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Settings</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/global.css', 'resources/js/app.js', 'resources/js/navigation.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen flex justify-end grid-cols-6 grid-rows-8 gap-y-0 tablet:flex cp:grid-cols-12 tablet:gap-y-4 relative overflow-x-hidden">

    {{-- <x-create/> --}}

    {{-- NOTIFS --}}
    @if (session('roomDeleted'))
        <div id="success-alert"
            class="absolute flex justify-self-center top-0 alert z-50 transition-all duration-500 ease-in-out opacity-0">
            <div
                class="py-2 px-16 rounded-md  bg-bgcolor border-2 border-sgcolor shadow-sm transition-all duration-500 ease-in-out roomDeleted">
                <h1 class="text-green-500 text-lg text-center">
                    Room Deleted!
                </h1>
            </div>
        </div>
    @endif

    @if (session('roomCreated'))
        <div id="success-room"
            class="absolute flex justify-self-center top-0 right-0 left-0 alert z-50 transition-all duration-500 ease-in-out opacity-0">
            <div
                class="py-2 px-16 rounded-md bg-bgcolor border-2 border-sgcolor shadow-sm transition-all duration-500 ease-in-out roomCreated">
                <h1 class="text-green-500 text-xl text-center">
                    Room Created!
                </h1>
            </div>
        </div>
    @endif

    {{-- Navigation --}}
    <nav class="sidebar flex flex-col w-[17%] h-full bg-gray-100 absolute top-0 left-0 border-r border-sgline">
        <div class="flex h-[5.5rem] items-center justify-between pl-8 border-b border-sgline">
            <div class="flex items-center">
                <img class=" bg-bgcolor border-2 border-sgline px-1 rounded-full w-12 h-12 mr-2" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
            </div>
            <button id="menu-bar" class=" -mb-1 text-4xl pt-1 cp:text-3xl"><i class='bx bx-menu text-mainText hover:text-subtText mr-6'></i></button>
        </div>
        <div class=" py-4 flex flex-col items-center px-4">
            <a href="{{ route('teacher-home') }}"
                class=" w-full nav-link text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                <i class="bx bxs-home pr-4 text-xl "></i>
                <span class=" text-base ">Home</span>
            </a>
            <a href="{{ route('teacher-calendar') }}"
                class="w-full nav-link text-md  text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                <i class="bx bxs-calendar pr-4 text-xl"></i>
                <span class="text-base ">Calendar</span>
            </a>
            <a href="{{ route('teacher-setting') }}"
            class="w-full nav-link text-md  text-mainText bg-navHover hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
            <i class="bx bxs-cog pr-4 text-xl"></i>
            <span class="text-base ">Settings</span>
        </a>
        </div>
        <div class="flex-1 flex flex-col relative overflow-y-auto">
            <div class="sticky top-0 left-0 right-0 py-4 px-4 border-t border-sgline">
                <button
                    class="class-button w-full nav-link text-md flex items-center justify-between text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <div class=" flex items-center">
                        <i class="bx bxs-collection pr-4 text-xl"></i>
                        <span class="text-base ">Class</span>
                    </div>
                    <div class=" h-full flex items-center">
                        <i class="bx bx-chevron-right class pr-4 text-2xl"></i>
                    </div>
                </button>
            </div>
            <div class=" flex flex-col class-nav overflow-y-auto overflow-x-hidden hide-scrollbar px-4" id="scrollableDiv">
            </div>

            <div class=" py-4 border-t border-sgline px-4">
                <button
                    class="folder-button w-full nav-link text-md flex items-center justify-between text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <div class=" flex items-center">
                         <i class="bx bxs-archive pr-4 text-xl"></i>
                        <span class="text-base ">Folders</span>
                    </div>
                    <div class=" h-full flex items-center">
                        <i class="bx bx-chevron-right folder pr-4 text-2xl"></i>
                    </div>
                </button>
            </div>
            <div class="pl-4 px-4 border-b border-sgline flex flex-col folder-nav overflow-y-auto overflow-x-hidden hide-scrollbar relative" id="scrollableDiv">
            </div>

        </div>            
        <div class=" px-8 py-8">
            <button class=" h-full grid place-items-center">
                <i class="bx bx-question-mark text-lg text-subtText rounded-full border-[3px] border-sgline"></i>
            </button>
        </div>
    </nav>
    {{-- END OF NAVIGATION --}}

    @auth
        {{-- Body --}}
        <section id="hero"
            class="hero grid grid-cols-8 grid-rows-8 tablet:col-start-4 tablet:col-span-16 tablet:row-start-1 tablet:row-span-8 cp:col-start-2 cp:col-span-10 small-bp">
            {{-- <div class=" row-start-1 row-span-1 col-start-1 col-span-8 flex items-center tablet:items-start">
            <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
        </div> --}}
            <div class="row-start-1 row-span-8 col-start-1 col-span-8 overflow-y-auto overflow-scrolls z-10 bg-bgcolor">
                <div
                    class="w-full flex flex-col justify-center items-center h-[5.5rem] pr-8 pl-16 tablet:items-start sticky top-0 left-0 z-40 cp:gap-8 bg-bgcolor border-b border-sgline">
                    {{-- Header --}}
                    <header class=" w-full row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none z-40">
                        <div class="w-search">
                            <div class="show hidden justify-self-start">
                                <div class="flex gap-4 items-center pr-8">
                                    <button id="menu-assist" class=" text-4xl pt-1 cp:text-3xl"><i
                                            class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                                    <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">
                                        SAPT
                                    </h1>
                                </div>
                            </div>
                        </div>
                        {{-- SEARCH SECTION HERE! --}}
                        <div class=" h-full w-full row-start-1 row-span-1 flex items-center col-start-4 pr-16 z-40">
                            <div class=" flex items-center h-12 w-full rounded-full relative ">
                                <i class="bx bx-search absolute font-semibold text-subtText text-2xl ml-5"></i>
                                <label for="search"></label>
                                <input type="text" name="search" id="search"
                                    class="search-btn h-full w-full rounded-md text-base px-14 border border-sgline outline-subtText bg-gray-100 hover:bg-sgcolorSub hover:border-sgcolorSub placeholder" placeholder="Search class..." autocomplete="off">
                            </div>
                        </div>
                        <div class="flex gap-4 relative items-center">
                            <a href=" {{ route('folders.index') }} "
                                class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                                <i class='bx bx-folder-plus text-3xl font-weight-bolder'></i>
                            </a>
                            <a href=" {{ route('create') }} "
                                class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                                <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                            </a>
                            {{-- <button class="btn-pluss grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                            <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                        </button> --}}
                            <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText">
                                <h1 class=" text-xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                            </button>
                            <div
                                class="log-user bg-bgcolor hidden absolute left-0 ml-[4.7rem] mt-[7rem] rounded-md shadow-md z-10 border border-sgline">
                                <div class="flex flex-col">
                                    <a href="{{ route('logout') }}"
                                        class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                                </div>
                            </div>
                        </div>
                    </header>
                    {{-- <div class=" w-full py-8">
                    <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
                </div> --}}
                </div>
                <div class="flex">
                    <div class="flex-1 flex flex-col relative">
                        <div id="class-list" class="content-box flex flex-col items-center h-auto p-16 gap-8">
                            <div class=" w-[70%] p-6 border border-sgline rounded-md">
                                <h1 class=" text-4xl mb-5">Profile</h1>
                                <h1 class=" text-sm font-medium text-mainText mb-2">Profile Picture</h1>
                                <div class=" w-full flex items-center gap-2 mb-5">
                                    <div class=" text-3xl w-9 h-9 grid place-items-center rounded-full cp:text-2xl bg-mainText">
                                        <h1 class=" text-xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                                    </div>
                                    <a href="" class=" text-sm font-medium text-blue-500">Change</a>
                                </div>
                                <h1 class="text-sm font-medium text-mainText mb-1">Account settings</h1>
                                <h1 class="text-sm font-normal text-mainText mb-5">Change your password and security options, and access other Google services. <a href="https://myaccount.google.com/" target="_blank" class=" underline text-blue-500">Manage</a></h1>
                                <h1 class="text-sm font-medium text-mainText mb-1">Change name</h1>
                                <h1 class="text-sm font-normal text-mainText">To change your name, go to your <a href="" class="underline text-blue-500">account settings.</a></h1>
                            </div>
                            <div class=" w-[70%] p-6 border border-sgline rounded-md">
                                <h1 class=" text-4xl mb-5">Notifications</h1>
                                <h1 class="text-2xl font-normal text-mainText mb-1">SMS</h1>
                                <h1 class="text-sm font-normal text-mainText mb-5">These settings apply to the notifications you get by sms. <a href="" class=" underline text-blue-500">Learn more</a></h1>
                                <h1 class="text-2xl font-normal text-mainText mb-1">Email</h1>
                                <h1 class="text-sm font-normal text-mainText mb-5">These settings apply to the notifications you get by email. <a href="" class=" underline text-blue-500">Learn more</a></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endauth
    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
