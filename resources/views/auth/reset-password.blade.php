<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link rel="icon" href="{{ asset('images/saptlogo.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/auth.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>

    <section class=" w-full h-screen grid place-content-center bg-bgcolor">
        <div class=" bg-bgcolor rounded-md relative scale-90">
                <h1 class=" text-center mb-5 text-3xl font-bold">Reset Password!</h1>
                <div>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        {{-- Password --}}
                        <div class=" relative pb-6">
                            <input type="password" id="password" name="password" class="pass  md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border md:border-2 border-subtText @error('password') ring-1 ring-red-500 @enderror" placeholder="New Password" required>
                            <label for="password"></label>
                            <i class="bx bxs-show absolute right-5 top-4 cursor-pointer text-xl text-subtText"></i>
                            @error('password')
                                <p class=" text-red-500 text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                            @error('failed')
                                <p class=" text-red-500 text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                        </div>
                        {{-- Confirm Password --}}
                        <div class=" relative pb-12">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="pass  md:min-w-96 w-80 outline-mainText py-3 px-5 rounded-md bg-transparent border md:border-2 border-subtText @error('password_confirmation') ring-1 ring-red-500 @enderror" placeholder="Confirm Password">
                            <label for="password_confirmation"></label>
                            <i class="bx bxs-show absolute right-5 top-4 cursor-pointer text-xl text-subtText"></i>
                            @error('password_confirmation')
                                <p class=" text-red-500 text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                            @error('failed')
                                <p class=" text-red-500 text-[.785rem] md:text-sm"> {{$message}} </p>
                            @enderror
                        </div>
                        <div class="">
                            <button class=" md:min-w-96 w-80 outline-sky-300 py-3 px-4 rounded-md bg-mainText text-bgcolor border md:border-2 border-mainText hover:bg-bgcolor hover:text-mainText">Reset Password</button>
                        </div>
                    </form>
                </div>
        </div>
    </section>
    
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>
</html>