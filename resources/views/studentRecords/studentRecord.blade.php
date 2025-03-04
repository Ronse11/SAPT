<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Track Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/users.js', 'resources/js/studentRecords/records.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen">

        {{-- Header --}}
        {{-- <header class=" w-screen row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
            <div class=" flex items-center pr-6">
                <a href=" {{ route('student.room', $encodedId) }} " class=" h-14 w-14 grid place-content-center rounded-full cp:text-3xl">
                    <i class="bx bx-arrow-back text-4xl text-subtText hover:text-mainText"></i>
                </a>
            </div>
            <div class="flex gap-4 relative items-center">
                <button class="grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:text-subtText">
                    <i class='bx bxs-cog text-3xl font-weight-bolder'></i>
                </button>
                <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText">
                    <h1 class=" text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                </button>
                <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                    </div>
                </div>
            </div>
        </header> --}}

        {{-- Body --}}
        <section class=" w-full h-full flex px-14 py-8">

            <div class="bg-gray-50 rounded-2xl px-4 pb-6 w-60 flex flex-col justify-between border border-sgline">

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
                            {{-- <div id="homeSubmenu" class="">
                                <div class="flex flex-col items-start pl-8 font-semibold">
                                    <button id="chartTab" class="flex items-center gap-2 w-full p-2 text-purple-600 focus:outline-none">
                                        <i class='bx bxs-doughnut-chart' ></i>
                                        Chart
                                    </button>
                                    <button id="tableTab" class="flex items-center gap-2 w-full p-2 text-gray-600 focus:outline-none">
                                        <i class='bx bxs-square'></i>
                                        Table
                                    </button>
                                </div>
                            </div> --}}
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

            <div class="  flex-1 overflow-y-auto overflow-scrolls">
                {{-- overflow-y-auto overflow-scrolls --}}

                <div class=" w-full h-full flex flex-col gap-4 px-8 rounded-3xl">


                    <div class=" h-20 flex items-center justify-between px-8 rounded-2xl border border-sgline">
                        <h1 class=" text-3xl font-bold text-mainText">Records</h1>
                        <div>
                            {{-- <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText">
                                <h1 class=" text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                            </button>
                            <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                                <div class="flex flex-col">
                                    <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                                </div>
                            </div> --}}

                            <button id="toggleButton" 
                            class="relative w-44 h-10 bg-gray-200 rounded-full flex items-center transition-all duration-300 ease-in-out">
                            <!-- Active Indicator -->
                            <span id="activeIndicator" 
                                class="absolute left-1 w-[48%] h-8 bg-mainText rounded-full transition-all duration-300 ease-in-out"></span>
                            
                            <!-- Chart Tab (Default Active) -->
                            <span id="chartTab" 
                                class="relative w-1/2 h-full flex items-center justify-center text-white font-semibold transition-all duration-300 ease-in-out z-10">
                                <i class='bx bxs-doughnut-chart' ></i>
                                <h3 class=" text-[.8rem] ml-1 font-light">Chart</h3>
                            </span>
                            <!-- Table Tab -->
                            <span id="tableTab" 
                                class="relative w-1/2 h-full flex items-center justify-center text-gray-700 font-semibold transition-all duration-300 ease-in-out z-10">
                                <i class='bx bxs-square'></i>
                                <h3 class=" text-[.8rem] ml-1 font-light">Table</h3>
                            </span>
                        </button>

                        </div>
                    </div>


                    <div class=" flex-1 flex flex-col">
                        <!-- Chart Section (Initially Hidden) -->
                        <div id="chartSection" class="flex-1 flex flex-col">
                            <div id="quizTabsContainer" class="flex-1 flex flex-col gap-4 ">
                                <!-- Tabs -->
                                <div id="quizTabs" class="flex space-x-2 py-4 px-8 rounded-md">
                                    @php 
                                        $groupedData = [];
                                        $currentGroup = null;
                                    @endphp
                            
                                    @foreach ($quizzesColumns['processedColumns'] as $column => $data)
                                        @if ($data['quizzes']->isNotEmpty())
                                            @if ($currentGroup)
                                                @php $groupedData[] = $currentGroup; @endphp
                                            @endif
                                            @php 
                                                $currentGroup = ['quizzes' => $data['quizzes'], 'items' => []]; 
                                            @endphp
                                        @endif
                            
                                        @if ($currentGroup)
                                            @php
                                                $currentGroup['items'][] = [
                                                    'date' => $data['date'][0]->content ?? '',
                                                    'scores' => $data['scores'][0]->content ?? 0,
                                                    'total' => $data['total'][0]->content ?? 1
                                                ];
                                            @endphp
                                        @endif
                                    @endforeach
                            
                                    @if ($currentGroup)
                                        @php $groupedData[] = $currentGroup; @endphp
                                    @endif
                            
                                    @foreach ($groupedData as $index => $group)
                                        @php
                                            // Get the first quiz name as the tab title
                                            $quizName = $group['quizzes'][0]->content ?? 'Unnamed Quiz';
                                        @endphp
                                        <div class="flex space-x-4">
                                            <button class=" w-28 text-center py-2 px-4 rounded-full text-[.8rem] font-medium hover:bg-mainText hover:text-bgcolor focus:outline-none {{ $index === 0 ? ' bg-mainText text-bgcolor' : '' }}"
                                                    data-tab="{{ Str::slug($quizName) }}">
                                                {{ $quizName }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            
                                <!-- Tab Content -->
                                <div class="flex-1 p-8  border border-sgline rounded-2xl">

                                    <div id="quizContent" class="">
                                        @foreach ($groupedData as $index => $group)
                                            @php
                                                $quizName = $group['quizzes'][0]->content ?? 'Unnamed Quiz';
                                                $quizSlug = Str::slug($quizName); // Slugify for unique tab IDs
                                            @endphp
                                            <div class="grid gap-6 {{ $index === 0 ? '' : 'hidden' }}"  
                                            style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));"  
                                            data-content="{{ $quizSlug }}">

                                            @php
                                                $gradients = [
                                                    'from-[#E3F2FD] to-[#1E3A8A]', // Pale Blue to Deep Blue
                                                    'from-[#E8F5E9] to-[#2E7D32]', // Pale Green to Forest Green
                                                    'from-[#FFF9C4] to-[#FBC02D]', // Pale Yellow to Gold
                                                    'from-[#FFE8D5] to-[#E65100]', // Pale Peach to Muted Orange
                                                    'from-[#F3E5F5] to-[#6A1B9A]', // Pale Purple to Deep Purple
                                                ];
                                            @endphp
                                            
                                            @foreach ($group['items'] as $index => $item)
                                                @php
                                                    $gradient = $gradients[array_rand($gradients)];
                                                @endphp
                                                <div class="p-6 rounded bg-gradient-to-br {{ $gradient }} shadow-md flex justify-between">
                                                    <div class="flex-1">
                                                        <h1 class="text-xl font-semibold">Date:</h1>
                                                        <h1 class="text-base mt-3 ml-2">{{ $item['date'] }}</h1>
                                                    </div>
                                                    <div class="relative w-28 h-28 flex items-center justify-center shadow-md border rounded-full bg-bgcolor">
                                                        @php
                                                            $score = $item['scores'];
                                                            $total = $item['total'];
                                                            $percentage = ($total > 0) ? ($score / $total) * 100 : 0;
                                                            $dashOffset = 283 - ($percentage / 100) * 283;
                                                        @endphp
                                            
                                                        <div class="progress-container" data-score="{{ $score }}" data-total="{{ $total }}">
                                                            <div class="progress-circle"></div>
                                                            <div class="progress-text">
                                                                <span class="score">{{ $score }}</span> 
                                                                <span class="divider">/</span> 
                                                                <span class="total">{{ $total }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        
                                            </div>

    
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Table Section -->
                        <div id="tableSection" class="mt-4 hidden">
                            <div class="h-[24rem] flex justify-center gap-8 rounded-xl p-4">
                                <div class=" whitespace-nowrap overflow-x-auto py-8">
        
                                    @if($skills->isEmpty() ) 
                                        <h1>No Records Yet!</h1>
                                    @else 
                                        @php
                                            $rowspanTrack = [];
                                        @endphp
        
                                        <h1>{{$notif}}</h1>
                                        <table class=" h-full">
                                            <thead>
        
                                                @if (!$organized['organizedRows'])
                                                    @php $maxCol = $organized['maxColIndex'] + 1; @endphp
        
                                                    <tr>
                                                        <td class=" border border-sgline text-center py-2 px-4 font-semibold" colspan="{{ $maxCol }}">
                                                            <h1 class=" animate-side">The teacher hasn't provided the table headers yet.</h1>
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
                                                
                                                @foreach ($groupedRows as $rowKey => $rows)
                                                    <tr>
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
                                                            @endphp
        
                                                
                                                            @if ($rowData)
                                                                <th column="{{ $rowData->column ?? '' }}" rowspan="{{ $rowspan }}" colspan="{{ $colspan }}" class="text-center px-10 py-1 border border-sgline">
                                                                    {{ $rowData->content ?? '' }}
                                                                </th>
                                                                @php
                                                                
                                                                // Track rowspan
                                                                    if ($rowspan > 1) {
                                                                        for ($i = 0; $i < $colspan; $i++) {
                                                                            $rowspanTrack[$sequences[$colIndex + $i]] = $rowspan - 1;
                                                                        }
                                                                    }
                                                                    $colIndex += $colspan;
                                                                    
                                                                    
                                                                @endphp
        
                                                            @else
                                                                <th class="text-center py-1 border border-sgline"></th>
                                                                @php $colIndex++; @endphp
                                                            @endif
                                                        @endwhile
                                                    </tr>
        
                                                @endforeach
        
                                                @endif
                                            
                                            </thead>
                            
                                            <tbody>
                                                <tr>
                                                    @php 
                                                        $colIndex = 0; 
                                                        $nameColCount = $nameAsStudent->name_2 ? 3 : 2;
                                                        $names = [$nameAsStudent->name_1 ?? '#', $nameAsStudent->name_2 ?? $nameAsStudent->name_3, $nameAsStudent->name_3];
                                                    @endphp
                                                    
                                                    @while ($colIndex <= $organized['maxColIndex'])
                                                        @if ($colIndex < $nameColCount) 
                                                            <td class="text-center border border-sgline px-8"> {{$names[$colIndex] ?? ''}} </td>
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
                                                            
                                                            $isDone = in_array($column, $doneCheck);
                                                            $text = $isDone ? 'Absent' : 'Not yet recorded';
                                                        @endphp
                                                        
                                                        {{-- Render cell for remaining columns --}}
                                                        @if ($column)
                                                            <td class="text-center border border-sgline px-10" >
                                                                {{ $cell->content ?? $text }}
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

                        {{-- <div id="attendanceSection" class="mt-4 hidden">
                            <div class="h-[24rem] flex justify-center gap-8 rounded-xl p-4">
                                <div class=" whitespace-nowrap overflow-x-auto py-8">
                                    @include('studentRecords.studentAttendance')
                                </div>
                            </div>
                        </div> --}}
                    
                    </div>                

                </div>
            </div>
        </section>

    </section>

    <script>
        let isChartActive = true;
    
        document.getElementById("toggleButton").addEventListener("click", function() {
            let activeIndicator = document.getElementById("activeIndicator");
            let chartTab = document.getElementById("chartTab");
            let tableTab = document.getElementById("tableTab");
    
            if (isChartActive) {
                activeIndicator.style.transform = "translateX(100%)";
                chartTab.classList.add("text-gray-700");
                chartTab.classList.remove("text-white");
    
                tableTab.classList.add("text-white");
                tableTab.classList.remove("text-gray-700");
            } else {
                activeIndicator.style.transform = "translateX(0)";
                tableTab.classList.add("text-gray-700");
                tableTab.classList.remove("text-white");
    
                chartTab.classList.add("text-white");
                chartTab.classList.remove("text-gray-700");
            }
    
            isChartActive = !isChartActive;
        });
    </script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
