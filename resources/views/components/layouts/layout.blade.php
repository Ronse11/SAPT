<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Academic Performance Tracker</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/ui.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen grid grid-cols-6 grid-rows-8 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 tablet:gap-y-4 bg-bgcolor">

        {{-- Header --}}
        <header
            class=" w-screen row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
            <div class="flex gap-4 items-center">
                <button id="menu-bar" class=" text-4xl pt-1 cp:text-3xl"><i
                        class='bx bx-menu text-mainText'></i></button>
                <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
            </div>
            <div class="flex gap-4 relative">
                <button class="btn-plus grid place-items-center rounded-full w-12 h-12 text-mainText cp:text-2xl cursor-pointer hover:bg-subtText">
                    <i class='bx bx-plus text-3xl font-weight-bolder'></i>
                </button>
                <div class="choices bg-bgcolor hidden absolute left-0 -ml-[9rem] mt-9 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <button class="btn-track text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Track Record</button>
                        <button class="btn-create text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2">Create Record</button>
                    </div>
                </div>
                <button class=" text-3xl w-12 h-12 rounded-full text-mainText shadow-md cp:text-2xl">
                    <i class='bx bxs-user'></i>
                </button>
            </div>
        </header>

        <x-track />
        <x-create />

        {{ $slot }}

    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
