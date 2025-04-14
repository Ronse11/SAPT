<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Changed Password</title>
    <link rel="icon" href="{{ asset('images/saptlogo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/auth.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>

    <section class=" w-full h-screen grid place-content-center bg-bgcolor">
        <div class=" bg-bgcolor rounded-md relative scale-90">
                <h1 class=" text-center mb-2 text-3xl font-semibold">Successfully Changed!</h1>
                <div class="md:min-w-96 w-80 opacity-70 text-center mb-12 text-[13px]">
                    <p>Your password has been successfully updated.</p>
                </div>
                <div>
                    <div class=" md:min-w-96 w-80 bg-slate-200 flex">
                        <a href="{{ route('login') }}" class="flex-1 text-center py-3 px-4 rounded-md bg-mainText text-bgcolor border md:border-2 border-mainText hover:bg-bgcolor hover:text-mainText cursor-pointer" >Go to Login Page</a>
                    </div>
                </div>
        </div>
    </section>
    
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>