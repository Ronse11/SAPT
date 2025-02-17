<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Room</title>

    @vite(['resources/css/app.css'])
</head>

<body>
    <section class=" w-full h-screen grid place-content-center">
        <div class=" w-[26rem] max-w-[30rem] py-8 px-10 shadow-md rounded-md flex flex-col gap-y-8 bg-bgcolor border border-sgline">
            <form action=" {{ route('folders.store') }} " method="POST" autocomplete="off">
                @csrf
                <div class="flex flex-col gap-y-8 mb-2">
                    <div class=" font-semibold text-placeHolderText text-2xl mb-2">
                        <h1>Create Folder</h1>
                    </div>
                    <div class="  gap-y-2 relative mb-8">
                        <input type="text" id="folder-name" name="folder_name"
                            class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer"
                            placeholder="Class Name">
                        <label for="folder-name"
                            class=" absolute left-0 -top-4 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-5 peer-focus:border-mainText peer-focus:text-mainText cursor-text transition-all">Folder Name</label>
                    </div>
                    @error('folder_name')
                        <p class=" text-sm text-red-500 pb-4"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="flex justify-end gap-x-4 font-normal text-subtText">
                    <a href="{{ route('choices') }}" class="hover:text-mainText">Cancel</a>
                    <button class=" hover:text-mainText">Create</button>
                </div>
            </form>
        </div>
    </section>
</body>

</html>
