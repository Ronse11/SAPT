<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Track Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/users.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen overflow-hidden">

        {{-- Body --}}
        <section class="relative w-full h-full flex md:px-14 px-3">
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
                                <a href="{{ route('studentRecord.room', [ 'id' => $encodedId, 'key' => 'main-table']) }}" class=" flex items-center text-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                                    <i class="bx bxs-book-alt text-lg"></i>
                                    <span >Records</span>
                                </a>
                            </div>
                        </div>
            
                        <a href="{{ route('studentAttendance.room', [ 'id' => $encodedId, 'key' => 'attendance-table']) }}" class="w-full flex items-center bg-mainText text-bgcolor font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class='bx bxs-check-square text-lg'></i>            
                            Attendance
                        </a>
                    </div>
                </div>
            </div>
            

              

            <div class="bg-gray-50 rounded-2xl px-4 pb-6 w-60 cp:flex hidden flex-col justify-between border border-sgline my-8">

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
                                <a href="{{ route('studentRecord.room', [ 'id' => $encodedId, 'key' => 'main-table']) }}" class=" flex items-center text-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                                    <i class="bx bxs-book-alt text-lg"></i>
                                    <span >Records</span>
                                </a>
                            </div>
                        </div>
    
                        <a href="{{ route('studentAttendance.room', [ 'id' => $encodedId, 'key' => 'attendance-table']) }}" class="w-full flex items-center  bg-mainText text-bgcolor font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
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

            <div class="  flex-1 overflow-y-auto overflow-scrolls py-4">
                {{-- overflow-y-auto overflow-scrolls --}}

                <div class=" w-full h-full flex flex-col gap-10 md:px-8 px-4 rounded-3xl">


                    <div class=" md:h-20 flex items-center justify-between md:px-8">
                        <h1 class=" md:text-3xl text-2xl font-bold text-mainText">Attendance</h1>
                        <div id="menu-bar" class="md:hidden cursor-pointer">
                            <i class='text-4xl bx bx-menu'></i>
                        </div>
                    </div>

                    {{-- <div class=" cp:hidden w-full text-center">
                        <h1 class=" text-3xl font-bold text-mainText">Records</h1>
                    </div> --}}


                    <div class=" flex-1 flex flex-col">
                    
                        <!-- Table Section -->
                        <div class="mt-4 sm:h-[24rem] h-full">
                            <div class=" h-full flex justify-center gap-8 rounded-xl sm:p-4">
                                <div class=" whitespace-nowrap overflow-x-auto py-8">
                    
                                @if($skills->isEmpty()) 
                                    <h1>No Records Yet</h1>
                                @else 
                                @php
                                    $nextSequence = function($index) {
                                        if ($index < 26) {
                                            return chr($index + ord('A'));
                                        } else {
                                            $index -= 26;
                                            $firstChar = chr(floor($index / 26) + ord('A'));
                                            $secondChar = chr(($index % 26) + ord('A'));
                                            return $firstChar . $secondChar;
                                        }
                                    };
                                
                                    $sequences = [];
                                    for ($i = 0; $i <= 104; $i++) {  // Adjust the count as needed
                                        $sequences[] = $nextSequence($i);
                                    }
                                
                                    $organizedData = [];
                                    foreach ($skills as $cell) {
                                        $organizedData[$cell->column] = $cell;
                                    }
                    
                                    $organizedRows = [];
                                    // Here we are assuming $startRow is a collection of rows
                                    foreach ($startRow as $row) {
                                        $organizedRows[] = $row;
                                    }
                                
                                    // Determine the maximum column index that has content
                                    $maxColIndex = 0;
                                    foreach ($organizedRows as $row) {
                                        $column = $row->column ?? null;
                                        if ($column !== null) {
                                            $index = array_search($column, $sequences);
                                            if ($index !== false && $index > $maxColIndex) {
                                                $maxColIndex = $index;
                                            }
                                        }
                                    }
                    
                                    foreach ($organizedData as $column => $cell) {
                                        $index = array_search($column, $sequences);
                                        if ($index !== false && $index > $maxColIndex) {
                                            $maxColIndex = $index;
                                        }
                                    }
                    
                                    $rowspanTrack = [];
                                @endphp
                                    
                                    <table class="w-full bg-white text-sm border-collapse shadow-md rounded-lg overflow-hidden">
                                        <thead class="bg-gray-50">
                                            @if (!$organizedRows)
                                                @php $maxCol = $maxColIndex + 1; @endphp
                                                <tr>
                                                    <td class="border border-sgline text-center py-3 px-4 font-semibold text-gray-600" colspan="{{ $maxCol }}">
                                                        <h1 class="animate-side flex items-center justify-center">
                                                            <i class='bx bx-info-circle mr-2 text-gray-400'></i>
                                                            The teacher hasn't provided the table headers yet.
                                                        </h1>
                                                    </td>
                                                </tr>
                                            @else
                                            <!-- Display each row from $startRow separately -->
                                            @php
                                                // Group rows by their row number
                                                $groupedRows = [];
                                                foreach ($organizedRows as $row) {
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
                                                <tr class="bg-gray-50 text-mainText hover:bg-opacity-95 transition-colors">
                                                    @php $colIndex = 0; @endphp
                                                    @while ($colIndex <= $maxColIndex)
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
                                                            <th column="{{ $rowData->column ?? '' }}" rowspan="{{ $rowspan }}" colspan="{{ $colspan }}" class="text-center px-10 py-3 border border-sgline font-medium">
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
                                    
                                        <tbody class="h-[10rem] divide-y divide-gray-100">
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                @php 
                                                    $colIndex = 0; 
                                                    $nameColCount = $nameAsStudent->name_2 ? 3 : 2;
                                                    $names = [$nameAsStudent->name_1 ?? '#', $nameAsStudent->name_2 ?? $nameAsStudent->name_3, $nameAsStudent->name_3];
                                                @endphp
                                                
                                                @while ($colIndex <= $maxColIndex)
                                                    @if ($colIndex < $nameColCount) 
                                                        <td class="text-center border border-sgline px-8 py-3 font-medium text-gray-700"> {{$names[$colIndex] ?? ''}} </td>
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
                                                        $cell = $organizedData[$column] ?? null;
                                    
                                                        // $isDone = in_array($column, $doneCheck);
                                                        // $text = $isDone ? 'Absent' : 'Not yet recorded';
                                                    @endphp
                                    
                                                    {{-- Render cell for remaining columns --}}
                                                    @if ($column)
                                                    <td class="text-center border border-sgline px-10 py-3">
                                                        @if(!empty($cell->content))
                                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                                                                {{ $cell->content }}
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





              