<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup</title>
    <link rel="icon" href="{{ asset('images/saptlogo.svg') }}">


    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/auth.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <section class=" w-full h-screen grid place-content-center bg-bgcolor">
        <div class=" bg-bgcolor rounded-md">
            <div> 
                <h1 class=" text-center mb-5 text-3xl font-bold">Create an account</h1>
                <div>
                    <form action="{{ route('register') }}" method="POST" autocomplete="off">
                        @csrf
                        {{-- Username --}}
                        <div class=" pb-6">
                            <input type="text" id="username" name="username" class=" md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border-2 border-subtText @error('username') ring-1 ring-red-500 @enderror" placeholder="Username" value="{{old('username')}}" autocomplete="off">
                            <label for="username"></label>
                            @error('username')
                                <p class=" text-red-500 text-sm"> {{$message}} </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class=" pb-6">
                            <input type="text" id="email" name="email" class=" md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border-2 border-subtText  @error('email') ring-1 ring-red-500 @enderror" placeholder="Email" value="{{old('email')}}" autocomplete="off">
                            <label for="email"></label>
                            @error('email')
                            <p class=" text-red-500 text-sm"> {{$message}} </p>
                        @enderror
                        </div>

                        {{-- Password --}}
                        <div class=" pb-6 relative">
                            <input type="password" id="password" name="password" class="pass md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border-2 border-subtText @error('password') ring-1 ring-red-500 @enderror" placeholder="Create password">
                            <label for="password"></label>
                            <i class="bx bxs-show absolute right-5 top-4 cursor-pointer text-xl text-subtText"></i>
                            @error('password')
                            <p class=" text-red-500 text-sm"> {{$message}} </p>
                        @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class=" pb-2">
                            <button class="md:min-w-96 w-80 outline-sky-300 py-3 px-4 rounded-md bg-mainText text-bgcolor border-2 border-mainText hover:bg-bgcolor hover:text-mainText">Continue</button>
                        </div>

                        <div class=" md:min-w-96 w-80">
                            <h1 class=" text-center text-sm py-2">Already have an account? <a href="{{ route('login') }}" class=" text-mainText">Login</a></h1>
                        </div>
                        <div class=" pb-2 md:min-w-96 w-80">
                            <h1 class=" text-center text-sm py-2">OR</h1>
                        </div>
                        {{-- OAuth --}}
                        <div class=" flex mb-6 w-full h-auto">
                            <a href="{{ route('google-choose') }}" class="flex-1 bg-bgcolor text-start py-3 px-5 rounded-md text-mainText border-2 border-subtText hover:border-mainText">Login with Google</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>