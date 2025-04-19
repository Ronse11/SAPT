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
        {{-- <section class="create-pop hidden w-screen h-screen absolute bg-bColor backdrop-blur-[2px] z-50"> --}}
            {{-- <div class=" w-full h-full grid place-items-center"> --}}

                <div class=" w-[26rem] max-w-[30rem] py-8 px-10 shadow-md rounded-md flex flex-col gap-y-8 bg-bgcolor border border-sgline">
                    <form action=" {{ route('roomFolder', $folderId->id) }} " method="POST" autocomplete="off">
                        @csrf
                        <div class="flex flex-col gap-y-8 mb-2">
                            <div class=" font-semibold text-placeHolderText mb-2 text-2xl">
                                <h1>Create Record</h1>
                            </div>
                            <div class="  gap-y-2 relative">
                                <input type="text" id="class-name" name="class_name" class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer" placeholder="Subject Code" >
                                <label for="class-name" class=" absolute left-0 -top-5 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-5 peer-focus:border-mainText peer-focus:text-mainText cursor-text transition-all">Subject Code</label>
                                @error('class_name')
                                    <p class=" absolute right-0 left-0 text-sm text-red-500 pb-4"> The subject code field is required. </p>
                                @enderror
                            </div>
                            <div class="  gap-y-2 relative">
                                <input type="text" id="subject" name="subject" class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer" placeholder="Descriptive Title">
                                <label for="subject" class=" absolute left-0 -top-5 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-5 peer-focus:border-mainText peer-focus:text-mainText cursor-text transition-all">Descriptive Title</label>
                                @error('subject')
                                    <p class="absolute right-0 left-0 text-sm text-red-500 pb-4"> The descriptive title field is required. </p>
                                @enderror
                            </div>
                            <div class="  gap-y-2 relative">
                                <input type="text" id="section" name="section" class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer" placeholder="Course/Year/Section" autocomplete="false">
                                <label for="section" class=" absolute left-0 -top-5 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-5 peer-focus:border-mainText peer-focus:text-mainText cursor-text transition-all">Course/Year/Section</label>
                                @error('section')
                                    <p class="absolute right-0 left-0 text-sm text-red-500 pb-4"> The course/year/section field is required. </p>
                                @enderror
                            </div>
                            <div>
                                <input type="hidden" name="role" id="role" value="Teacher">
                            </div>
                        </div>
                        <div class="flex justify-end gap-x-4 font-normal text-subtText">
                            <a href="{{ route('choices') }}" class="hover:text-mainText">Cancel</a>
                            <button class=" hover:text-mainText">Create</button>
                        </div>
                    </form>
                </div>
            {{-- </div> --}}
        {{-- </section> --}}
    </section>
</body>
</html>