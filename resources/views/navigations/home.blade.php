<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/global.css', 'resources/js/app.js','resources/js/navigation.js', 'resources/js/home/delButton.js', 'resources/js/search.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <section class=" w-screen h-screen flex justify-end grid-cols-6 grid-rows-8 gap-y-0 tablet:flex cp:grid-cols-12 tablet:gap-y-4 relative overflow-x-hidden">

        @if($studentName->school_name == null)
        {{-- Confirmation for FullName --}}
        <section class=" absolute top-0 right-0 left-0 bottom-0 flex items-center justify-center z-50  backdrop-blur-md">
            <div class="welcome-animation w-[32rem] bg-bgcolor border border-sgline flex flex-col p-8 rounded-md gap-4 relative">
                <div class="flex items-center gap-x-4 mb-4">
                    <div class="flex gap-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-mainText rounded-full">
                            <h1 class="text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                        </div>
                        <div>
                            <h1 class=""> {{ auth()->user()->username }} </h1>
                            <h3 class="text-sm text-subtText"> {{ auth()->user()->email }} </h3>
                        </div>
                    </div>
                </div>
                <h1 class=" text-2xl font-semibold">Welcome to SAPT!</h1>
                <div class="">
                    <div class="opacity-80">
                        <h1>Please provide your fullname to ensure accurate and private record-keeping. This protects your privacy and other students' confidentiality.</h1>
                        <h1>Thank you!</h1>
                    </div>
                    <div>
                        <form action="{{ route('full-name') }}" method="POST">
                        @csrf
                            {{-- Full Name of User --}}
                            <div class="flex flex-col gap-y-6 mt-4">
                                <label for="full_name" class="font-semibold"></label>
                                <input type="text" id="full_name" name="full_name" class=" px-3 py-2 border-2 border-subtText outline-mainText rounded-md @error('error') ring-2 ring-red-500 @enderror" placeholder="Fullname">
                            </div>
                            <div>
                                @error('full_name')
                                    <p class=" text-sm text-red-500">{{$message}}</p>
                                @enderror
                                <div class="flex flex-col gap-y-4 mt-2">
                                    <h1 class="text-base text-subtText mt-1">example: Surname, Firstname MI.</h1>
                                </div>
                            </div>
                            <div class="flex justify-end font-normal text-subtText mt-12">
                                <button class=" hover:text-mainText text-base font-medium flex items-center">Continue<i class=" bx bx-right-arrow-alt text-2xl pl-1"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        @else
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
                <a href="{{ route('home') }}"
                    class=" w-full nav-link text-mainText bg-navHover hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <i class="bx bxs-home pr-4 text-xl "></i>
                    <span class=" text-base ">Home</span>
                </a>
                <a href="{{ route('calendar') }}"
                    class="w-full nav-link text-md  text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <i class="bx bxs-calendar pr-4 text-xl"></i>
                    <span class="text-base ">Calendar</span>
                </a>
                <a href="{{ route('setting') }}"
                class="w-full nav-link text-md  text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
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
        <section id="hero" class="hero grid grid-cols-8 grid-rows-8 tablet:col-start-4 tablet:col-span-16 tablet:row-start-1 tablet:row-span-8 cp:col-start-2 cp:col-span-10 small-bp">
            <div class="row-start-1 row-span-8 col-start-1 col-span-8 overflow-y-auto overflow-scrolls z-10 bg-bgcolor flex flex-col">
                <div class="w-full flex flex-col justify-center items-center h-[5.5rem] pr-8 tablet:items-start sticky top-0 left-0 z-40 cp:gap-8 bg-bgcolor border-b border-sgline ">      
                    {{-- Header --}}
                    <header class=" w-full row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none z-40">
                        <div class="w-search">
                            <div class="show hidden justify-self-start">
                                <div class="flex gap-4 items-center pr-8">
                                    <button id="menu-assist" class=" text-4xl pt-1 cp:text-3xl">
                                        <i class='bx bx-menu text-mainText hover:text-subtText'></i>
                                    </button>
                                    <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
                                </div>
                            </div>
                        </div>
                        {{-- SEARCH SECTION HERE! --}}
                        <div class=" h-full w-full row-start-1 row-span-1 flex items-center col-start-4 px-16 z-40">
                            <div class=" flex items-center h-12 w-full rounded-full relative ">
                                <i class="bx bx-search absolute font-semibold text-subtText text-2xl ml-5"></i>
                                <label for="search"></label>
                                <input type="text" name="search" id="search" class="search-btn h-full w-full rounded-md text-base px-14 border-2 border-sgcolor outline-subtText bg-sgcolor hover:bg-sgcolorSub hover:border-sgcolorSub" placeholder="Search class..." autocomplete="off">
                            </div>
                        </div>
                        <div class="flex gap-4 relative items-center">
                            {{-- <a href=" {{ route('folders.index') }} " class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                                <i class='bx bx-folder-plus text-3xl font-weight-bolder'></i>
                            </a> --}}
                            <button class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                                <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                            </button>
                            <div class="choices bg-bgcolor hidden absolute left-0 -ml-[6.5rem] mt-36 border border-sgline rounded-md shadow-md ">
                                <div class="flex flex-col">
                                    <a href=" {{ route('track') }} " class="text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Track Record</a>
                                    <a href=" {{ route('create') }} " class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2">Create Record</a>
                                </div>
                            </div>

                            <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText hover:ring-8 hover:ring-gray-100">
                                <h1 class=" text-xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                            </button>
                            <div class="log-user bg-bgcolor hidden absolute left-0 ml-[.5rem] mt-[6.5rem] rounded-md shadow-md z-10 border border-sgline">
                                <div class="flex flex-col">
                                    <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                                </div>
                            </div>
                        </div>
                    </header>
                </div>
                <div class="flex flex-1">
                    <div class="flex-1 flex flex-col bg-bgcolor">
                        <div class="flex items-center gap-4 w-full py-8 px-16 sticky top-[5.5rem] left-0 z-10">
                            {{-- <div class=" flex items-center h-full w-full gap-4 bg-slate-400"> --}}
                                <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
                                <div class=" h-full border-r-2 border-mainText"></div>
                                <h2 class=" text-xl text-mainText">Home > </h2>
                            {{-- </div> --}}
                        </div>    
                        <div class=" flex-1 flex flex-col items-center justify-center mb-8 mx-8 rounded-md">
                            <h1 class=" text-5xl font-semibold text-subtText">No Rooms Yet!</h1>
                        </div>
                    </div>
                    <div class="w-[18%] flex mr-8 relative">
                        <div class=" w-full ">
                            <div class="sticky top-[5.5rem] right-0">
                                <div class="absolute top-0 left-0 right-0 h-[88vh] mb-8">
                                    <div class="flex flex-col h-full py-8">
                                        <div
                                        class="h-full bg-gray-100 flex justify-center items-center rounded-md border border-sgline">
                                        <div class=" h-full w-full overflow-y-auto relative">
                                            <div class=" w-full absolute top-0 right-0 left-0 bottom-0 flex flex-col ">
                                                <div class="flex-1 p-2.5">
                                                    <div class="custom-calendar-container">
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                                <div class=" w-full border-b border-sgline"></div>
                                                <div class="flex-1 flex flex-col p-2.5">
                                                    <div class="flex-1 flex gap-2">
                                                        <div class=" w-[50%] h-full grid place-items-center">
                                                            <h1>No. of Class</h1>
                                                        </div>
                                                        <div class=" w-[50%] h-full grid place-items-center">
                                                            <div class=" w-[90%] h-[70%] bg-mainText text-bgcolor grid place-items-center rounded-md border border-sgline">
                                                                <h1 class="text-xl">0</h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 flex gap-2">
                                                        <div class=" w-[50%] h-full grid place-items-center">
                                                            <h1>No. of Folder</h1>
                                                        </div>
                                                        <div class=" w-[50%] h-full grid place-items-center">
                                                            <div class=" w-[90%] h-[70%] bg-mainText text-bgcolor grid place-items-center rounded-md border border-sgline">
                                                                <h1 class="text-xl">0</h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endauth

    </section>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
</body>

</html>