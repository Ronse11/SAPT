<section class="create-pop hidden  w-screen h-screen absolute bg-bColor backdrop-blur-[2px] z-50">
    <div class=" w-full h-full grid place-items-center">
                    <div class=" w-[26rem] max-w-[30rem] py-8 px-10 shadow-md rounded-md flex flex-col gap-y-8 bg-bgcolor">
                        <form action=" {{ route('room') }} " method="POST" autocomplete="off">
                            @csrf
                            <div class="flex flex-col gap-y-8 mb-2">
                                <div class=" font-semibold text-subtText text-2xl">
                                    <h1>Create Record</h1>
                                </div>
                                <div class="  gap-y-2 relative">
                                    <input type="text" id="class-name" name="class_name" class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer" placeholder="Class Name" >
                                    <label for="class-name" class=" absolute left-0 -top-4 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-4 peer-focus:border-mainText peer-focus:text-subtText cursor-text transition-all">Subject Code</label>
                                </div>
                                @error('class_name')
                                    <p class=" text-sm text-red-500 pb-4"> {{$message}} </p>
                                @enderror
                                <div class="  gap-y-2 relative">
                                    <input type="text" id="subject" name="subject" class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer" placeholder="Subject">
                                    <label for="subject" class=" absolute left-0 -top-4 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-4 peer-focus:border-mainText peer-focus:text-subtText cursor-text transition-all">Descriptive Title</label>
                                </div>
                                @error('subject')
                                    <p class=" text-sm text-red-500 pb-4"> {{$message}} </p>
                                @enderror
                                <div class="  gap-y-2 relative">
                                    <input type="text" id="section" name="section" class=" w-full py-2 border-b-2 focus:border-mainText text-mainText outline-none placeholder-transparent peer" placeholder="Section" autocomplete="false">
                                    <label for="section" class=" absolute left-0 -top-4 text-subtText peer-focus:text-base peer-placeholder-shown:text-base peer-placeholder-shown:top-[9.5px] peer-focus:-top-4 peer-focus:border-mainText peer-focus:text-subtText cursor-text transition-all">Course/Year/Section</label>
                                </div>
                                @error('section')
                                    <p class=" text-sm text-red-500 pb-4"> {{$message}} </p>
                                @enderror
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
    </div>
</section>
