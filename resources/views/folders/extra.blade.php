
@auth
{{-- Body --}}
<section id="hero"
        class="hero grid grid-cols-8 grid-rows-8 tablet:col-start-4 tablet:col-span-16 tablet:row-start-1 tablet:row-span-8 cp:col-start-2 cp:col-span-10 small-bp">
        <div class="row-start-1 row-span-8 col-start-1 col-span-8 overflow-y-auto overflow-scrolls z-10 bg-bgcolor">
            <div class="header-holder w-full flex flex-col justify-center items-center h-[12%] pr-8 pl-16 tablet:items-start sticky top-0 left-0 z-40 cp:gap-8 bg-bgcolor border-b border-sgline">
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
                            class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-gray-100 hover:border-2 hover:border-sgline">
                            <i class='bx bx-folder-plus text-3xl font-weight-bolder'></i>
                        </a>
                        <a href=" {{ route('trackRoomFolder', $folder_id) }} "
                            class="btn-plus grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:bg-gray-100 hover:border-2 hover:border-sgline">
                            <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                        </a>
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
            </div>
            <div class="flex">
                <div class="flex-1 flex flex-col relative">
                    <div class="flex items-center gap-4 w-full pt-8 pb-8 pl-16 sticky top-[12%] left-0 bg-bgcolor z-10">
                        <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
                        <div class=" h-full border-r-2 border-mainText"></div>
                        <h2 class=" text-xl text-mainText">Home > Student-Room</h2>
                    </div>
                    <div id="class-list" class="content-box h-auto grid grid-cols-adjust px-16 mt-8 mb-8 tablet:grid-cols-2 md:grid-cols-2 3xl:grid-cols-3 gap-8">
                        {{-- Class Box --}}
                        @if ($rooms->isEmpty())
                            <h1>No Data yet!</h1>
                        @else
                            @foreach ($rooms as $room)
                                <div class="class-item h-[14rem] flex bg-mainText rounded-md shadow-md relative hover:-translate-y-1 transition hover:shadow-lg">
                                    @php
                                        $className = strlen($room->class_name) > 15 ? substr($room->class_name, 0, 15) . '...' : $room->class_name;
                                        $subject = strlen($room->subject) > 28 ? substr($room->subject, 0, 28) . '...' : $room->subject;
                                    @endphp
                                    <div class=" flex-1 h-full border-2 border-mainText rounded-md">
                                        <div class=" w-full h-full flex flex-col justify-between bg-gray-100 file-vertical shadow-sm relative overflow-visible rounded-l-md p-5">
                                            <div class=" w-full">
                                                <div class=" w-full mb-1">
                                                    <h3 class=" text-lg text-mainText">{{ $room->section }}</h3>
                                                </div>
                                                <div class=" w-full">
                                                    <h3 class=" text-4xl text-mainText">{{ $className }}</h3>
                                                </div>
                                            </div>
                                            <div class=" w-full">
                                                <h3 class=" text-lg text-mainText">{{ $subject }}</h3>
                                            </div>
                                            {{-- <div class="file-corner-fold bg-gray-300"></div> --}}
                                        </div>
                                    </div>
                                    <div class=" w-[15%] h-full flex flex-col justify-between bg-bgcolor rounded-r-md border-y-2 border-r-2 border-mainText py-5">
                                        <div class=" w-full grid items-center">
                                            <button class=" three-dot">
                                                <i class="bx bx-dots-vertical-rounded text-2xl text-mainText"></i>
                                            </button>
                                            <div class="hidden delButton absolute top-[11%] right-[12%] bg-bgcolor py-2 px-2 rounded-md border border-sgline">
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
                                            <a href="{{ route('student.room', $room->id) }}" class="">
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
                        <div class="sticky top-[12%] right-0">
                            <div class="absolute top-0 left-0 right-0 h-[88vh] mb-8">
                                <div class="flex flex-col h-full pb-8">
                                    <div class="pt-16 pb-8">
                                        <h1 class="text-4xl text-bgcolor font-semibold">Folders</h1>
                                    </div>
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
                                                                <h1 class="text-xl">{{ $classCount }}</h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 flex gap-2">
                                                        <div class=" w-[50%] h-full grid place-items-center">
                                                            <h1>No. of Folder</h1>
                                                        </div>
                                                        <div class=" w-[50%] h-full grid place-items-center">
                                                            <div class=" w-[90%] h-[70%] bg-mainText text-bgcolor grid place-items-center rounded-md border border-sgline">
                                                                <h1 class="text-xl">{{ $folderCount }}</h1>
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