<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Track Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/users.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen grid grid-cols-6 grid-rows-8 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 tablet:gap-y-4 bg-bgcolor relative">

        {{-- Header --}}
        <header class=" w-screen row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
            {{-- <div class="flex gap-4 items-center">
                <button id="menu-bar" class=" text-4xl pt-1 cp:text-3xl"><i
                        class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
            </div> --}}
            <div class=" flex items-center pr-6">
                <a href=" {{ route('student.room', $encodedId) }} " class=" h-14 w-14 grid place-content-center rounded-full cp:text-3xl">
                    <i class="bx bx-arrow-back text-4xl text-subtText hover:text-mainText"></i>
                </a>
            </div>
            <div class="flex gap-4 relative items-center">
                <button class="grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:text-subtText">
                    <i class='bx bxs-cog text-3xl font-weight-bolder'></i>
                </button>
                <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText">
                    <h1 class=" text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                </button>
                <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Body --}}
        <section class=" row-start-2 row-span-7 col-start-1 col-span-6 px-4 tablet:pb-8 grid grid-cols-8 grid-rows-8 tablet:col-start-1 tablet:col-span-16 tablet:px-16 tablet:row-start-2 tablet:row-span-7 cp:col-start-2 cp:col-span-10 small-bp">
            <div class=" row-start-1 row-span-8 col-start-1 col-span-8 overflow-y-auto overflow-scrolls">
                <div class=" w-full h-full flex flex-col gap-8 px-8 py-8 rounded-3xl">
                    <div class="text-center">
                        <h1 class=" text-4xl font-semibold">RECORDS</h1>
                    </div>
                    <div class="h-full flex gap-8 rounded-xl shadow-md p-4 bg-gray-100">
                        <div class="p-4 flex-1 bg-gray-100 overflow-x-auto">
                            <h1>No Records yet!</h1>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
