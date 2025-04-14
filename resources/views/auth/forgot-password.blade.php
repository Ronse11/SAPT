<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
    <link rel="icon" href="{{ asset('images/saptlogo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/auth.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>

    <section class=" w-full h-screen grid place-content-center bg-bgcolor">
        <div class=" bg-bgcolor rounded-md relative scale-90">
                <h1 class=" text-center mb-2 text-3xl font-semibold">Reset your password</h1>
                <div class="md:min-w-96 w-80 opacity-70 text-center mb-12 text-[13px]">
                    <p>Input your email address account to receive a reset link</p>
                </div>
                <div>
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        {{-- Email --}}
                        <div class=" pb-6">
                            <input type="text" id="email" name="email" class="  md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border md:border-2 border-subtText @error('email') ring-1 ring-red-500 @enderror" placeholder="Email" autocomplete="on">
                            <label for="email"></label>
                            @error('email')
                                <p class=" text-red-500 pb-4  text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                        </div>
                        <div class=" pb-6">
                            <button class=" md:min-w-96 w-80 py-3 px-4 rounded-md bg-mainText text-bgcolor border md:border-2 border-mainText hover:bg-bgcolor hover:text-mainText">Continue</button>
                        </div>
                        <div class=" md:min-w-96 w-80 flex justify-center ">
                            <a href="{{ route('register') }}" class=" text-mainText flex items-center gap-1 opacity-70">
                                <i class='bx bx-arrow-back text-xl'></i>
                                <p class=" text-sm">Back to log in</p>
                            </a>
                        </div>
                    </form>
                </div>
        </div>
    </section>
    
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>