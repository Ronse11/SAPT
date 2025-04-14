<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    {{-- <link rel="icon" href="{{ asset('images/saptlogo.svg') }}"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css' rel='stylesheet' />
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/global.css', 'resources/js/app.js', 'resources/js/navigation.js', 'resources/js/home/delButton.js', 'resources/js/search.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section
        class=" w-screen h-screen flex justify-end grid-cols-6 grid-rows-8 gap-y-0 tablet:flex cp:grid-cols-12 tablet:gap-y-4 relative overflow-x-hidden">

        {{-- <x-create/> --}}

        {{-- NOTIFS --}}
        @if (session('roomDeleted'))
            <div id="success-alert"
                class="absolute flex justify-self-center top-0 right-0 left-0 alert z-50 transition-all duration-500 ease-in-out opacity-0">
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
                    <img class=" w-8 h-8 mb-3 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                    <h1 class=" text-xl font-normal text-black select-none tracking-widest cp:text-3xl">APT</h1>
                </div>
                <div class="relative group">
                    <button id="menu-bar" class=" -mb-1 text-4xl pt-1 cp:text-3xl"><i class='bx bx-menu text-mainText hover:text-subtText mr-6'></i></button>
                    <div class="absolute top-full -left-[50%] transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                        <div class="relative w-[8rem] bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                            <h1>Close sidebar</h1>
                            <div class="absolute -top-1 left-[82%] transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                        </div>  
                    </div>
                </div>  
            </div>
            <div class=" py-4 flex flex-col items-center px-4">
                <a href="{{ route('teacher-home') }}"
                    class=" w-full nav-link text-mainText bg-navHover hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <i class="bx bxs-home pr-4 text-xl "></i>
                    <span class=" text-base ">Home</span>
                </a>
                <a href="{{ route('teacher-calendar') }}"
                    class="w-full nav-link text-md  text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <i class="bx bxs-calendar pr-4 text-xl"></i>
                    <span class="text-base ">Calendar</span>
                </a>
                <a href="{{ route('teacher-setting') }}"
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
                    @if ($data->allRooms->isEmpty())
                        <div></div>
                    @else
                    @foreach ($data->allRooms as $room)
                        <a href="{{ route('teacher.room', ['id' => \App\Http\Controllers\HelperFunctions::base64_url_encode($room->id)]) }}"
                            class="w-full nav-link text-md text-mainText hover:bg-navHover rounded-md px-4">
                            <i class="bx bxs-rectangle pr-4 text-xl"></i>
                            <span class="text-base truncate whitespace-nowrap">{{ $room->class_name }}</span>
                        </a>
                    @endforeach
                    @endif
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
                    @if ($data->folders->isEmpty())
                        <div></div>
                    @else
                    @foreach ($data->folders as $folder)
                        <a href="{{ route('teacher-folder', $folder->id) }}"
                            class="w-full nav-link text-md text-mainText hover:bg-navHover rounded-md px-4">
                            <i class="bx bxs-folder pr-4 text-xl"></i>
                            <span class="text-base truncate whitespace-nowrap">{{ $folder->folder_name }}</span>
                        </a>
                    @endforeach
                    @endif
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
                {{-- <div class=" row-start-1 row-span-1 col-start-1 col-span-8 flex items-center tablet:items-start">
                <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
            </div> --}}
                <div class="row-start-1 row-span-8 col-start-1 col-span-8 overflow-y-auto overflow-scrolls z-10 bg-bgcolor">
                    <div class="header-holder w-full flex flex-col justify-center items-center h-[5.5rem] pr-8 pl-16 tablet:items-start sticky top-0 left-0 z-40 cp:gap-8 bg-bgcolor border-b border-sgline">
                        {{-- Header --}}
                        <header class=" w-full flex justify-between items-center z-40">
                            <div class="w-search">
                                <div class="show hidden justify-self-start w-full">
                                    <div class="flex w-full items-center justify-between">
                                        <div class="flex-1 flex items-center justify-between">
                                            <div class=" flex items-center">
                                                <img class=" w-8 h-8 mb-3 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                                                <h1 class=" text-xl font-normal text-black select-none tracking-widest cp:text-3xl">APT</h1>
                                            </div>
                                            <div class="relative group">
                                                <button id="menu-assist" class="  -mb-1 text-4xl pt-1 cp:text-3xl"><i class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                                                    <div class="relative w-[8rem] bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                                        <h1>Open sidebar</h1>
                                                        <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                                                    </div>  
                                                </div>
                                            </div>  
                                        </div>
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
                                <div class="relative group">
                                    <a href=" {{ route('folders.index') }} "
                                        class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-gray-100 hover:border-2 hover:border-sgline">
                                        <i class='bx bx-folder-plus text-3xl font-weight-bolder'></i>
                                    </a>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-3.5 hidden group-hover:flex flex-col items-center">
                                        <div class="relative w-[6.7rem] bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                            <h1>New folder</h1>
                                            <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                                        </div>  
                                    </div>
                                </div>  
                                <div class="relative group">
                                    <a href=" {{ route('create') }} "
                                        class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-gray-100 hover:border-2 hover:border-sgline">
                                        <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                                    </a>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-3.5 hidden group-hover:flex flex-col items-center">
                                        <div class="relative w-[6.5rem] bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                            <h1>New room</h1>
                                            <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                                        </div>  
                                    </div>
                                </div>  
                                <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText hover:ring-8 hover:ring-gray-100">
                                    <h1 class=" text-xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                                </button>
                                <div class="log-user bg-bgcolor hidden absolute left-0 ml-[4.7rem] mt-[7rem] rounded-md shadow-md z-10 border border-sgline">
                                    <div class="flex flex-col">
                                        <a href="{{ route('logout') }}"
                                            class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                                    </div>
                                </div> 
                            </div>
                        </header>
                    </div>
                    <div class="flex">
                        <div class="flex-1 flex flex-col relative">
                            <div class="adjust-pleft flex items-center gap-4 w-full pt-8 pb-8 pl-16 sticky top-[5.5rem] left-0 bg-bgcolor z-10">
                                <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
                                <div class=" h-full border-r-2 border-mainText"></div>
                                <h2 class=" text-xl text-mainText">Home > Teacher-Room</h2>
                            </div>
                            <div id="class-list" class="content-box h-auto grid grid-cols-adjust px-16 mt-8 mb-8 tablet:grid-cols-2 md:grid-cols-2 3xl:grid-cols-3 gap-8">
                                {{-- Class Box --}}
                                @if ($data->rooms->isEmpty() && $data->folders->isEmpty())
                                    <h1 >No Data yet!</h1>
                                @else
                                    @foreach ($data->roomsWithUrls as $room)
                                        <div class="class-item h-[14rem] flex rounded-md shadow-md relative hover:-translate-y-1 transition hover:shadow-lg border border-mainText">
                                            @php
                                                $className = strlen($room->class_name) > 15 ? substr($room->class_name, 0, 15) . '...' : $room->class_name;
                                                $subject = strlen($room->subject) > 28 ? substr($room->subject, 0, 28) . '...' : $room->subject;
                                            @endphp
                                            <div class=" flex-1 h-full rounded-l-md overflow-hidden">
                                                <div class=" w-full h-full flex flex-col justify-between bg-mainBg file-vertical shadow-sm relative overflow-visible p-5">
                                                    <div class=" w-full">
                                                        <div class=" w-full mb-1">
                                                            <h3 class=" text-lg text-mainText opacity-80">{{ $room->section }}</h3>
                                                        </div>
                                                        <div class=" w-full">
                                                            <h3 class=" text-4xl text-mainText">{{ $className }}</h3>
                                                        </div>
                                                    </div>
                                                    <div class=" w-full">
                                                        <h3 class=" text-lg text-mainText opacity-80">{{ $subject }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" w-[15%] h-full flex flex-col justify-between bg-bgcolor rounded-r-md border-l border-mainText py-5">
                                                <div class=" w-full grid items-center">
                                                    <div class="relative group grid place-content-center">
                                                        <button class="three-dot">
                                                            <i class="bx bx-dots-vertical-rounded text-2xl text-mainText"></i>
                                                        </button>
                                                        <div class="absolute top-1/2 right-full transform -translate-y-1/2 translate-x-2 hidden group-hover:flex flex-col items-center">
                                                            <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                                                <h1>Options</h1>
                                                                <div class="absolute top-1/2 left-full transform -translate-y-1/2 -translate-x-1 w-0 h-0 border-t-4 border-l-4 border-b-4 border-transparent border-l-mainText ml-1"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="hidden delButton absolute top-[6%] right-[12%] bg-bgcolor py-2 px-2 rounded-md border border-sgline">
                                                        <div class=" flex flex-col text-mainTexts">
                                                            <a href="" class=" w-full px-8 py-2 text-center hover:bg-navHover rounded-md">Move</a>
                                                            <form action="{{ route('teacherRoom.delete', $room->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="px-8 py-2 w-full hover:bg-navHover rounded-md"
                                                                    onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-full flex justify-center -mb-2">
                                                    <div class="relative group grid place-content-center">
                                                        <a href="{{ $room->encoded_url }}" class="">
                                                            <i class='bx bxs-folder-open text-4xl text-mainText'></i>
                                                        </a>
                                                        <div class="absolute top-1/2 right-full transform mr-4 -translate-y-1/2 translate-x-2 hidden group-hover:flex flex-col items-center">
                                                            <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                                                <h1>Open</h1>
                                                                <div class="absolute top-1/2 left-full transform -translate-y-1/2 -translate-x-1 w-0 h-0 border-t-4 border-l-4 border-b-4 border-transparent border-l-mainText ml-1"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach

                                    @foreach ($data->folders as $folder)
                                        <div class="class-item h-[14rem] flex justify-between shadow-md rounded-md relative hover:-translate-y-1 transition hover:shadow-lg border border-mainText">
                                            @php
                                                $folderName = strlen($folder->folder_name) > 14 ? substr($folder->folder_name, 0, 14) . '...' : $folder->folder_name;
                                            @endphp
                                            <div class="flex-1 bg-mainText rounded-l-md pt-[21px]">
                                                <div class="folder-vertical shadow-lg relative w-full h-full bg-gray-100 rounded-l-[5px] overflow-visible">
                                                    <div class=" absolute top-0 right-0 left-0 bottom-0 px-5 pb-5 pt-6 z-[1]">
                                                        <h1 class=" text-4xl text-mainText">{{ $folderName }}</h1>
                                                    </div>
                                                    <div class="folder-tab absolute top-[-21px] left-0 w-[150px] h-[30px] bg-gray-300"></div>
                                                </div>
                                            </div>
                                            <div class=" w-[15%] h-full flex flex-col justify-between bg-bgcolor rounded-r-md border-l border-mainText py-5">
                                                <div class=" w-full grid items-center">
                                                    <div class="relative group grid place-content-center">
                                                        <button class="three-dot">
                                                            <i class="bx bx-dots-vertical-rounded text-2xl text-mainText"></i>
                                                        </button>
                                                        <div class="absolute top-1/2 right-full transform -translate-y-1/2 translate-x-2 hidden group-hover:flex flex-col items-center">
                                                            <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                                                <h1>Options</h1>
                                                                <div class="absolute top-1/2 left-full transform -translate-y-1/2 -translate-x-1 w-0 h-0 border-t-4 border-l-4 border-b-4 border-transparent border-l-mainText ml-1"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hidden delButton absolute top-[6%] right-[12%] bg-bgcolor py-2 px-2 rounded-md border border-sgline z-40">
                                                    <div class=" flex flex-col text-mainTexts">
                                                        <a href="" class=" w-full px-8 py-2 text-center hover:bg-navHover rounded-md">Move</a>
                                                        <form action="{{ route('teacherFolder.delete', $folder->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-8 py-2 w-full hover:bg-navHover rounded-md"
                                                                onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="w-full flex justify-center -mb-2">
                                                    <a href="{{ route('teacher-folder', $folder->id) }}" class="">
                                                        <i class='bx bxs-folder-open text-4xl text-mainText'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif


                            </div>
                        </div>
                        <div class="w-[18%] flex mr-8 relative">
                            <div class=" w-full ">
                                <div class="sticky top-[5.5rem] right-0">
                                    <div class="absolute top-0 left-0 right-0 h-[88vh] mb-8">
                                        <div class="flex flex-col h-full pt-8 pb-8">
                                            {{-- <div class="pt-8 pb-16">
                                                <h1 class="text-4xl text-mainText font-semibold">Folders</h1>
                                            </div> --}}
                                            <div class="h-full bg-gray-100 flex justify-center items-center rounded-md border border-sgline">
                                                <div class=" h-full w-full overflow-y-auto relative">
                                                    <div class=" w-full absolute top-0 right-0 left-0 bottom-0 flex flex-col ">
                                                        {{-- @if ($folders->isEmpty())
                                                            <div class=" h-full grid place-items-center">
                                                                <h1 class="text-xl font-semibold text-subtText">No Folders
                                                                    Yet!</h1>
                                                            </div>
                                                        @else
                                                            @foreach ($folders as $folder)
                                                                <div class=" hover:bg-navHover rounded-sm">
                                                                    <a href="{{ route('teacher-folder', $folder->id) }}"
                                                                        class="flex items-center">
                                                                        <i
                                                                            class='bx bxs-folder text-3xl text-mainTexts pl-2.5'></i>
                                                                        <h1
                                                                            class=" py-2.5 px-2.5 text-base text-mainTexts">
                                                                            {{ $folder->folder_name }}</h1>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @endif --}}
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
                                                                        <h1 class="text-xl">{{ $data->classCount }}</h1>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-1 flex gap-2">
                                                                <div class=" w-[50%] h-full grid place-items-center">
                                                                    <h1>No. of Folder</h1>
                                                                </div>
                                                                <div class=" w-[50%] h-full grid place-items-center">
                                                                    <div class=" w-[90%] h-[70%] bg-mainText text-bgcolor grid place-items-center rounded-md border border-sgline">
                                                                        <h1 class="text-xl">{{ $data->folderCount }}</h1>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');

            if (successAlert) {
                const alertDelete = successAlert.querySelector('.roomDeleted');

                setTimeout(() => {
                    successAlert.classList.add('top-[10%]', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    successAlert.classList.remove('opacity-100');
                }, 1500);

                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 2000);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const successRoom = document.getElementById('success-room');

            if (successRoom) {
                const alertRoom = successRoom.querySelector('.roomCreated');

                setTimeout(() => {
                    successRoom.classList.add('top-[10%]', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    successRoom.classList.remove('opacity-100');
                }, 1500);

                setTimeout(() => {
                    successRoom.style.display = 'none';
                }, 2000);
            }
        });
    </script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
