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
    <section class=" w-screen h-screen grid grid-cols-6 grid-rows-8 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 tablet:gap-y-4 bg-bgcolor relative">

        {{-- Header --}}
        <header class=" w-screen row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
            {{-- <div class="flex gap-4 items-center">
                <button id="menu-bar" class=" text-4xl pt-1 cp:text-3xl"><i
                        class='bx bx-menu text-mainText hover:text-subtText'></i></button>
                <h1 class=" text-3xl font-medium select-none tracking-widest text-mainText cp:text-2xl">SAPT</h1>
            </div> --}}
            <div class=" flex items-center pr-6">
                <a href=" {{ route('student.room', $encodedId) }} " class=" h-14 w-14 grid place-content-center rounded-full cp:text-3xl">
                    <i class="bx bx-arrow-back text-4xl text-subtText hover:text-mainText"></i>
                </a>
            </div>
            <div class="flex gap-4 relative items-center">
                <button class="grid place-items-center rounded-full w-11 h-11 text-mainText cp:text-2xl cursor-pointer hover:text-subtText">
                    <i class='bx bxs-cog text-3xl font-weight-bolder'></i>
                </button>
                {{-- <div class="choices bg-bgcolor hidden absolute left-0 -ml-[9rem] mt-9 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <a href=" {{ route('track') }} " class="text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Track Record</a>
                        <a href=" {{ route('create') }} " class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2">Create Record</a>
                    </div>
                </div> --}}
                <button class="btn-user text-3xl w-9 h-9 rounded-full cp:text-2xl bg-mainText">
                    <h1 class=" text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                </button>
                <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                    <div class="flex flex-col">
                        <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Body --}}
        <section class=" row-start-2 row-span-7 col-start-1 col-span-6 px-4 tablet:pb-8 grid grid-cols-8 grid-rows-8 tablet:col-start-1 tablet:col-span-16 tablet:px-16 tablet:row-start-2 tablet:row-span-7 cp:col-start-2 cp:col-span-10 small-bp">
            <div class=" row-start-1 row-span-8 col-start-1 col-span-8 overflow-y-auto overflow-scrolls">
                <div class=" w-full h-full flex flex-col gap-8 px-8 py-8 rounded-3xl">
                    <div class="text-center">
                        <h1 class=" text-4xl font-semibold">RECORDS</h1>
                    </div>
                    <div class="h-[55%] flex gap-8 rounded-xl p-4">
                        <div class="flex-1 overflow-x-auto whitespace-nowrap">
                            @php
                                $isDone = [];
                            @endphp

                            @if($skills->isEmpty() ) 
                                <h1>No Records Yet!</h1>
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
                    
                                <table class=" h-full">
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
            </div>
        </section>

    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
