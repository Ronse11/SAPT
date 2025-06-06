<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Track Room</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/saptfavicon.svg')  }}">

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/global.css', 'resources/css/table.css', 'resources/js/nav/nav.js', 'resources/js/users.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-full h-screen flex flex-col">
        {{-- Navigation --}}
        <nav class="sidebar flex flex-col w-[17%] h-full bg-gray-100 -translate-x-[20rem] transition absolute top-0 left-0 border-r border-sgline z-20">
            <div class="flex h-[5.5rem] items-center justify-between pl-8 border-b border-sgline">
                <div class="flex items-center">
                    <img class=" w-8 h-8 mb-3 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                    <h1 class=" text-xl font-normal text-black select-none tracking-widest cp:text-3xl">APT</h1>
                </div>
                <div class="relative group">
                    <button id="menu-bar" class="menu-bar-nav -mb-1 text-4xl pt-1 cp:text-3xl"><i class='bx bx-menu text-mainText hover:text-subtText mr-6'></i></button>
                    <div class="absolute top-full left-[28%] transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                        <div class="relative w-[8rem] bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                            <h1>Close sidebar</h1>
                            <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                        </div>  
                    </div>
                </div>  
            </div>
            <div class=" py-4 flex flex-col items-center px-4">
                <a href="{{ route('student-home') }}"
                    class=" w-full nav-link text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <i class="bx bxs-home pr-4 text-xl "></i>
                    <span class=" text-base ">Home</span>
                </a>
                <a href="{{ route('student-setting') }}"
                    class="w-full nav-link text-md  text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                    <i class="bx bxs-calendar pr-4 text-xl"></i>
                    <span class="text-base ">Calendar</span>
                </a>
                <a href="{{ route('student-setting') }}"
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
                    @if ($allRooms->isEmpty())
                        <div></div>
                    @else
                    @foreach ($allRooms as $room)
                        <a href="{{ route('student.room', ['id' => \App\Http\Controllers\HelperFunctions::base64_url_encode($room->room_id)]) }}"
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
                    @if ($folders->isEmpty())
                        <div></div>
                    @else
                    @foreach ($folders as $folder)
                        <a href="{{ route('student-folder', $folder->id) }}"
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

        {{-- Body --}}
        <section class="teacher-choices flex-1 flex flex-col w-full bg-bgcolor">
                {{-- Header --}}
                <header class=" w-full h-[5.5rem] row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none cp:pr-8 z-10 border-b border-sgline">
                    <div class="flex h-[5.5rem] w-[17%] items-center justify-between pl-8 ">
                        <div class="flex items-center">
                            <img class=" w-8 h-8 mb-3 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                            <h1 class=" text-xl font-normal text-black select-none tracking-widest cp:text-3xl">APT</h1>
                        </div>
                        <div class="relative group">
                            <button id="menu-bar" class="menu-bar -mb-1 text-4xl pt-1 cp:text-3xl"><i class='bx bx-menu text-mainText hover:text-subtText mr-6'></i></button>
                            <div class="absolute top-full left-[28%] transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                                <div class="relative w-[8rem] bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                    <h1>Open sidebar</h1>
                                    <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                                </div>  
                            </div>
                        </div>  
                    </div>
                    <div class="flex items-center gap-4 relative">
                        <a href="{{ route('room-setting', $record->id) }}" class="grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:text-subtText">
                            <i class='bx bxs-cog text-3xl font-weight-bolder'></i>
                        </a>
                        <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText hover:ring-8 hover:ring-gray-100">
                            <h1 class=" text-xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                        </button>
                        <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                            <div class="flex flex-col">
                                <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                            </div>
                        </div>
                    </div>
                </header>
                {{-- End Of Header --}}
                <div class=" flex-1 flex flex-col cp:items-start tablet:mx-16 tablet:my-12 gap-12">
                    <div class="w-[80%] self-center text-center flex items-center justify-center relative">
                        <h1 class=" text-4xl font-semibold text-mainText">CLASS RECORD</h1>
                    </div>
                    <div class=" flex w-[70%] self-center gap-8 h-full">
                        <a href=" {{ route('studentRecord.room', [ 'id' => $encodedId, 'key' => 'main-table']) }} " class=" bg-gray-100 flex-1 h-full rounded-lg flex items-center justify-center relative hover:-translate-y-2 transition hover:shadow-xl cp:gap-4 border border-sgline">
                            <div class=" ">
                                <i class="bx bxs-book-alt text-5xl"></i>              
                            </div>
                            <div class="">
                                <h1 class="text-4xl font-semibold">Record</h1>
                            </div>
                        </a>
                        <a href=" {{ route('studentAttendance.room', [ 'id' => $encodedId, 'key' => 'attendance-table']) }} " class=" bg-gray-100 flex-1 h-full rounded-lg flex items-center justify-center relative hover:-translate-y-2 transition hover:shadow-xl cp:gap-4 border border-sgline">
                            <div class=" ">
                                <i class="bx bxs-bar-chart-alt-2 text-5xl "></i>              
                            </div>
                            <div class="">
                                <h1 class="text-4xl font-semibold">Attendance</h1>
                            </div>
                        </a>
                    </div>
                    <div class="flex justify-between w-[70%] self-center">

                    </div>
                </div>
        </section>

    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
</body>

</html>
