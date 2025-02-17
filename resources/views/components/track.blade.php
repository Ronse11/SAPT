<section class="track-pop hidden w-screen h-screen absolute bg-bColor backdrop-blur-[2px] z-50">
    <div class=" w-full h-full grid place-items-center">
        <div class=" w-full max-w-[26rem] p-8 shadow-md rounded-md flex flex-col gap-y-4 bg-bgcolor">
            <div class="border-b-2 border-subtText pb-4">
                <h1 class="mb-6 font-semibold">You are signed in as</h1>
                <div class="flex gap-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-subtText rounded-full">
                        <h1 class="text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                    </div>
                    <div>
                        <h1 class=""> {{ auth()->user()->username }} </h1>
                        <h3 class="text-sm text-subtText"> {{ auth()->user()->email }} </h3>
                    </div>
                </div>
            </div>
            <form action="" method="">
                <div class="flex flex-col gap-y-6">
                    <label for="code-number" class="font-semibold">Enter your Track Code :</label>
                    <input type="text" id="code-number" class=" px-3 py-2 border-2 border-subtText outline-mainText rounded-md" placeholder="Track Code">
                </div>
                <div class="flex flex-col gap-y-4">
                    <h1 class="text-sm text-subtText">example: its-okies-oki</h1>
                    <h1 class="">The Track Code will be provided by your Teacher.</h1>
                </div>
                <div class="flex justify-end gap-x-4 font-normal text-subtText">
                    <button class="track-cancel hover:text-mainText">Cancel</button>
                    <button class=" hover:text-mainText">Join</button>
                </div>
            </form>
        </div>
    </div>
</section>