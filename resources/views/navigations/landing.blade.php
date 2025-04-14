<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Academic Performance Tracker</title>
    {{-- <link rel="icon" href="{{ asset('images/saptlogo.svg') }}"> --}}

    @vite(['resources/css/app.css', 'resources/js/nav/landingAnimations.js'])   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
    <section id="home" class=" w-full h-dvh grid place-items-center md:h-screen bg-gradient-to-t from-slate-300 via-gray-200 to-gray-50 relative">
        <svg class=" lg:w-[55rem] md:w-[41rem] sm:w-[40rem] absolute bottom-0 -z-1 [mask-image:linear-gradient(to_bottom,transparent,transparent,black)]" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 800'><g fill='none' stroke='#94a3b8'  stroke-width='.2'><path d='M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63'/><path d='M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764'/><path d='M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880'/><path d='M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382'/><path d='M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269'/></g><g  fill='#94a3b8'><circle  cx='769' cy='229' r='3'/><circle  cx='539' cy='269' r='3'/><circle  cx='603' cy='493' r='3'/><circle  cx='731' cy='737' r='3'/><circle  cx='520' cy='660' r='3'/><circle  cx='309' cy='538' r='3'/><circle  cx='295' cy='764' r='3'/><circle  cx='40' cy='599' r='3'/><circle  cx='102' cy='382' r='3'/><circle  cx='127' cy='80' r='3'/><circle  cx='370' cy='105' r='3'/><circle  cx='578' cy='42' r='3'/><circle  cx='237' cy='261' r='3'/><circle  cx='390' cy='382' r='3'/>
        </g></svg>

        {{-- SHOW BAR FOR CP SIZE --}}
        <div id="show-bar" class="opacity-0 pointer-events-none fixed md:hidden top-0 right-0 bottom-0 w-0 transition-all duration-500 ease-in-out z-50">            
            <div class="w-full h-full shadow-xl flex flex-col border-l border-sgline gap-6 bg-mainBg">
                <nav class=" flex flex-col w-full h-full bg-gray-100 absolute top-0 left-0 border-r border-sgline">
                    <div class="flex py-4 px-8 items-center justify-end border-b border-sgline">
                        <div class="relative group">
                            <button id="close-bar" class=" mb-1 text-3xl pt-1 cp:text-3xl"><i class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                            <div class="absolute top-full -left-[50%] transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                                <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg ">
                                    <h1>Close sidebar</h1>
                                    <div class="absolute -top-1 left-[82%] transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                                </div>  
                            </div>
                        </div>  
                    </div>
                    <div class=" py-4 px-8 flex flex-col items-center">
                        <a href="#home"
                            class="home w-full flex justify-center text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                            <h1 class=" text-base text-center">Home</h1>
                        </a>
                        <a href="#about"
                            class="about w-full flex justify-center text-md  text-mainText hover:bg-navHover hover:text-mainText rounded-md pl-4 py-3">
                            <h1 class="text-base text-center">About</h1>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
        
        <header class=" absolute top-0 left-0 right-0 flex justify-between items-center sm:px-16 sm:py-6 px-8 py-3 border-b border-sgline">
            <div class="flex items-center">
                <img class=" w-8 h-8 mb-3 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                <h1 class=" text-xl font-normal text-black select-none tracking-widest cp:text-3xl sm:block hidden">APT</h1>
            </div>
            <nav class=" text-gray-400 sm:block hidden">
                <a href="" class=" hover:text-mainText mr-12">Home</a>
                <a href="#about" class="hover:text-mainText">About</a>
            </nav>
            <a href="{{ route('login') }}" class=" w-28 text-center rounded-xl md:py-2 py-3 text-bgcolor border-2 border-mainText bg-mainText hover:text-mainText hover:bg-transparent text-sm sm:block hidden">Get Started</a>
            <button class=" open-menu sm:hidden block">
                <i class='bx bx-menu text-3xl text-mainText hover:text-subtText'></i>
            </button>
        </header>
        <div class="flex flex-col items-center gap-6 sm:w-auto w-[80%] sm:mt-10 z-40">
            {{-- <div class=" w-full sm:text-center text-start">
                <h1 class=" text-sm text-mainText opacity-40 sm:mt-5">Track Success</h1>
            </div> --}}
            <div class=" lg:w-[55rem] md:w-[41rem] sm:w-[40rem] sm:text-center text-start transform opacity-0 translate-y-10 transition duration-700 delay-100" id="el-1">
                <h1 class=" text-3xl md:text-[3.5rem] sm:text-[2rem] font-normal leading-tight"><span class=" opacity-60">Academic Insight Made</span> Simple</h1>
            </div>
            <div class=" lg:w-[45rem] md:w-[39rem] sm:w-[32rem] sm:text-sm text-[12px] sm:mt-0 -mt-4 sm:text-center text-start transform opacity-0 translate-y-10 transition duration-700 delay-300"  id="el-2">
                <p class="opacity-40">Helping educators guide every learner toward success, by providing clear insights through actionable academic records.</p>
            </div>
            <div class=" w-full flex sm:justify-center justify-start items-center gap-4 md:my-5 -mt-2 transform opacity-0 translate-y-10 transition duration-700 delay-500" id="el-3">
                <a href="{{ route('login') }}" class=" w-28 grid place-items-center rounded-xl md:py-2 py-3 text-bgcolor border-2 border-mainText bg-mainText hover:text-mainText hover:bg-transparent text-sm">Log in</a>
                {{-- <h1 class=" md:hidden">or</h1> --}}
                <a href="{{ route('register') }}" class=" w-28 grid place-items-center rounded-xl md:py-2 py-3 text-mainText border-2 border-bgcolor bg-bgcolor hover:text-mainText hover:bg-transparent hover:border-mainText text-sm">Sign up</a>
            </div>
            {{-- IMAGES --}}
            <div class="w-full flex justify-center sm:items-center relative sm:mt-5 mt-1 -mb-20 flex-col sm:flex-row sm:gap-0 gap-2 " >
                <img class="lg:w-[17rem] md:w-[12rem] sm:w-[9rem] w-[14rem] rounded-lg z-0 sm:-mr-10 shadow-lg transform opacity-0 -translate-x-20 transition duration-700 delay-700" id="el-4" src="{{ Vite::asset('resources/images/choose.png') }}" alt="Choose">
                
                <img class="lg:w-[25rem] md:w-[20rem] sm:w-[17rem] w-[14rem] rounded-lg z-10 shadow-lg max-sm:self-end transform opacity-0 translate-y-20 transition duration-700 delay-700" id="el-5" src="{{ Vite::asset('resources/images/landing.png') }}" alt="Landing">
                
                <img class="lg:w-[17rem] md:w-[12rem] sm:w-[9rem] w-[14rem] rounded-lg z-0 sm:-ml-10 shadow-lg transform opacity-0 translate-x-20 transition duration-700 delay-700" id="el-6" src="{{ Vite::asset('resources/images/record.png') }}" alt="Record">
            </div>
            
        </div>
    </section>

    {{-- ABOUT PAGE --}}
    <section id="about" class="w-full grid place-items-center pt-8 pb-16 relative">
        <svg class="lg:w-[55rem] md:w-[41rem] sm:w-[40rem] absolute top-0 -z-10" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 800'><g fill='none' stroke='#d1d5db'  stroke-width='.2'><path d='M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63'/><path d='M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764'/><path d='M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880'/><path d='M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382'/><path d='M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269'/></g><g  fill='#d1d5db'><circle  cx='769' cy='229' r='3'/><circle  cx='539' cy='269' r='3'/><circle  cx='603' cy='493' r='3'/><circle  cx='731' cy='737' r='3'/><circle  cx='520' cy='660' r='3'/><circle  cx='309' cy='538' r='3'/><circle  cx='295' cy='764' r='3'/><circle  cx='40' cy='599' r='3'/><circle  cx='102' cy='382' r='3'/><circle  cx='127' cy='80' r='3'/><circle  cx='370' cy='105' r='3'/><circle  cx='578' cy='42' r='3'/><circle  cx='237' cy='261' r='3'/><circle  cx='390' cy='382' r='3'/>
        </g></svg>
        <svg class="lg:w-[55rem] md:w-[41rem] sm:w-[40rem] absolute bottom-0 -z-10" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 800'><g fill='none' stroke='#d1d5db'  stroke-width='.2'><path d='M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63'/><path d='M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764'/><path d='M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880'/><path d='M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382'/><path d='M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269'/></g><g  fill='#d1d5db'><circle  cx='769' cy='229' r='3'/><circle  cx='539' cy='269' r='3'/><circle  cx='603' cy='493' r='3'/><circle  cx='731' cy='737' r='3'/><circle  cx='520' cy='660' r='3'/><circle  cx='309' cy='538' r='3'/><circle  cx='295' cy='764' r='3'/><circle  cx='40' cy='599' r='3'/><circle  cx='102' cy='382' r='3'/><circle  cx='127' cy='80' r='3'/><circle  cx='370' cy='105' r='3'/><circle  cx='578' cy='42' r='3'/><circle  cx='237' cy='261' r='3'/><circle  cx='390' cy='382' r='3'/>
        </g></svg>
        <div class="flex flex-col items-center sm:w-auto w-[80%]">
            <div class=" w-full text-center text-3xl font-semibold p-8 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" text-mainText">ABOUT US</h1>
            </div>
            <div class=" w-full text-start font-semibold px-8 py-6 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" text-mainText opacity-80 text-xl">Our Story</h1>
            </div>
            <div class="lg:w-[55rem] md:w-[41rem] sm:w-[40rem] bg-bgcolor text-sm p-8 text-justify flex flex-col gap-4 rounded-lg  mb-4 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" opacity-80 font-normal">The idea for the Student Academic Performance Tracker came from a common frustration that many students experience year after year. It all started when we noticed that a number of students—despite their consistent effort, participation, and strong performance throughout the school year—were often shocked by their final grades. These grades didn’t always align with the effort they put into their studies or the progress they had shown in class. It was disheartening to see hardworking students face unexpected results, leading to confusion, frustration, and missed opportunities. </h1>
                <h1 class=" opacity-80 font-normal">We realized that the problem wasn’t the lack of effort or dedication from students, but the lack of timely and accessible feedback regarding their academic progress. Students were unaware of how they were truly performing until the final grades were posted. This left them with little to no time to address issues, improve their performance, or adjust their approach.</h1>
                <h1 class=" opacity-80 font-normal">The problem also affected teachers. Teachers were finding it difficult to provide continuous feedback in an efficient and organized way, which made it harder for students to fully understand where they stood.
                </h1>
                <h1 class=" opacity-80 font-normal">After recognizing this gap in the system, we decided to take action. We wanted to build a solution that would allow students to track their progress throughout the year, giving them the opportunity to improve and make necessary changes before it was too late. At the same time, we aimed to create a platform for teachers that would make managing and updating student performance easier and more transparent.
                </h1>
                <h1 class=" opacity-80 font-normal">Thus, the Student Academic Performance Tracker was born—designed to empower both students and teachers by providing real-time feedback, greater transparency, and an early opportunity for improvement.
                </h1>
            </div>
            <div class=" w-full text-start font-semibold px-8 py-6 mt-24 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" text-mainText opacity-80 text-xl">Meet the Developers</h1>
            </div>
            <div class="w-full flex sm:flex-row flex-col sm:gap-0 gap-8 justify-evenly items-center mb-4 py-8 bg-gray-50 rounded-lg scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <div class="relative ">
                    <img class=" w-[13rem] " src="{{ Vite::asset('resources/images/kyle.jpg') }}" alt="">
                    <div class=" absolute bottom-3 left-3 right-3 p-3 bg-gray-300 border-2 border-bgcolor text-center">
                        <h1 class=" font-semibold">Kyle Soriano</h1>
                    </div>
                </div>
                <div class="relative ">
                    <img class=" w-[13rem] " src="{{ Vite::asset('resources/images/gelyn.jpg') }}" alt="">
                    <div class=" absolute bottom-3 left-3 right-3 p-3 bg-gray-300 border-2 border-bgcolor text-center">
                        <h1 class=" font-semibold">Gelyn Rosario</h1>
                    </div>
                </div>
                <div class="relative ">
                    <img class=" w-[13rem] " src="{{ Vite::asset('resources/images/ronald.jpg') }}" alt="">
                    <div class=" absolute bottom-3 left-3 right-3 p-3 bg-gray-300 border-2 border-bgcolor text-center">
                        <h1 class=" font-semibold">Ronald Seron</h1>
                    </div>
                </div>
            </div>
            <div class=" w-full text-start font-semibold px-8 py-6 mt-24 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" text-mainText opacity-80 text-xl">Our Mission</h1>
            </div>
            <div class="lg:w-[55rem] md:w-[41rem] sm:w-[40rem] bg-gray-50 text-sm p-8 text-justify flex flex-col gap-4 rounded-lg  mb-4 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" opacity-80 font-normal">To help students and teachers keep track of academic performance early. We made this system so students can see their grades as they go, not just at the end of the school year. This way, they can improve before it’s too late. Teachers can also use it to update grades easily and give feedback faster. </h1>
            </div>
            <div class=" w-full text-start font-semibold px-8 py-6 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" text-mainText opacity-80 text-xl">Our Vision</h1>
            </div>
            <div class="lg:w-[55rem] md:w-[41rem] sm:w-[40rem] bg-gray-50 text-sm p-8 text-justify flex flex-col gap-4 rounded-lg  mb-4 scroll-animate opacity-0 translate-y-10 transition-all duration-700">
                <h1 class=" opacity-80 font-normal">We want to create a school environment where students always know how they’re doing. Our vision is to make learning more clear and fair by giving both students and teachers the right tools to stay updated and connected throughout the school year. </h1>
            </div>
            
        </div>
    </section>
    
</body>
</html>
