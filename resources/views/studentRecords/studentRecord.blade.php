<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Track Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/studentRecords/records.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-full h-screen overflow-hidden">

        {{-- Body --}}
        <section class="relative w-full h-full flex gap-8 lg:px-16 lg:py-8 sm:p-8 p-4">
            <div class=" absolute cp:hidden top-0 left-0 right-0 h-[28%] bg-mainBg -z-10 rounded-b-3xl"></div>
            <div id="show-bar" class="opacity-0 pointer-events-none z-50 absolute md:hidden top-0 right-0 bottom-0 w-0 transition-all duration-500 ease-in-out">
                <div class="w-full h-full shadow-xl pr-7 pl-4 py-4 flex flex-col border-l border-sgline gap-6 bg-mainBg">
                    <div class="flex items-center justify-between pb-2 border-b border-sgline">
                        <div class="flex items-center -mt-4">
                            <img class="w-8 h-8 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                            <h1 class="pt-3 text-3xl font-normal text-black select-none tracking-widest">APT</h1>
                        </div>
                        <div id="close-bar" class="md:hidden cursor-pointer">
                            <i class='text-4xl bx bx-menu'></i>
                        </div>
                    </div>
            
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('student-home') }}" class="flex items-center text-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class="bx bxs-home text-lg"></i>
                            <span>Home</span>
                        </a>
                        <div>
                            <div>
                                <button class="records-btn w-full bg-mainText flex items-center text-bgcolor font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                                    <i class="bx bxs-book-alt text-lg"></i>              
                                    Records
                                </button>
                            </div>
                        </div>
            
                        <a href="{{ route('studentAttendance.room', [ 'id' => $encodedId, 'key' => 'attendance-table']) }}" class="w-full flex  items-center text-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class='bx bxs-check-square text-lg'></i>            
                            Attendance
                        </a>
                    </div>
                </div>
            </div>
            

              


            <div class="bg-gray-50 rounded-2xl px-4 pb-6 md:min-w-[16rem] min-w-[13rem] cp:flex hidden flex-col justify-between border border-sgline">

                <div>
                    <div class="h-20 flex items-center mb-4 border-b border-sgline">
                        <img class=" w-8 h-8 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                        <h1 class=" pt-3 text-xl font-normal text-black select-none tracking-widest cp:text-3xl">APT</h1>
                    </div>
    
                    <div class=" flex flex-col gap-3">
                        <a href="{{ route('student-home') }}" class=" flex items-center text-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class="bx bxs-home text-lg"></i>
                            <span >Home</span>
                        </a>
                        <div>
                            <div class="">
                                <button class="records-btn w-full bg-mainText flex items-center text-bgcolor font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                                    <i class="bx bxs-book-alt text-lg"></i>              
                                    Records
                                </button>
                            </div>
                        </div>
    
                        <a href="{{ route('studentAttendance.room', [ 'id' => $encodedId, 'key' => 'attendance-table']) }}" class="w-full flex items-center text-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class='bx bxs-check-square text-lg'></i>            
                            Attendance
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <!-- User Avatar -->
                    <div class="w-9 h-9 rounded-full bg-mainText flex items-center justify-center text-xl font-semibold text-white">
                        {{ substr(auth()->user()->username, 0, 1) }}
                    </div>
                    
                    <!-- Username -->
                    <h2 class="mt-3 text-base font-semibold text-gray-800">{{auth()->user()->username}}</h2>
                    
                    <!-- Email -->
                    <p class="text-sm font-light text-gray-500">{{auth()->user()->email}}</p>
                </div>
                
            </div>

            <div class="flex-1 overflow-y-auto overflow-scrolls">
                {{-- overflow-y-auto overflow-scrolls --}}

                <div class=" w-full h-full flex flex-col gap-8 ">


                    <div class=" w-full md:h-20 flex items-center justify-between lg:px-8">
                        <h1 class=" md:text-3xl text-2xl font-bold text-mainText">Records</h1>
                        <div id="menu-bar" class="sm:hidden cursor-pointer">
                            <i class='text-4xl bx bx-menu'></i>
                        </div>
                        {{-- class="absolute sm:hidden block bottom-10 left-1/2 transform -translate-x-1/2" --}}
                        <div class=" absolute sm:relative bottom-10 sm:bottom-auto left-1/2 sm:left-auto transform sm:transform-none -translate-x-1/2 sm:translate-x-0">
                            <button id="toggleButton" class="relative w-48 h-9 bg-gray-200 rounded-full flex items-center transition-all duration-300 ease-in-out">
                                <!-- Active Indicator -->
                                <span id="activeIndicator" 
                                    class="absolute left-1 w-[48%] h-7 bg-mainText rounded-full transition-all duration-300 ease-in-out"></span>
                                
                                <!-- Chart Tab (Default Active) -->
                                <span id="chartTab" 
                                    class="relative w-1/2 h-full flex items-center justify-center text-white font-semibold transition-all duration-300 ease-in-out z-10">
                                    <i class=' text-[.9rem] bx bxs-doughnut-chart' ></i>
                                    <h3 class=" text-[.9rem] ml-1 font-light">Chart</h3>
                                </span>
                                <!-- Table Tab -->
                                <span id="tableTab" 
                                    class="relative w-1/2 h-full flex items-center justify-center text-gray-700 font-semibold transition-all duration-300 ease-in-out z-10">
                                    <i class='text-[.9rem] bx bxs-square '></i>
                                    <h3 class=" text-[.9rem] ml-1 font-light">Table</h3>
                                </span>
                            </button>

                        </div>
                    </div>

                    <div class=" w-full h-full flex flex-col lg:px-8 overflow-hidde">
                        <!-- Chart Section (Initially Hidden) -->
                        <div id="chartSection" class="w-full flex flex-col">
                            <div id="quizTabsContainer" class="flex-1 flex flex-col gap-4">
                                <div class="flex-1 flex flex-col gap-6">
                                    <div class="rounded-lg bg-mainText p-4 text-bgcolor sm:shadow-sm shadow-sm">
                                        <div class="flex-1">
                                            <div class="mb-4 font-semibold text-lg">
                                                <h1 class="">Class Details</h1>
                                            </div>
                                            <div class="pl-2 flex gap-4 text-sm">
                                                <div class=" flex flex-col gap-1">
                                                    <h1 class=" font-medium">Subject Code</h1>
                                                    <h1 class=" font-medium">Class Name</h1>
                                                    <h1 class=" font-medium">C/Y/S</h1>
                                                    <h1 class=" font-medium">Instructor</h1>
                                                </div>
                                                <div class=" flex flex-col gap-1">
                                                    <h1>:</h1>
                                                    <h1>:</h1>
                                                    <h1>:</h1>
                                                    <h1>:</h1>
                                                </div>
                                                <div class=" flex flex-col gap-1">
                                                    <h1>{{ $roomData->class_name }}</h1>
                                                    <h1>{{ $roomData->subject }}</h1>
                                                    <h1>{{ $roomData->section }}</h1>
                                                    <h1>{{ $roomData->teacher_name }}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" flex flex-col gap-4">
                                        <div class="flex gap-4">
                                            @if(!empty($getMidTerm->content))
                                                <div class=" w-[50%] relative">
                                                    @php
                                                        $isPassed = round($getMidTerm->content) >= 75;
                                                        
                                                        $bgColor = $isPassed 
                                                            ? 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-teal-100' 
                                                            : 'bg-gradient-to-br from-rose-50 via-rose-100 to-pink-100';
                                                        $textColor = $isPassed ? 'text-emerald-700' : 'text-rose-700';
                                                        $borderColor = $isPassed ? 'border-emerald-200' : 'border-rose-200';
                                                        $iconColor = $isPassed ? 'text-emerald-500' : 'text-rose-500';
                                                        $accentColor = $isPassed ? 'bg-emerald-500' : 'bg-rose-500';
                                                        $shadowColor = $isPassed ? 'shadow-emerald-200/50' : 'shadow-rose-200/50';
                                                    @endphp
                                            
                                                    <div class="relative overflow-hidden rounded-md border-2 {{ $borderColor }} {{ $bgColor }} p-4 flex-1 h-full flex flex-col justify-between {{ $shadowColor }} hover:shadow-xl transition-all duration-300">
                                                        <!-- Decorative SVG Background -->
                                                        <div class="absolute inset-0 opacity-10 pointer-events-none">
                                                            @if($isPassed)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="text-emerald-500">
                                                                    <pattern id="success-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                                        <rect x="15" y="30" width="7" height="10" fill="currentColor" opacity="0.6"/>
                                                                        <rect x="25" y="20" width="7" height="20" fill="currentColor" opacity="0.7"/>
                                                                        <rect x="35" y="10" width="7" height="30" fill="currentColor" opacity="0.8"/>
                                                                    </pattern>
                                                                    <rect width="100%" height="100%" fill="url(#success-pattern)" />
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="text-rose-500">
                                                                    <pattern id="failed-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                                        <path d="M30 10L10 30M10 10L30 30" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                                    </pattern>
                                                                    <rect width="100%" height="100%" fill="url(#failed-pattern)" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Highlight Accent Corner -->
                                                        <div class="absolute -top-10 -right-10 w-[4.5rem] h-[4.5rem] {{ $accentColor }} rotate-45 opacity-70"></div>
                                                        
                                                        <!-- Card Header -->
                                                        <div class=" flex items-center z-10">
                                                            <span class="w-1 h-7 {{ $accentColor }} rounded-full mr-3"></span>
                                                            <h1 class="font-bold text-sm uppercase tracking-wider {{ $textColor }} opacity-90">Mid Term</h1>
                                                        </div>
                                                        
                                                        <!-- Main Content -->
                                                        <div class="flex flex-col items-center z-10">
                                                            <!-- Status Icon -->
                                                            <div class="relative w-8 h-8 flex items-center justify-center">
                                                                @if($isPassed)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Pass/Fail Status -->
                                                            <div class="text-center">
                                                                <h2 class="text-2xl font-bold {{ $textColor }}">
                                                                    {{ $isPassed ? 'Passed' : 'Failed' }}
                                                                </h2>
                                                                <p class="text-xs {{ $textColor }} opacity-80 mt-1">
                                                                    {{ $isPassed ? 'Great job!' : 'Try again next time' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class=" w-[50%] rounded-md border border-sgline bg-gray-50 p-4 relative">
                                                    <div class=" flex flex-col items-center justify-center text-center">
                                                        <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <h1 class="text-gray-500 mt-2 text-sm font-medium">No Midterm Grade Available</h1>
                                                        <p class="text-gray-400 text-xs mt-1">Grade will appear here when posted</p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!empty($getFinalTerm->content))
                                                <div class=" w-[50%] relative">
                                                    @php
                                                        $isPassed = round($getFinalTerm->content) >= 75;
                                                        
                                                        $bgColor = $isPassed 
                                                            ? 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-teal-100' 
                                                            : 'bg-gradient-to-br from-rose-50 via-rose-100 to-pink-100';
                                                        $textColor = $isPassed ? 'text-emerald-700' : 'text-rose-700';
                                                        $borderColor = $isPassed ? 'border-emerald-200' : 'border-rose-200';
                                                        $iconColor = $isPassed ? 'text-emerald-500' : 'text-rose-500';
                                                        $accentColor = $isPassed ? 'bg-emerald-500' : 'bg-rose-500';
                                                        $shadowColor = $isPassed ? 'shadow-emerald-200/50' : 'shadow-rose-200/50';
                                                    @endphp
                                            
                                                    <div class="relative overflow-hidden rounded-md border-2 {{ $borderColor }} {{ $bgColor }} p-4 flex-1 h-full flex flex-col justify-between {{ $shadowColor }} hover:shadow-xl transition-all duration-300">
                                                        <!-- Decorative SVG Background -->
                                                        <div class="absolute inset-0 opacity-10 pointer-events-none">
                                                            @if($isPassed)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="text-emerald-500">
                                                                    <pattern id="success-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                                        <rect x="15" y="30" width="7" height="10" fill="currentColor" opacity="0.6"/>
                                                                        <rect x="25" y="20" width="7" height="20" fill="currentColor" opacity="0.7"/>
                                                                        <rect x="35" y="10" width="7" height="30" fill="currentColor" opacity="0.8"/>
                                                                    </pattern>
                                                                    <rect width="100%" height="100%" fill="url(#success-pattern)" />
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="text-rose-500">
                                                                    <pattern id="failed-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                                        <path d="M30 10L10 30M10 10L30 30" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                                    </pattern>
                                                                    <rect width="100%" height="100%" fill="url(#failed-pattern)" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Highlight Accent Corner -->
                                                        <div class="absolute -top-10 -right-10 w-[4.5rem] h-[4.5rem] {{ $accentColor }} rotate-45 opacity-70"></div>
                                                        
                                                        <!-- Card Header -->
                                                        <div class=" flex items-center z-10">
                                                            <span class="w-1 h-7 {{ $accentColor }} rounded-full mr-3"></span>
                                                            <h1 class="font-bold text-sm uppercase tracking-wider {{ $textColor }} opacity-90">Final Term</h1>
                                                        </div>
                                                        
                                                        <!-- Main Content -->
                                                        <div class="flex flex-col items-center z-10">
                                                            <!-- Status Icon -->
                                                            <div class="relative w-8 h-8 flex items-center justify-center">
                                                                @if($isPassed)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Pass/Fail Status -->
                                                            <div class="text-center">
                                                                <h2 class="text-2xl font-bold {{ $textColor }}">
                                                                    {{ $isPassed ? 'Passed' : 'Failed' }}
                                                                </h2>
                                                                <p class="text-xs {{ $textColor }} opacity-80 mt-1">
                                                                    {{ $isPassed ? 'Great job!' : 'Try again next time' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class=" w-[50%] rounded-md border border-sgline bg-gray-50 p-4 relative">
                                                    <div class=" flex flex-col items-center justify-center text-center">
                                                        <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <h1 class="text-gray-500 mt-2 text-sm font-medium">No Finalterm Grade Available</h1>
                                                        <p class="text-gray-400 text-xs mt-1">Grade will appear here when posted</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
    
                                        <div class="flex gap-4">
                                            @if(!empty($getMidTerm->content) && !empty($getFinalTerm->content))
                                                <div class="flex-1">
                                                    <div class=" w-full relative">
                                                        @php
                                                            $isPassed = round(((78 * .4) + (88 * .6))) >= 75;
                                                            
                                                            $bgColor = $isPassed 
                                                                ? 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-teal-100' 
                                                                : 'bg-gradient-to-br from-rose-50 via-rose-100 to-pink-100';
                                                            $textColor = $isPassed ? 'text-emerald-700' : 'text-rose-700';
                                                            $borderColor = $isPassed ? 'border-emerald-200' : 'border-rose-200';
                                                            $iconColor = $isPassed ? 'text-emerald-500' : 'text-rose-500';
                                                            $accentColor = $isPassed ? 'bg-emerald-500' : 'bg-rose-500';
                                                            $shadowColor = $isPassed ? 'shadow-emerald-200/50' : 'shadow-rose-200/50';
                                                        @endphp
                                                
                                                        <div class="relative overflow-hidden rounded-md border-2 {{ $borderColor }} {{ $bgColor }} p-4 flex-1 h-full flex justify-between {{ $shadowColor }} hover:shadow-xl transition-all duration-300">
                                                            <!-- Decorative SVG Background -->
                                                            <div class="absolute inset-0 opacity-10 pointer-events-none">
                                                                @if($isPassed)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="text-emerald-500">
                                                                        <pattern id="success-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                                            <rect x="15" y="30" width="7" height="10" fill="currentColor" opacity="0.6"/>
                                                                            <rect x="25" y="20" width="7" height="20" fill="currentColor" opacity="0.7"/>
                                                                            <rect x="35" y="10" width="7" height="30" fill="currentColor" opacity="0.8"/>
                                                                        </pattern>
                                                                        <rect width="100%" height="100%" fill="url(#success-pattern)" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="text-rose-500">
                                                                        <pattern id="failed-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                                            <path d="M30 10L10 30M10 10L30 30" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                                        </pattern>
                                                                        <rect width="100%" height="100%" fill="url(#failed-pattern)" />
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Highlight Accent Corner -->
                                                            <div class="absolute -top-10 -right-10 w-[4.5rem] h-[4.5rem] {{ $accentColor }} rotate-45 opacity-70"></div>
                                                            
                                                            <!-- Card Header -->
                                                            <div class=" flex items-center z-10">
                                                                <span class="w-1 h-7 {{ $accentColor }} rounded-full mr-3"></span>
                                                                <h1 class="font-bold text-sm uppercase tracking-wider {{ $textColor }} opacity-90">Final Term</h1>
                                                            </div>
                                                            
                                                            <!-- Main Content -->
                                                            <div class="flex flex-col items-center z-10 mr-4">
                                                                <!-- Status Icon -->
                                                                <div class="relative w-8 h-8 flex items-center justify-center">
                                                                    @if($isPassed)
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                    @else
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                
                                                                <!-- Pass/Fail Status -->
                                                                <div class="text-center">
                                                                    <h2 class="text-2xl font-bold {{ $textColor }}">
                                                                        {{ $isPassed ? 'Passed' : 'Failed' }}
                                                                    </h2>
                                                                    <p class="text-xs {{ $textColor }} opacity-80 mt-1">
                                                                        {{ $isPassed ? 'Great job!' : 'Try again next time' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex-1 rounded-md border border-sgline p-4 bg-gray-50">
                                                    <div class=" flex flex-col items-center justify-center">
                                                        <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <h1 class="text-gray-500 mt-2 text-sm font-medium">No Final Grade Available</h1>
                                                        <p class="text-gray-400 text-xs mt-1">Grade will appear here when posted</p>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Table Section -->
                        <div id="tableSection" class="hidden w-full h-full">
                            <div class="h-full w-full flex justify-center gap-8 sm:rounded-xl sm:bg-bgcolor sm:border border-sgline overflow-y-auto overflow-scrolls">
                                <div class="whitespace-nowrap overflow-x-auto pb-4">
                                    
                                    @if($skills->isEmpty()) 
                                        <div class="flex flex-col items-center justify-center py-6 px-4">
                                            <i class='bx bx-table text-4xl text-gray-300 mb-2'></i>
                                            <h1 class="text-lg font-medium text-gray-600">No Records Yet!</h1>
                                        </div>
                                    @else 
                                        @php
                                            $rowspanTrack = [];
                                            $existCol = [];
                                        @endphp
                        
                                        @if(!empty($notif))
                                            <div class="text-center py-2 px-4 mb-3 text-blue-600 bg-blue-50 border-b border-blue-100">
                                                {{$notif}}
                                            </div>
                                        @endif
                                        
                                        <table class="bg-bgcolor text-sm border-collapse shadow-md mt-10">
                                            <thead>
                                                @if (!$organized['organizedRows'])
                                                    @php $maxCol = $organized['maxColIndex'] + 1; @endphp
                                                    <tr>
                                                        <td class="border border-sgline text-center py-3 px-4 font-semibold bg-gray-50" colspan="{{ $maxCol }}">
                                                            <div class="flex items-center justify-center space-x-2">
                                                                <i class='bx bx-info-circle text-gray-400'></i>
                                                                <h1 class="animate-pulse">The teacher hasn't provided the table headers yet.</h1>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @else
                                                    @php
                                                        // Group rows by their row number
                                                        $groupedRows = [];
                                                        foreach ($organized['organizedRows'] as $row) {
                                                            $groupedRows[$row->row][] = $row;
                                                        }
                                                    
                                                        // Find the first row with content
                                                        $firstRowWithContent = null;
                                                        foreach ($groupedRows as $rowNumber => $rows) {
                                                            if (!empty($rows)) {
                                                                $firstRowWithContent = $rowNumber;
                                                                break;
                                                            }
                                                        }
                                                    
                                                        // If no row with content is found, start from the first row
                                                        if ($firstRowWithContent === null) {
                                                            $firstRowWithContent = 1;
                                                        }
                                                    
                                                        $maxRow = max(array_keys($groupedRows));
                                                    
                                                        // Start from the first row with content
                                                        for ($i = $firstRowWithContent; $i <= $maxRow; $i++) {
                                                            if (!isset($groupedRows[$i])) {
                                                                $groupedRows[$i] = []; 
                                                            }
                                                        }
                                                    
                                                        ksort($groupedRows);
                                                    @endphp
                                                    @php
                                                        $midColumns = [];
                                                        $finalColumns = [];
                                                     @endphp
                                                    @foreach ($groupedRows as $rowKey => $rows)
                                                        <tr class="bg-gray-50">
                                                            @php $colIndex = 0; @endphp
                                                            @while ($colIndex <= $organized['maxColIndex'])
                                                                @php
                                                                    $column = $sequences[$colIndex];
                                                                    $rowData = collect($rows)->firstWhere('column', $column);
                                                                
                                                                    // Check for rowspan tracking
                                                                    if (isset($rowspanTrack[$column]) && $rowspanTrack[$column] > 0) {
                                                                        $rowspanTrack[$column]--;
                                                                        $colIndex++;
                                                                        continue;
                                                                    }
                        
                                                                    $rowspan = $rowData->rowspan ?? 1;
                                                                    $colspan = $rowData->colspan ?? 1;
                        
                        
                                                                    if($rowData){
                                                                        $existCol[] = $rowData->column;
                                                                        $isDone = in_array($existCol, $doneCheck);
                                                                        $text = $isDone ? 'Absent' : 'Not yet recorded';
                                                                    }
                                                                @endphp
                                                            
                                                                @if ($rowData)
                                                                    <th column="{{ $rowData->column ?? '' }}" rowspan="{{ $rowspan }}" colspan="{{ $colspan }}" 
                                                                        class="text-center px-5 py-2 border border-sgline font-medium text-mainText">
                                                                        {{ $rowData->content ?? '' }}
                                                                    </th>
                                                                    @php
                                                                    
                                                                    // Track rowspan
                                                                        if ($rowspan > 1) {
                                                                            for ($i = 0; $i < $colspan; $i++) {
                                                                                $rowspanTrack[$sequences[$colIndex + $i]] = $rowspan - 1;
                                                                            }
                                                                        }
                                                                        $content = strtolower($rowData->content ?? '');
        
                                                                        // Check for mid-term related keywords
                                                                        if (str_contains($content, 'mid') || str_contains($content, 'midterm') || str_contains($content, 'mid-term')) {
                                                                            $midColumns[] = $rowData->column;
                                                                        }
                                                                        
                                                                        // Check for final-term related keywords
                                                                        if (str_contains($content, 'final') || str_contains($content, 'finalterm') || str_contains($content, 'final-term')) {
                                                                            $finalColumns[] = $rowData->column;
                                                                        }
                                                                        
                                                                        $colIndex += $colspan;
                                                                        
                                                                    @endphp
                        
                                                                @else
                                                                    <th class="text-center py-2 border border-sgline"></th>
                                                                    @php $colIndex++; @endphp
                                                                @endif
                                                            @endwhile
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </thead>
                                        
                                            <tbody class="h-[10rem]">
                                                <tr class="hover:bg-gray-50">
                                                    @php 
                                                        $colIndex = 0; 
                                                        $nameColCount = $nameAsStudent->name_2 ? 3 : 2;
                                                        $names = [$nameAsStudent->name_1 ?? '#', $nameAsStudent->name_2 ?? $nameAsStudent->name_3, $nameAsStudent->name_3];
                                                    @endphp
                                                    
                                                    @while ($colIndex <= $organized['maxColIndex'])
                                                        @if ($colIndex < $nameColCount) 
                                                            <td class="text-center border border-sgline px-3 py-5 font-medium"> 
                                                                {{$names[$colIndex] ?? ''}} 
                                                            </td>
                                                            @php
                                                            $colIndex++;
                                                            continue;
                                                            @endphp
                                                        @endif
                                                        @php
                                                            $column = $sequences[$colIndex];
                                                            // Handle first three columns with names
                                                
                                                            // Check if rowspan is set and skip this column if necessary
                                                            if (isset($rowspanTrack[$column]) && $rowspanTrack[$column] > 0) {
                                                                $rowspanTrack[$column]--;
                                                                $colIndex++;
                                                                continue;
                                                            }
                                                            // Retrieve the cell content if available
                                                            $cell = $organized['organizedData'][$column] ?? null;
                                                        @endphp
                                                        
                                                        {{-- Render cell for remaining columns --}}
                                                        @if ($column)
                                                            <td class="text-center border border-sgline px-5 py-5">
                                                                @if (!empty($cell) && is_numeric($cell->content) && (in_array($column, $finalColumns) || in_array($column, $midColumns)))
                                                                    @php
                                                                        $isPassed = round($cell->content) >= 75;
                                                                        $statusClass = $isPassed ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700';
                                                                    @endphp
                                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-sm font-medium {{ $statusClass }}">
                                                                        {{ $isPassed ? 'Passed' : 'Failed' }}
                                                                    </span>
                                                                @elseif (!empty($cell) && is_numeric($cell->content))
                                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                                                                        {{ round($cell->content) }}
                                                                    </span>
                                                                @else
                                                                    {{ $cell->content ?? '' }}
                                                                @endif
                                                            </td>
                                                        @endif
                                                
                                                        @php $colIndex++; @endphp
                                                    @endwhile
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>

                    
                    </div>                

                </div>
            </div>
        </section>

    </section>

    <script>
        const toggleButton = document.getElementById("toggleButton");
        const chartTab = document.getElementById("chartTab");
        const tableTab = document.getElementById("tableTab");
        const activeIndicator = document.getElementById("activeIndicator");

        let isChartActive = true;

        chartTab.addEventListener("click", function() {
            if (!isChartActive) {
                activeIndicator.style.transform = "translateX(0)";
                tableTab.classList.add("text-gray-700");
                tableTab.classList.remove("text-white");
                
                chartTab.classList.add("text-white");
                chartTab.classList.remove("text-gray-700");
                
                isChartActive = true;
                
                document.getElementById("chartSection").classList.remove("hidden");
                document.getElementById("chartSection").classList.add("flex");
                document.getElementById("tableSection").classList.add("hidden");
                document.getElementById("tableSection").classList.remove("flex");
            }
        });

        tableTab.addEventListener("click", function() {
            if (isChartActive) {
                activeIndicator.style.transform = "translateX(100%)";
                chartTab.classList.add("text-gray-700");
                chartTab.classList.remove("text-white");
                
                tableTab.classList.add("text-white");
                tableTab.classList.remove("text-gray-700");
                
                isChartActive = false;
                
                document.getElementById("tableSection").classList.remove("hidden");
                document.getElementById("tableSection").classList.add("flex");
                document.getElementById("chartSection").classList.add("hidden");
                document.getElementById("chartSection").classList.remove("flex");
            }
        });

        const menuBar = document.getElementById('menu-bar');
        const closeBar = document.getElementById('close-bar');
        const showBar = document.getElementById('show-bar');

        menuBar.addEventListener('click', () => {
            showBar.classList.remove('opacity-0', 'pointer-events-none'); 
            showBar.classList.remove('w-0');
            showBar.classList.add('w-[80%]');
        });

        function closeSideBar() {
            showBar.classList.remove('w-[80%]');
            showBar.classList.add('w-0');

            showBar.classList.add('opacity-0', 'pointer-events-none');
        }

        closeBar.addEventListener('click', closeSideBar);

        document.addEventListener('click', (event) => {
            if (!showBar.contains(event.target) && !menuBar.contains(event.target)) {
                closeSideBar();
            }
        });


    </script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
