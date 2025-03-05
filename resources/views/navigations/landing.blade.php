<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Academic Performance Tracker</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/saptlogo.svg')  }}">

    @vite(['resources/css/app.css'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
    <section class=" w-full h-screen grid place-content-center">
        <div class="flex flex-col gap-6 w-[30rem]">
            <div class=" w-full text-center ">
                <h1 class=" text-4xl font-bold">Get started</h1>
            </div>
            <div class=" w-full flex justify-center gap-8 text-md">
                <a href="{{ route('login') }}" class=" w-full grid place-items-center rounded-md py-4 text-bgcolor border-2 border-mainText bg-mainText hover:text-mainText hover:bg-transparent">Log in</a>
                <a href="{{ route('register') }}" class=" w-full grid place-items-center rounded-md py-4 text-bgcolor border-2 border-mainText bg-mainText hover:text-mainText hover:bg-transparent ">Sign up</a>
            </div>
        </div>
    </section>

</body>
</html>
