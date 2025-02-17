<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/navigation.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen grid grid-cols-6 grid-rows-8 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 tablet:gap-y-4 bg-bgcolor">

        {{-- Header --}}
        <header
            class=" w-screen row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
            <div class="flex gap-4 items-center">
                <button id="menu-bar" class=" text-4xl pt-1 cp:text-3xl"><i
                        class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
            </div>
            <div class="flex gap-4 relative">
                <button class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                    <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                </button>
                <div class="choices bg-bgcolor hidden absolute left-0 -ml-[9rem] mt-9 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <a href=" {{ route('track') }} " class="text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Track Record</a>
                        <a href=" {{ route('create') }} " class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2">Create Record</a>
                    </div>
                </div>
                <button class="btn-user text-3xl w-11 h-11 rounded-full cp:text-2xl bg-mainText">
                    <h1 class=" text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                </button>
                <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Navigation --}}
        <nav class="sidebar row-start-2 row-span-7 col-start-1 col-span-3 py-[1px] transition ease-in-out delay-200">
            <a href="{{ route('home') }}"
                class=" w-full nav-link text-md text-subtText hover:bg-hoverColor hover:text-mainText rounded-r-full pl-16">
                <i class="bx bxs-home pr-4 pl-1 text-xl icon"></i>
                <span class="text ">Home</span>
            </a>
            <a href="{{ route('student-room') }}"
                class="nav-link text-md  text-subtText hover:bg-hoverColor hover:text-mainText rounded-r-full pl-16">
                <i class="bx bxs-collection pr-4 pl-1 text-xl icon"></i>
                <span class="text ">Student Room</span>
            </a>
            <a href="{{ route('teacher-room') }}"
                class="nav-link text-md  text-mainText hover:bg-hoverColor hover:text-mainText rounded-r-full pl-16">
                <i class="bx bxs-collection pr-4 pl-1 text-xl icon"></i>
                <span class="text ">Teacher Room</span>
            </a>
            <a href="{{ route('setting') }}"
                class="nav-link text-md  text-subtText hover:bg-hoverColor hover:text-mainText rounded-r-full pl-16">
                <i class="bx bxs-cog pr-4 pl-1 text-xl icon"></i>
                <span class="text ">Settings</span>
            </a>
        </nav>

        @auth
        {{-- Body --}}
        <section id="hero" class=" row-start-2 row-span-7 col-start-1 col-span-6 px-4 grid grid-cols-8 grid-rows-8 tablet:col-start-4 tablet:col-span-11 tablet:row-start-2 tablet:row-span-7 cp:col-start-2 cp:col-span-10 small-bp">
            <div class=" row-start-1 row-span-1 col-start-1 col-span-8 flex items-center tablet:items-start">
                <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
            </div>
            <div class="row-start-2 row-span-7 col-start-1 col-span-8 overflow-y-auto overflow-scrolls">
                <div class="content-box h-auto grid grid-cols-adjust lg:grid-cols-3 3xl:grid-cols-4 gap-8">
                    {{-- Class Box --}}
                    @if ($rooms->isEmpty() && $student_room->isEmpty())
                        <h1>No Data yet!</h1>
                    @else              
                        @foreach ($rooms as $room)              
                        <div class=" bg-mainText rounded-md p-5 shadow-md relative">
                            <div class="flex flex-col gap-y-1 select-none">
                                <h3 class=" text-lg text-bgcolor"> {{ $room->section }} </h3>
                                <h1 class=" text-3xl text-bgcolor"> {{ $room->class_name }} </h1>
                            </div>                       
                            <h5 class=" text-bgcolor pt-8 cp:pt-12"> {{ $room->subject }} </h5>
                            <button class="absolute top-0 right-0 mt-5 mr-4">
                                <i class="bx bx-dots-vertical-rounded text-2xl text-bgcolor"></i>
                            </button>
                            <a href=" {{ route('teacher.room', $room->id) }} " class="absolute right-0 bottom-0 mb-3 mr-5">
                                <i class='bx bxs-folder-open text-3xl text-bgcolor'></i>
                            </a>
                        </div>
                        {{-- <a href=" {{ route('teacher.room', $room->id) }} " class="absolute right-0 bottom-0 mb-3 mr-5"> --}}
                        @endforeach

                        @foreach ($student_room as $room)              
                        <div class=" bg-mainText rounded-md p-5 shadow-md relative">
                            <div class="flex flex-col gap-y-1 select-none">
                                <h3 class=" text-lg text-bgcolor"> {{ $room->section }} </h3>
                                <h1 class=" text-3xl text-bgcolor"> {{ $room->class_name }} </h1>
                            </div>
                            <h5 class=" text-bgcolor pt-8 cp:pt-12"> {{ $room->teacher_name }} </h5>
                            <button class="absolute top-0 right-0 mt-5 mr-4">
                                <i class="bx bx-dots-vertical-rounded text-2xl text-bgcolor"></i>
                            </button>
                            <a href=" {{ route('student.room', $room->room_id) }} " class="absolute right-0 bottom-0 mb-3 mr-5">
                                <i class='bx bxs-folder-open text-3xl text-bgcolor'></i>
                            </a>
                        </div>
                        @endforeach
                    @endif
                    
                </div>
            </div>
        </section>
        @endauth

    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>