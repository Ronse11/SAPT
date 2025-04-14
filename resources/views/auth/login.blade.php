<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="icon" href="{{ asset('images/saptlogo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/auth.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>

    <section class=" w-full h-screen grid place-content-center bg-bgcolor">
        <div class=" bg-bgcolor rounded-md relative scale-90">
                <h1 class=" text-center mb-5 text-3xl font-bold">Hi There!</h1>
                <div>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        {{-- Email --}}
                        <div class=" pb-6">
                            <input type="text" id="email" name="email" class="  md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border  border-subtText @error('email') ring-1 ring-red-500 @enderror" placeholder="Email" autocomplete="on">
                            <label for="email"></label>
                            @error('email')
                                <p class=" text-red-500 pb-4  text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class=" relative">
                            <input type="password" id="password" name="password" class="pass  md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border  border-subtText @error('password') ring-1 ring-red-500 @enderror" placeholder="Password">
                            <label for="password"></label>
                            <i class="bx bxs-show absolute right-5 top-4 cursor-pointer text-xl text-subtText"></i>
                            @error('password')
                                <p class=" text-red-500 text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                            @error('failed')
                                <p class=" text-red-500 text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                        </div>
                        <div class=" md:min-w-96 w-80 pb-4">
                            <h1 class=" text-end text-sm py-2"> <a href="{{ route('password.request') }}" class=" text-mainText underline">Forgot password?</a></h1>
                        </div>
                        <div class=" pb-2">
                            <button class=" md:min-w-96 w-80 outline-sky-300 py-3 px-4 rounded-md bg-mainText text-bgcolor border border-mainText hover:bg-bgcolor hover:text-mainText">Continue</button>
                        </div>

                        <div class=" md:min-w-96 w-80">
                            <h1 class=" text-center text-sm py-2">Don't have an account? <a href="{{ route('register') }}" class=" text-mainText underline">Signup</a></h1>
                        </div>
                        <div class=" pb-2  md:min-w-96 w-80">
                            <h1 class=" text-center text-sm py-2">OR</h1>
                        </div>
                        <div class=" flex mb-6 w-full h-auto">
                            <a href="{{ route('google-choose') }}" class="flex-1 bg-bgcolor text-start py-3 px-5 rounded-md text-mainText border border-subtText hover:border-mainText">Login with Google</a>
                        </div>
                    </form>
                </div>
        </div>
    </section>
    
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>