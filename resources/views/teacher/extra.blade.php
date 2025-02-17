<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js','resources/js/navigation.js', 'resources/js/home/delButton.js', 'resources/js/search.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* .w-full-screen {
            width: 100vw;
            transition: width 0.5s ease-in-out;

        }
        .w-half-screen {
            width: 50vw;
            transition: width 0.5s ease-in-out;

        } */
         .hero {
            transition: width .3s ease;
            width: 85%;
         }
         .w-search {
            width: 0%;
            transition: width .3s ease;
         }
         /* .expand {
            width: 100%;
            transition: width .7s ease-in-out;
         } */
         /* .full-width {
            border: 2px solid red;
        }  */
        /* .show {
            display: block;
        } */
    </style>
</head>

<body>
    <section class=" w-screen h-screen flex justify-end grid-cols-6 grid-rows-8 gap-y-0 tablet:flex cp:grid-cols-12 tablet:gap-y-4 relative overflow-x-hidden">

        {{-- NOTIFS --}}
        @if(session('roomDeleted'))
        <div id="success-alert" class="absolute flex justify-self-center top-0 alert z-50 transition-all duration-500 ease-in-out opacity-0">
            <div class="py-2 px-16 rounded-md  bg-bgcolor border-2 border-sgcolor shadow-sm transition-all duration-500 ease-in-out roomDeleted">
                <h1 class="text-green-500 text-lg text-center">
                    Room Deleted!
                </h1>
            </div>
        </div>
        @endif
        
        @if(session('roomCreated'))
        <div id="success-room" class="absolute flex justify-self-center top-0 alert z-50 transition-all duration-500 ease-in-out opacity-0">
            <div class="py-2 px-16 rounded-md bg-bgcolor border-2 border-sgcolor shadow-sm transition-all duration-500 ease-in-out roomCreated">
                <h1 class="text-green-500 text-xl text-center">
                    Room Created!
                </h1>
            </div>
        </div>
        @endif

        {{-- Navigation --}}
        <nav class="sidebar w-[15%] h-full bg-sgcolor absolute top-0 left-0">
            <div class="flex gap-4 items-center pl-8 py-3 mb-4">
                <button id="menu-bar" class=" text-4xl pt-1 cp:text-3xl"><i
                        class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
            </div>
            <a href="{{ route('teacher-home') }}"
                class=" w-full nav-link text-md text-mainText hover:bg-hoverColor hover:text-mainText rounded-r-full pl-8">
                <i class="bx bxs-home pr-4 text-xl icon"></i>
                <span class="text-base ">Home</span>
            </a>
            <a href="{{ route('teacher-setting') }}"
                class="w-full nav-link text-md  text-subtText hover:bg-hoverColor hover:text-mainText rounded-r-full pl-8">
                <i class="bx bxs-cog pr-4 text-xl icon"></i>
                <span class="text-base ">Settings</span>
            </a>
        </nav>

        @auth
        {{-- Body --}}
        <section id="hero" class="hero grid grid-cols-8 grid-rows-8 tablet:col-start-4 tablet:col-span-16 tablet:row-start-1 tablet:row-span-8 cp:col-start-2 cp:col-span-10 small-bp">
            {{-- <div class=" row-start-1 row-span-1 col-start-1 col-span-8 flex items-center tablet:items-start">
                <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
            </div> --}}
            <div class="row-start-1 row-span-8 col-start-1 col-span-8 pl-8 overflow-y-auto overflow-scrolls z-10 bg-bgcolor">
                <div class="w-full flex flex-col items-center h-[9%] pt-2 pl-8 tablet:items-start sticky top-0 left-0 z-40 cp:gap-8 bg-bgcolor">      
                    {{-- Header --}}
                    <header class=" w-full row-start-1 row-span-1 flex justify-between items-center pr-8 shadow tablet:shadow-none z-40 ">
                        <div class="w-search">
                            <div class="show hidden justify-self-start">
                                <div class="flex gap-4 items-center pr-8">
                                    <button id="menu-assist" class=" text-4xl pt-1 cp:text-3xl"><i
                                            class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                                    <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
                                </div>
                            </div>
                        </div>
                        {{-- SEARCH SECTION HERE! --}}
                        <div class=" h-full w-full row-start-1 row-span-1 flex items-center col-start-4 pr-5 z-40">
                            <div class=" flex items-center h-12 w-full rounded-full relative ">
                                <i class="bx bx-search absolute font-semibold text-subtText text-2xl ml-5"></i>
                                <label for="search"></label>
                                <input type="text" name="search" id="search" class="search-btn h-full w-full rounded-md text-base px-14 border-2 border-sgcolor outline-subtText bg-sgcolor hover:bg-sgcolorSub hover:border-sgcolorSub" placeholder="Search classes..." autocomplete="off">
                            </div>
                        </div>
                        <div class="flex gap-4 relative">
                            <a href=" {{ route('create') }} " class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                                <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                            </a>
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
                    {{-- <div class=" w-full py-8">
                        <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
                    </div> --}}
                </div>
                <div class="flex">
                    <div class="flex-1 flex flex-col mr-8 relative">
                        <div class=" w-full py-8 pl-8 sticky top-[9%] left-0 bg-bgcolor z-10">
                            <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
                        </div>    
                        <div id="class-list" class="content-box h-auto grid grid-cols-adjust px-8 tablet:pb-8 tablet:grid-cols-2 md:grid-cols-2 3xl:grid-cols-4 gap-8">
                            {{-- Class Box --}}
                            @if ($rooms->isEmpty())
                                <h1>No Data yet!</h1>
                            @else              
                                @foreach ($rooms as $room) 
                                <div class="class-item h-[100%] bg-mainText rounded-md p-5 shadow-md relative">
                                    <div class="flex flex-col gap-y-1 select-none">
                                        <h3 class="text-lg text-bgcolor">{{ $room->section }}</h3>
                                        <h1 class="text-3xl text-bgcolor">{{ $room->class_name }}</h1>
                                    </div>
                                    @php
                                        $subject = strlen($room->subject) > 19 ? substr($room->subject, 0, 23) . '...' : $room->subject;
                                    @endphp                       
                                    <h5 class="text-bgcolor pt-8 cp:pt-[4.5rem]">{{ $subject}}</h5>
                                    <button class="absolute top-0 right-0 mt-5 mr-4 three-dot">
                                        <i class="bx bx-dots-vertical-rounded text-2xl text-bgcolor"></i>
                                    </button>
                                    <div class="hidden delButton absolute top-[12%] right-[14%] bg-bgcolor py-2 rounded-sm hover:text-red-600">
                                        <form action="{{ route('teacherRoom.delete', $room->id) }}" method="post"> 
                                            @csrf
                                            @method('DELETE')             
                                            <button type="submit" class="px-6" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                        </form>             
                                    </div>
                                    <a href="{{ route('teacher.room', $room->id) }}" class="absolute right-0 bottom-0 mb-3 mr-5">
                                        <i class='bx bxs-folder-open text-3xl text-bgcolor'></i>
                                    </a>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="w-[17%] flex mr-8 relative">
                        <div class=" w-full ">
                            <div class="sticky top-[9%] right-0 bg-slate-100 h-[30%]">HEhe</div>
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
                    successAlert.classList.add('top-[12%]', 'opacity-100');
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
                    successRoom.classList.add('top-[12%]', 'opacity-100');
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