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
        <section class=" w-full h-full flex p-8">

            <div class="bg-slate-100 rounded-2xl px-4 pb-6 w-60 flex flex-col justify-between border-[3px] border-sgline">

                <div>
                    <div class="h-20 flex items-center mb-4 border-b-[2px] border-sgline">
                        <img class=" w-8 h-8 mr-1" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo">
                        <h1 class=" pt-3 text-xl font-normal text-black select-none tracking-widest cp:text-3xl">APT</h1>
                    </div>
    
                    <div class=" flex flex-col gap-3">
                        <a href="{{ route('student-home') }}"  class=" flex items-center text-gray-400 font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class="bx bxs-home text-lg"></i>
                            <span >Home</span>
                        </a>
                        <div>
                            <div class="">
                                <a href="{{ route('studentRecord.room', [ 'id' => $encodedId, 'key' => 'main-table']) }}" class="records-btn w-full flex items-center text-gray-400 font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                                    <i class="bx bxs-book-alt text-lg"></i>              
                                    Records
                                </a>
                            </div>
                        </div>
    
                        <button id="attendanceTab" class="w-full flex items-center text-bgcolor bg-mainText font-medium text-[14px] px-4 py-2 rounded-full gap-3 hover:bg-mainText hover:text-bgcolor">
                            <i class='bx bxs-check-square text-lg'></i>            
                            Attendance
                        </button>
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

                <div class=" w-full h-full flex flex-col gap-8 px-8 rounded-3xl">


                    <div class=" h-20 flex items-center justify-between bg-slate-50 px-8 rounded-2xl ">
                        <h1 class=" text-3xl font-bold">Attendance</h1>
                    </div>


                    <div class=" flex-1 flex flex-col bg-slate-100 rounded-2xl">
                    
                        <!-- Table Section -->
                        <div class="mt-4">
                            <div class="h-[24rem] flex justify-center gap-8 rounded-xl p-4">
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
                                    
                                    <table class=" bg-white">
                                        <thead>
    
                                            @if (!$organizedRows)
                                                @php $maxCol = $maxColIndex + 1; @endphp
    
                                                <tr>
                                                    <td class=" border border-sgline text-center py-2 px-4 font-semibold" colspan="{{ $maxCol }}">
                                                        <h1 class=" animate-side">The teacher hasn't provided the table headers yet.</h1>
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
                                                <tr>
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
                                                    $names = [$nameAsStudent->name_1 ?? '#', $nameAsStudent->name_2 ?? '', $nameAsStudent->name_3];
                                                @endphp
                                                
                                                @while ($colIndex <= $maxColIndex)
                                                    @if ($colIndex < 3) 
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
                                                        $cell = $organizedData[$column] ?? null;
    
                                                        $isDone = in_array($column, $doneCheck);
                                                        $text = $isDone ? 'Absent' : 'Not yet recorded';
                                                    @endphp
                                        
                                                    {{-- Render cell for remaining columns --}}
                                                    @if ($column)
                                                        <td class="text-center border border-sgline px-10">
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
                    
                    </div>                

                </div>
            </div>
        </section>

    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
