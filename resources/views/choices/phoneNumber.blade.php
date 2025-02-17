<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Phone Room</title>

    @vite(['resources/css/app.css'])
</head>
<body>
    <section class=" w-full h-screen grid place-content-center">
        <div class=" w-full h-full grid place-items-center">
            <div class=" w-full max-w-[26rem] p-8 shadow-md rounded-md flex flex-col gap-y-4 bg-bgcolor border border-sgline">
                <div class="border-b-2 border-subtText pb-4">
                    <h1 class="mb-6 font-semibold">You are signed in as</h1>
                    <div class="flex gap-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-mainText rounded-full">
                            <h1 class="text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                        </div>
                        <div>
                            <h1 class=""> {{ auth()->user()->username }} </h1>
                            <h3 class="text-sm text-subtText"> {{ auth()->user()->email }} </h3>
                        </div>
                    </div>
                </div>
                <form action=" {{ route('checkPhoneNumber') }} " method="POST" autocomplete="off">
                    @csrf
                    <div class="flex flex-col relative pb-1">
                        <label for="phone_number" class="font-semibold pb-6">Enter your Phone Number :</label>
                        <input type="text" id="phone_number" name="phone_number" class=" px-3 py-2 border-2 border-subtText outline-mainText rounded-md @error('error') ring-2 ring-red-500 text-red-500 placeholder-red-500 @enderror" placeholder="09XX-XXX-XXXX">
                        @error('phone_number')
                            <p class=" text-sm text-red-500 mt-2">{{$message}}</p>
                        @enderror
                        @error('error')
                            <p class=" text-sm text-red-500 mt-2">{{$message}}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="hidden" name="role" id="role" value="Student">
                    </div>
                    <div>
                        <h1 class="text-sm text-subtText">example: its-oki-oki</h1>
                    </div>
                    <div class="flex flex-col gap-y-4 mt-4">
                        <h1 class="">The Track Code will be provided by your Teacher.</h1>
                    </div>
                    <div class="flex justify-end gap-x-4 mt-4 font-normal text-subtText">
                        <a href="{{ route('choices') }}" class=" hover:text-mainText">Cancel</a>
                        <button class=" hover:text-mainText">Join</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>