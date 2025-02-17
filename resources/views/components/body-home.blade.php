<section id="hero" class=" row-start-2 row-span-7 col-start-1 col-span-6 px-4 tablet:pb-8 grid grid-cols-8 grid-rows-8 tablet:col-start-4 tablet:col-span-11 tablet:row-start-2 tablet:row-span-7 cp:col-start-2 cp:col-span-10 small-bp">
    <div class=" row-start-1 row-span-1 col-start-1 col-span-8 flex items-center tablet:items-start">
        <h1 class=" text-4xl text-mainText font-semibold"> {{ auth()->user()->username }} </h1>
    </div>
    <div class="row-start-2 row-span-7 col-start-1 col-span-8 overflow-y-auto overflow-scrolls">
        <div class="content-box h-auto grid grid-cols-adjust lg:grid-cols-3 3xl:grid-cols-4 gap-8">
            {{-- Class Box --}}
            @foreach ($rooms as $room)              
            <div class=" bg-mainText rounded-md p-5 shadow-md relative">
                <div class="flex flex-col gap-y-1 select-none">
                    <h3 class=" text-lg text-bgcolor"> {{ $room()->section }} </h3>
                    <h1 class=" text-3xl text-bgcolor">Class Name</h1>
                </div>
                <h5 class=" text-bgcolor pt-8 cp:pt-12">Student Count</h5>
                <button class="absolute top-0 right-0 mt-5 mr-4">
                    <i class="bx bx-dots-vertical-rounded text-2xl text-bgcolor"></i>
                </button>
                <a href="" class="absolute right-0 bottom-0 mb-3 mr-5">
                    <i class='bx bxs-folder-open text-3xl text-bgcolor'></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>