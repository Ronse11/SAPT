<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Class Record</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/table.css', 'resources/js/app.js', 'resources/js/teacherTable/sample.js', 'resources/js/teacherTable/merging.js', 'resources/js/teacherTable/applyBorder.js', 'resources/js/teacherTable/addStudentsNames.js', 'resources/js/teacherTable/applyFontStyle.js', 'resources/js/teacherTable/applyColors.js', 'resources/js/teacherTable/calculation.js', 'resources/js/teacherTable/logic.js', 'resources/js/teacherTable/colorLogic.js', 'resources/js/teacherTable/dragSum.js', 'resources/js/teacherTable/adjustFontSize.js', 'resources/js/teacherTable/doneCheck.js', 'resources/js/imports/import.js', 'resources/js/imports/export.js', 'resources/js/print/print.js', 'resources/js/teacherTable/tableTab.js', 'resources/js/teacherTable/ratingTable.js', 'resources/js/print/printGrades.js', 'resources/js/teacherTable/formula.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <section class=" w-screen h-screen relative grid grid-cols-6 grid-rows-15 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 bg-bgcolor overflow-hidden">

        @php 
            $totalColumn = $totalRowCol ? $totalRowCol->total_column : 40;
            $totalRow = $totalRowCol ? $totalRowCol->total_row : 40;

            $endingRow = 0;
            if($selectedRow) {
                $selected = $selectedRow->row;
                $colCount = $selectedRow->column;
                $numberOfStudents = $counts;
                $endingRow = $selected + $numberOfStudents - 1;
            } else {
                $numberOfStudents = 0;
                $selected = 0;
                $colCount = 0;
            }

            $allName2Null = collect($names)->every(fn($name) => empty($name->name_2));
            $nameCounter = 1;
            $name1Counter = 1;
            $name2Counter = 1;

        @endphp

        <div id="success-alert"
            class="fixed inset-0 grid place-content-center z-50 opacity-0 transition-all duration-500 ease-in-out pointer-events-none">
            <div class=" flex justify-center transition-all duration-500 ease-in-out pointer-events-none">
                <h1 class="text-red-500 px-4 py-2 rounded-sm text-sm text-center bg-bgcolor shadow-md ">
                    This feature is not available yet. Stay tuned!
                </h1>
            </div>
        </div>

        {{-- SUCCESS APPLYING OF BUTTONS --}}
        <div id="applied" class="absolute right-7 top-[13vh] flex items-center justify-center z-50 transition-all duration-500 ease-in-out opacity-0 pointer-events-none transform -translate-x-[-15rem]">
            <div class="p-4 bg-green-50 rounded-sm border border-green-300 shadow-lg max-w-sm w-full pointer-events-none">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <h1 class="message text-green-500 text-sm text-center">
                    </h1>
                </div>
            </div>
        </div>

        {{-- ERROR APPLYING --}}
        <div id="appliedError" class="absolute right-7 top-[13vh] flex items-center justify-center z-50 transition-all duration-500 ease-in-out opacity-0 pointer-events-none transform -translate-x-[-15rem]">
            <div class="p-4 bg-red-50 rounded-sm border border-red-300 shadow-lg max-w-sm w-full pointer-events-none">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <h1 class="messageError text-red-500 text-sm text-center">
                    </h1>
                </div>
            </div>
        </div>

        {{-- SUCCESS --}}
        <div id="success-changeName" class="absolute inset-0 flex items-center justify-center z-50 transition-all duration-500 ease-in-out opacity-0 pointer-events-none transform translate-y-[-50px]">
            <div class="pt-6 pb-14 px-10 bg-white rounded-md border border-sgline shadow-lg max-w-sm w-full pointer-events-none">
                <div class="flex justify-center mb-4">
                    <!-- Success Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="headText text-green-500 text-lg text-center font-semibold">
                </h1>
            </div>
        </div>
        {{-- ERROR --}}
        <div id="error-changeName" class="absolute inset-0 flex items-center justify-center z-50 transition-all duration-500 ease-in-out opacity-0 pointer-events-none transform translate-y-[-50px]">
            <div class="pt-6 pb-14 px-10 bg-white rounded-md border border-sgline shadow-lg max-w-sm w-full pointer-events-none">
                <div class="flex justify-center mb-4">
                    <!-- Success Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-red-500 text-lg text-center font-semibold">
                    Invalid!
                </h1>
                <p class="errShow text-center text-sm text-gray-600 mt-2">
                </p>
            </div>
        </div>


        <div class=" w-screen flex flex-col row-start-1 row-span-2 z-40">



            {{-- @if($highlightedCells->isEmpty())
                <p>No highlighted cells found.</p>
            @else
                <table class="table-auto border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Coordinate</th>
                            <th class="border px-4 py-2">Value</th>
                            <th class="border px-4 py-2">Background Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($highlightedCells as $cell)
                            <tr>
                                <td class="border px-4 py-2">{{ $cell->value }}</td>
                                <td class="border px-4 py-2">{{ $cell->row }}</td>
                                <td class="border px-4 py-2">{{ $cell->col }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif --}}


            {{-- Header --}}
            <header class="flex h-full bg-sgcolor shadow tablet:shadow-none px-5 text-sm border border-b-sgline py-0.5">
                <div class=" flex items-center pr-6">
                    <a href=" {{ route('teacher.room', $encodedID) }} " class=" bg-sgcolor grid place-content-center cp:text-3xl">
                        <i class="bx bx-arrow-back text-4xl text-subtText hover:text-mainText"></i>
                    </a>
                </div>
                {{-- HERE ARE THE BUTTONS --}}
                {{-- FONT --}}
                <div class="flex flex-1 items-center h-full relative">
                    <div id="hideTableButtons" class="hidden absolute inset-0 bg-sgcolorSub opacity-50 z-20"></div>
                    <div class=" flex h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-auto relative gap-1">
                            <div class=" flex flex-col w-full h-[90%] justify-center gap-2">
                                <div class=" w-full flex justify-evenly items-start gap-4">
                                    <div class=" w-full flex items-center gap-1">
                                        <div class=" flex w-full h-5 border border-cursor">
                                            <input class="w-full h-full text-[0.8rem] px-2 text-start outline-none" value="Poppins">
                                            <button id="" class="inline-flex items-center bg-bgcolor hover:bg-gray-300 text-[.7rem] border border-sgcolor hover:border-cursor">
                                                <i class="bx bx-caret-down"></i>
                                            </button>
                                        </div>
    
                                        <div class=" flex w-[30%] h-5 relative border border-cursor">
                                            <input id="showFontSize" class="w-full text-[0.8rem] px-2 text-start outline-none" value="14">
                                            <button id="fontSizeButton" class="inline-flex items-center bg-bgcolor hover:bg-gray-300 text-[.7rem] border border-sgcolor hover:border-cursor">
                                                <i class="bx bx-caret-down"></i>
                                            </button>

                                            <div id="dropdownFontSize" class="hidden origin-top-right absolute right-0 mt-7 whitespace-nowrap shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                                <div class="text-[0.8rem] w-11 flex flex-col" role="menu" aria-orientation="vertical" aria-labelledby="caretButton">
                                                    <button data-value="8" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">8</button>
                                                    <button data-value="9" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">9</button>
                                                    <button data-value="10" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">10</button>
                                                    <button data-value="11" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">11</button>
                                                    <button data-value="12" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">12</button>
                                                    <button data-value="14" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">14</button>
                                                    <button data-value="16" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">16</button>
                                                    <button data-value="18" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">18</button>
                                                    <button data-value="20" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">20</button>
                                                    <button data-value="22" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">22</button>
                                                    <button data-value="24" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">24</button>
                                                    <button data-value="26" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">26</button>
                                                    <button data-value="28" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">28</button>
                                                    <button data-value="36" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">36</button>
                                                    <button data-value="48" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">48</button>
                                                    <button data-value="72" class="dropDownSize font-light text-start px-2 py-0.5 w-full hover:bg-sgcolor">72</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" flex w-[20%] gap-1">
                                        <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <button id="addFontSize" class="w-6 h-5 grid place-content-center px-1 font-semibold border border-sgcolor hover:border-cursor hover:bg-gray-300">A</button>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7  shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Increase Font Size</h1>
                                                    <p class="text-[0.8rem]">Make your text a bit bigger.</p>
                                                </div>  
                                            </div>
                                        </div> 
                                        <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <button id="minusFontSize" class="w-6 h-5 grid place-content-center px-1 text-[.6rem] font-semibold border border-sgcolor hover:border-cursor hover:bg-gray-300">A</button>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7  shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Decrease Font Size</h1>
                                                    <p class="text-[0.8rem]">Make your text a bit smaller.</p>
                                                </div>  
                                            </div>
                                        </div> 
                                    </div>
            
                                    {{-- <div id="" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                        <x-colors />
                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                        <div class="flex items-center gap-1">
                                            <div class="dropdownOptionColors w-4 h-4 bg-black border cursor-pointer" data-shade="black"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-white border cursor-pointer" data-shade="white"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-bg_red border cursor-pointer" data-shade="bg_red"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="bg_blue"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="bg_yellow"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-bg_green border cursor-pointer" data-shade="bg_green"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="bg_purple"></div>
                                            <div class="dropdownOptionColors w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="bg_orange"></div>
                                        </div>
                                        <div class="flex items-center gap-2 my-2">
                                            <button id="delete-colors" class="w-full flex items-center gap-2.5">
                                                <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                <h1 class=" text-sm">No Fill</h1>
                                            </button>
                                        </div>
                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                        <div class="flex items-center gap-2">
                                            <input type="color" id="colorPicker" class="w-7 h-7">
                                            <span id="colorName" class="text-sm">Select a Color</span>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class=" w-full flex gap-2">        
                                    {{-- <button id="mergeButton" class="px-2 bg-sgline hover:bg-gray-300">M</button>
                                    <button id="unmergeButton" class="px-2 bg-sgline hover:bg-gray-300 ">UM</button> --}}
                                    <div class="flex gap-1">
                                        <div class="relative group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <div class="w-6 h-5">
                                                <button id="applyBold" class="w-full h-full border border-sgcolor hover:border-cursor hover:bg-gray-300"><b>B</b></button>
                                            </div>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7  shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Bold</h1>
                                                    <p class="text-[0.8rem]">Make your text bold.</p>
                                                </div>  
                                            </div>
                                        </div>                                     
                                        <div class="relative group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <div class="w-6 h-5">
                                                <button id="applyItalic" class="w-full h-full border border-sgcolor hover:border-cursor font-bold font-serif hover:bg-gray-300"><i>I</i></button>
                                            </div>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7  shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Italic</h1>
                                                    <p class="text-[0.8rem]">Italicize your text.</p>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="relative group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <div class="w-6 h-5">
                                                <button id="applyUnderline" class="w-full h-full font-bold border border-sgcolor hover:border-cursor hover:bg-gray-300"><u>U</u></button>
                                            </div>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7  shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Underline</h1>
                                                    <p class="text-[0.8rem]">Underline your text.</p>
                                                </div>  
                                            </div>
                                        </div>      
                                        <div class="relative group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <div class="w-6 h-5">
                                                <button id="delete-styles" class="w-full h-full border border-sgcolor hover:border-cursor hover:bg-gray-300">NS</button>
                                            </div>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7  shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">No Style</h1>
                                                    <p class="text-[0.8rem]">Remove the style of your text.</p>
                                                </div>  
                                            </div>
                                        </div>                                   
                                    </div>
                                    <div class=" h-[70%] self-center border-l border-cursor"></div>
                                    {{-- BORDER --}}
                                    <div class="flex relative border border-sgcolor hover:border-cursor hover:bg-gray-300">                       
                                        <div class="relative w-full flex group z-40" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <button class=" w-6 h-5 text-sm grid place-content-center">
                                                <div class=" w-4 h-3 border border-dotted border-black "></div>
                                            </button>
                                            <button id="caretButton" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                            <i class="bx bx-caret-down"></i>
                                            </button>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Border</h1>
                                                    <p class="text-[0.8rem]">Apply borders to the selected cells.</p>
                                                </div>  
                                            </div>
                                        </div>
                                            
                                        <div id="dropdownMenu" class="hidden origin-top-right absolute left-0 mt-7 whitespace-nowrap rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="caretButton">
                                                <button id="apply-border-btn" class="dropdownOption w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="Border Corner">Border All</button>
                                                <button id="apply-borders" class="dropdownOption w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="Border Corner">Border Corner</button>
                                                <button id="applyTopBorder" class="dropdownOption w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="Top Border">Top Border</button>
                                                <button id="applyRightBorder" class="dropdownOption text-left w-full block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="Right Border">Right Border</button>
                                                <button id="applyBotBorder" class="dropdownOption text-left w-full block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="Bottom Border">Bottom Border</button>
                                                <button id="applyLeftBorder" class="dropdownOption text-left w-full block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="Left Border">Left Border</button>
                                                <button id="delete-borders-all" class="dropdownOption text-left w-full block px-4 py-2 text-sm text-gray-700 hover:bg-sgline" role="menuitem" data-value="No Border">No Border</button>
                                            </div>
                                        </div>
                                    </div>   

                                    <div class=" h-[70%] self-center border-l border-cursor"></div>

                                    {{-- BACKGROUND --}}
                                    <div class="flex relative border border-sgcolor hover:border-cursor hover:bg-gray-300">
                                        <div class="relative  w-full flex group z-40" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <button class="w-6 h-5 grid place-content-center">
                                                <div id="showingColor" class="dropdownOptionColors w-4 h-3 bg-red-500" data-shade="red-500"></div>
                                            </button>
                                            <button id="caretButtonColor" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                <i class="bx bx-caret-down"></i>
                                            </button>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Fill Color</h1>
                                                    <p class="text-[0.8rem]">Color the background of cells to make them stand out.</p>
                                                </div>  
                                            </div>
                                        </div>
                        
                                        <div id="dropdownColorMenu" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                            <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                            <x-colors />
                                            <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                            <div class="flex items-center gap-1">
                                                <div class="dropdownOptionColors w-4 h-4 bg-black border cursor-pointer" data-shade="black"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-white border cursor-pointer" data-shade="white"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-bg_red border cursor-pointer" data-shade="bg_red"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="bg_blue"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="bg_yellow"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-bg_green border cursor-pointer" data-shade="bg_green"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="bg_purple"></div>
                                                <div class="dropdownOptionColors w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="bg_orange"></div>
                                            </div>
                                            <div class="flex items-center gap-2 my-2">
                                                <button id="delete-colors" class="w-full flex items-center gap-2.5">
                                                    <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                    <h1 class=" text-sm">No Fill</h1>
                                                </button>
                                            </div>
                                            <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                            <div class="flex items-center gap-2">
                                                <input type="color" id="colorPicker" class="w-7 h-7">
                                                <span id="colorName" class="text-sm">Select a Color</span>
                                            </div>
                                        </div>
                                    </div>     

                                    <div class=" h-[70%] self-center border-l border-cursor"></div>

                                    {{-- FONT COLOR --}}
                                    <div class="flex relative border border-sgcolor hover:border-cursor hover:bg-gray-300">
                                        <div class="relative w-full flex group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <button id="showingFontColor" class="dropdownOptionColorFont w-6 h-5 text-red-500 font-bold" data-shade="#ef4444">A</button>
                                            <button id="caretButtonColorFont" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                <i class="bx bx-caret-down"></i>
                                            </button>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Font Color</h1>
                                                    <p class="text-[0.8rem]">Change the color of your text.</p>
                                                </div>  
                                            </div>
                                        </div>   
                        
                                        <div id="dropdownColorFontMenu" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                            <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                            <x-colorFont />
                                            <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                            <div class="flex items-center gap-1">
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-black border cursor-pointer" data-shade="#000000"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-white border cursor-pointer" data-shade="#ffffff"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-bg_red border cursor-pointer" data-shade="#ff0000"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="#0000ff"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="#ffff00"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-bg_green border cursor-pointer" data-shade="#00b050"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="#800080"></div>
                                                <div class="dropdownOptionColorFont w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="#ffa500"></div>
                                            </div>
                                            <div class="flex items-center gap-2 my-2">
                                                <button id="delete-font-colors" class="w-full flex items-center gap-2.5">
                                                    <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                    <h1 class=" text-sm">No Color</h1>
                                                </button>
                                            </div>
                                            <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                            <div class="flex items-center gap-2">
                                                <input type="color" id="colorFontPicker" class="w-7 h-7">
                                                <span id="colorName" class="text-sm">Select a Color</span>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Font</h1>
                            </div>
                        </div>
                    </div>
    
                    {{-- ALIGNMENT --}}
                    <div class=" flex h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-full relative gap-1">
                            <div class=" flex flex-col w-full h-[90%] justify-center gap-2">
                                <div class="flex justify-between gap-1">
                                    <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                        <div class="w-6 h-5 px-0.5">
                                            <button id="applyTextStart" class=" w-full h-full grid place-content-center border border-sgcolor hover:border-cursor hover:bg-gray-300"><img src="{{ Vite::asset('resources/images/start.svg') }}" alt="start"></button>
                                        </div>
                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                <h1 class="text-[0.8rem] font-bold">Align Left</h1>
                                                <p class="text-[0.8rem]">Align your content to the left.</p>
                                            </div>  
                                        </div>
                                    </div>   
                                    <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                        <div class="w-6 h-5 px-0.5">
                                            <button id="applyTextCenter" class=" w-full h-full grid place-content-center border border-sgcolor hover:border-cursor hover:bg-gray-300"><img src="{{ Vite::asset('resources/images/center.svg') }}" alt="center"></button>
                                        </div>
                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                <h1 class="text-[0.8rem] font-bold">Center</h1>
                                                <p class="text-[0.8rem]">Center your Content.</p>
                                            </div>  
                                        </div>
                                    </div>   
                                    <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                        <div class="w-6 h-5 px-0.5">
                                            <button id="applyTextEnd" class=" w-full h-full  grid place-content-center border border-sgcolor hover:border-cursor hover:bg-gray-300"><img src="{{ Vite::asset('resources/images/end.svg') }}" alt="end"></button>
                                        </div>
                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                <h1 class="text-[0.8rem] font-bold">Align Right</h1>
                                                <p class="text-[0.8rem]">Align your content to the right.</p>
                                            </div>  
                                        </div>
                                    </div>  
                                </div>
                                <div class="flex justify-evenly gap-1">
                                    {{-- <div class=" w-6 h-5 px-0.5">
                                        <button id="mergeButton" class=" w-full h-full grid place-content-center border border-sgcolor hover:border-cursor hover:bg-gray-300">
                                            <img src="{{ Vite::asset('resources/images/merge.svg') }}" alt="merge">
                                        </button>
                                    </div> --}}
                                    {{-- <div class=" w-6 h-5 px-0.5">
                                        <button id="unmergeButton" class=" w-full h-full grid place-content-center border border-sgcolor hover:border-cursor hover:bg-gray-300">
                                            <img src="{{ Vite::asset('resources/images/unmerge.svg') }}" alt="unmerge">
                                        </button>
                                    </div> --}}

                                    <div class="flex relative border border-sgcolor hover:border-cursor hover:bg-gray-300">
                                        <div class="relative w-full flex gap-2 group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <div id="mergeButton" class=" flex whitespace-nowrap gap-1">
                                                <button class="w-6 h-5 px-0.5 grid place-content-center text-red-500 font-bold">
                                                    <img src="{{ Vite::asset('resources/images/merge.svg') }}" alt="merge">
                                                </button>
                                                <div class="cursor-default">
                                                    <h1 class="text-[0.8rem]">Merge Cells</h1>
                                                </div>
                                            </div>              
                                            <button id="caretButtonMergeUnmerge" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                <i class="bx bx-caret-down"></i>
                                            </button>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Merge & Unmerge</h1>
                                                    <p class="text-[0.8rem]">Make your selected cells combine if Merge and not if Unmerge.</p>
                                                </div>  
                                            </div>
                                        </div> 
                        
                                        <div id="dropdownMergeUnmerge" class="hidden absolute origin-top-right left-[-1px] mt-7 bg-bgcolor border border-sgline z-10">
                                            <div class="dropdownOptionMergeUnmerge p-0.5">
                                                <div id="unmergeButton" class="w-full flex items-center whitespace-nowrap gap-1 pr-4 hover:bg-gray-300">
                                                    <button class="w-6 h-5 px-0.5 grid place-content-center text-red-500 font-bold">
                                                        <img src="{{ Vite::asset('resources/images/unmerge.svg') }}" alt="unmerge">
                                                    </button>
                                                    <div class="cursor-default">
                                                        <h1 class="text-[0.8rem]">Unmerge Cells</h1>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
 

                                </div>
                            </div>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Alignment</h1>
                            </div>
                        </div>
                    </div>
    
                    {{-- NAME --}}
                    <div class=" flex h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-full relative gap-1">
                            <div class=" flex flex-col w-full h-full justify-center gap-2 py-2">
                                @if ($names->isEmpty())
                                    <a href="{{ route('paste-name', $encodedID) }}" class="w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">
                                        <i class=' text-2xl bx bxs-right-arrow-square'></i>
                                    </a>
                                @else
                                    @if (!$selected > 0 )
                                        <div id="studentContainer" class=" h-full bg-slate-500">
                                            <div class="relative group z-50 h-full" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                <div class="flex h-full">
                                                    <button id="pushNames" class="px-4 flex-1 h-full grid place-content-center text-[.8rem] bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 {{ $names->isEmpty() ? 'pointer-events-none opacity-50' : 'opacity-100' }}">
                                                        <i class="bx bxs-group text-2xl"></i>
                                                    </button>
                                                </div>
                                                <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                    <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                        <h1 class="text-[0.8rem] font-bold">Show Students</h1>
                                                        <p class="text-[0.8rem]">Shows all students in column of your selected cell.</p>
                                                    </div>  
                                                </div>
                                            </div>  
                                        </div>  
                                        <div id="removeContainer" class="hidden h-full">
                                            <div class="relative group z-50 h-full" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                <div class="flex h-full">
                                                    <form action="{{ route('delete-push-names', $room->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                        <input type="hidden" name="tableID" value="main-table">
                                                        <button class="px-4 flex-1 w-full h-full grid place-content-center text-[.8rem] bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300  {{ $names->isEmpty() ? 'pointer-events-none opacity-50' : 'opacity-100' }}">
                                                            <i class='bx bxs-user-x text-2xl'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                    <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                        <h1 class="text-[0.8rem] font-bold">Remove Students</h1>
                                                        <p class="text-[0.8rem]">Remove all names of students.</p>
                                                    </div>  
                                                </div>
                                            </div>  
                                        </div> 
                                    @else
                                        <input type="hidden" id="pushNames">
                                        <div class="relative group z-50 h-full" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                            <div class="flex h-full">
                                                <form action="{{ route('delete-push-names', $room->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                    <input type="hidden" name="tableID" value="main-table">
                                                    <button class="px-4 flex-1 w-full h-full grid place-content-center text-[.8rem] bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300  {{ $names->isEmpty() ? 'pointer-events-none opacity-50' : 'opacity-100' }}">
                                                        <i class='bx bxs-user-x text-2xl'></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                    <h1 class="text-[0.8rem] font-bold">Remove Students</h1>
                                                    <p class="text-[0.8rem]">Remove all names of students.</p>
                                                </div>  
                                            </div>
                                        </div>  
                                    @endif 
                                @endif
                            </div>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Name</h1>
                            </div>
                        </div>
                    </div>
    
    
                    {{-- CONDITION --}}
                    <div class=" flex h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-full relative gap-1">
                            <div class=" flex h-full gap-2">
                                <div class=" h-full flex justify-center relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                    <div class=" flex justify-between items-center w-14 py-2">
                                        <button id="ifCondition" class="w-full h-full bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300">If</button>
                                    </div>
                                    <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                        <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                            <h1 class="text-[0.8rem] font-bold">If(logical_test,result_if_true,result_if_false)</h1>
                                            <p class="text-[0.8rem]">Check wheter a condition is met, and returns all result if TRUE, and another result if FALSE.</p>
                                        </div>  
                                    </div>
                                </div> 
                                <div class=" h-full flex justify-center relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                    <div class=" flex justify-between items-center w-14 py-2">
                                        <button id="ifConditionColor" class="w-full h-full bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300">If Color</button>
                                    </div>
                                    <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                        <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                            <h1 class="text-[0.8rem] font-bold">If(logical_test,color_of_text_if_true,color_of_text_if_false)</h1>
                                            <p class="text-[0.8rem]">Check wheter a condition is met, and color all text if result is TRUE, and another color if result is FALSE.</p>
                                        </div>  
                                    </div>
                                </div> 
                            </div>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Condition</h1>
                            </div>
                        </div>
                    </div>

                    {{-- IMPORT --}}
                    <div class=" flex h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-full relative gap-1">
                            <div class="flex h-full w-full relative gap-1">
                                <div class=" flex flex-col w-full h-full justify-center gap-2 py-2">
                                        <button id="import" class="import w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">Imprt</button>
                                </div>
                                <div class=" flex flex-col w-full h-full justify-center gap-2 py-2">
                                    <button id="export" class="export w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">Exprt</button>
                                </div>
                                <div class=" flex flex-col w-full h-full justify-center gap-2 py-2">
                                    <div id="printButton" class="h-full">
                                        <button id="print-button" class=" w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">PrintB</button>
                                    </div>
                                    <div id="printGrade" class="hidden h-full">
                                        <button id="print-grade" class=" w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">PrintG</button>
                                    </div>
                                </div>
                            </div>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Import</h1>
                            </div>
                        </div>
                    </div>

                    {{-- ROWS and COLUMNS --}}
                    <div class=" flex h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-full relative gap-1">
                            <form action="{{ route('total-row-col', [ 'id' => $encodedID, 'key' => 'main-table']) }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="flex h-full w-full relative gap-2 py-2">
                                    <div class="flex flex-col h-full w-full relative gap-2">
                                        <div class=" flex w-full h-full justify-between items-center gap-2">
                                            <label for="columnNumber" class=" w-8">Col</label>
                                            <h1>:</h1>
                                            <input class=" w-14 border border-sgline outline-none pl-2" type="number" id="columnNumber" name="columnNumber" value="{{$totalColumn}}">
                                        </div>
                                        <div class=" flex w-full h-full justify-between items-center gap-2">
                                            <label for="rowNumber" class=" w-8">Row</label>
                                            <h1>:</h1>
                                            <input class=" w-14 border border-sgline outline-none pl-2" type="number" id="rowNumber" name="rowNumber" value="{{$totalRow}}">
                                        </div>
                                    </div>
                                    <div>
                                        <button class=" w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">Save</button>
                                    </div>
                                </div>
                            </form>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Rows & Columns</h1>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMATION --}}
                    <div class=" flex w-full h-full pr-2">
                        <div class=" h-full border-l border-cursor rounded-full mr-2"></div>
                        <div class="flex flex-col h-full w-full relative gap-1">
                            <div class=" flex justify-between items-center w-full h-[90%]">
                                <div class="flex flex-col items-center justify-center gap-2 w-full h-full">
                                    <div class="flex w-full h-[1.3rem] gap-2">
                                        <div class="flex items-center border border-sgline bg-bgcolor text-center">
                                            <label for="showRowCol"></label>
                                            <input type="text" name="showRowCol" id="showRowCol" class=" pointer-events-none text-[0.8rem] outline-none border-none bg-transparent px-2" placeholder="Column and Row">
                                        </div>
                                        <div class="flex items-center border border-sgline py-1 pl-2 pr-2 w-full bg-bgcolor text-sm">
                                            <input class="formulaTitle h-4 flex items-center text-sm text-sgline pointer-events-none" name="formulaTitle" id="formulaTitle" placeholder="Formula">
                                            <div class="hidden w-full" name="formula-box" id="formulaBox">
                                                <label for="formulaInput"></label>
                                                <input id="formulaInput" name="formulaInput" type="text" class=" text-[0.8rem] outline-none border-none bg-transparent w-full" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center h-[1.3rem] border border-sgline w-full bg-bgcolor">
                                        <label for="showContent"></label>
                                        <input type="text" name="showContent" id="showContent" class="w-full text-[0.8rem] outline-none border-none bg-transparent px-2" placeholder="Content">
                                    </div>
                                </div>
                            </div>
                            <div class=" w-full flex-1 text-center">
                                <h1 class="text-[11px]">Information</h1>
                            </div>
                        </div>
                    </div>
                


                    
                    {{-- <form id="importHighlightedForm" enctype="multipart/form-data">
                        <label for="file">Upload Excel File:</label>
                        <input type="file" id="file" name="file" accept=".xlsx,.xls" required>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Import Highlighted Data</button>
                    </form>
                    <div id="resultMessage" class="mt-4"></div> --}}
    
    
                    {{-- TEMPORARY --}}
                    {{-- <div class=" flex w-full h-full pr-6">
                        <div class=" h-full border-l border-cursor rounded-full mr-6"></div>
                        <div class="flex flex-col justify-around h-full w-full">
                            <div class="flex flex-col justify-around h-full w-full">
                                <button id="pushNames" class="px-4 bg-sgline">Students Name</button>
                                <form action="{{ route('delete-push-names', $room->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                    <button class="px-4 w-full bg-sgline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div> --}}
    
    
    
    
    
                    
                    {{-- <div class=" flex w-full h-full pr-6">
                        <div class=" h-full border-l border-cursor rounded-full mr-6"></div>
                        <div class="flex flex-col justify-around h-full w-full">
                            <div class="flex w-full justify-evenly gap-4">
                                <button id="applyUnderline" class="flex-1 bg-sgline"><u>U</u></button>
                                <button id="applyBold" class="flex-1 bg-sgline"><b>B</b></button>
                            </div>
                            <div class="flex w-full justify-evenly gap-4">
                                <button id="applyItalic" class="flex-1 bg-sgline font-serif"><i>I</i></button>
                                <button id="delete-styles" class="flex-1 bg-sgline">No Style</button>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class=" flex w-full h-full pr-6">
                        <div class=" h-full border-l border-cursor rounded-full mr-6"></div>
                        <div class="flex flex-col justify-around h-full w-full"> --}}
                            {{-- COLOR START --}}
                            {{-- <div class=" w-full bg-slate-300 relative">
                                <div class=" flex gap-1">
                                    <button class="w-full bg-sgline">Font Color</button>
                                    <button id="caretButtonColorFont" class="inline-flex items-center px-2 bg-sgline">
                                        <i class="bx bx-caret-down"></i>
                                    </button>
                                </div>  
        
                                <div id="dropdownColorFontMenu" class="hidden absolute origin-top-right right-0 mt-2 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                    <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                    <x-colorFont />
                                    <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                    <div class="flex items-center gap-1">
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-black border cursor-pointer" data-shade="#000000"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-white border cursor-pointer" data-shade="#ffffff"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-bg_red border cursor-pointer" data-shade="#ff0000"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="#0000ff"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="#ffff00"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-bg_green border cursor-pointer" data-shade="#00b050"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="#800080"></div>
                                        <div class="dropdownOptionColorFont w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="#ffa500"></div>
                                    </div>
                                    <div class="flex items-center gap-2 my-2">
                                        <button id="delete-font-colors" class="w-full flex items-center gap-2.5">
                                            <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                            <h1 class=" text-sm">No Color</h1>
                                        </button>
                                    </div>
                                    <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                    <div class="flex items-center gap-2">
                                        <input type="color" id="colorFontPicker" class="w-7 h-7">
                                        <span id="colorName" class="text-sm">Select a Color</span>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- COLOR END --}}
                            {{-- <div class=" flex gap-1">
                                <button id="ifCondition" class="w-full bg-sgline">If else</button>
                            </div>   --}}
                        {{-- </div>
                    </div> --}}
                    {{-- <div class=" flex w-full h-full pr-6">
                        <div class=" h-full border-l border-cursor rounded-full mr-6"></div>
                        <div class="flex flex-col justify-around h-full w-full">
                            <div class="flex w-full justify-evenly gap-4">
                                <button id="applyTextStart" class="flex-1 bg-sgline"><i class="bx bx-align-left"></i></button>
                                <button id="applyTextCenter" class="flex-1 bg-sgline"><i class="bx bx-align-middle"></i></button>
                            </div>
                            <div class="flex w-full justify-evenly gap-4">
                                <button id="applyTextEnd" class="flex-1 bg-sgline"><i class="bx bx-align-right"></i></button>
                                <button id="mergeButton" class="px-2 bg-sgline hover:bg-gray-300">M</button>
                                <button id="unmergeButton" class="px-2 bg-sgline hover:bg-gray-300 ">UM</button>
                            </div>
                        </div>
    
                    </div> --}}
                    
                    {{-- END TEMPORARY --}}
    
                    {{-- <div class="mb-4">
                        <button id="addButton" class="p-2 bg-slate-300 rounded-md">Total</button>
                        <button id="percentageButton" class="p-2 bg-slate-300 rounded-md">PS/WS</button>
                        <button id="sumButton" class="p-2 bg-slate-300 rounded-md">Sum</button>
                        <button id="subtractButton" class="p-2 bg-slate-300 rounded-md">Subtract</button>
                        <button id="divideButton" class="p-2 bg-slate-300 rounded-md">Divide</button>
                        <button id="multiplyButton" class="p-2 bg-slate-300 rounded-md">Multiply</button>
                    </div> --}}
                    {{-- <button id="applyColor">Apply Color</button>
                    <input type="color" id="colorPicker" value="#ff0000">
                    <button id="deleteColor" class="p-2 bg-red-300 rounded-md mb-4">Delete Color</button> --}}
                </div>
            </header>

            {{-- TITLE FOR THIS RECORD --}}
            {{-- <div class="w-full h-f py-1 bg-sgcolorSub flex-1 flex items-center px-4"> --}}
                {{-- START --}}
                {{-- <div class="flex items-center gap-4 w-full h-full">
                    <div class="flex w-[30%] h-[75%] gap-4">
                        <div class="flex items-center border border-sgline bg-bgcolor text-center">
                            <label for="showRowCol"></label>
                            <input type="text" name="showRowCol" id="showRowCol" class=" pointer-events-none text-sm outline-none border-none bg-transparent px-2 w-[6ch] min-w-[6ch]">
                        </div>
                        <div class="flex items-center border border-sgline py-1 pl-2 pr-2 w-full bg-bgcolor text-sm">
                            <input class="formulaTitle text-sm text-sgline pointer-events-none" name="formulaTitle" id="formulaTitle" value="FORMULA:">
                            <div class="hidden w-full" name="formula-box" id="formulaBox">
                                <label for="formulaInput"></label>
                                <input id="formulaInput" name="formulaInput" type="text" class=" text-sm outline-none border-none bg-transparent w-full" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center h-[75%] border border-sgline w-full bg-bgcolor">
                        <label for="showContent"></label>
                        <input type="text" name="showContent" id="showContent" class="w-full text-sm outline-none border-none bg-transparent px-2">
                    </div>
                </div> --}}
            {{-- </div> --}}
        </div>


        {{-- Nav Bar --}}
        {{-- <div class=" absolute bottom-0 right-0 left-0 z-50">
            <div class="flex">
                <div class="flex bg-gray-100 gap-4 px-8 pb-2 border-t border-sgline">
                    <div class="recordTabDiv bg-bgcolor px-4 border-x border-b-4 border-mainText hover:border-b-4 hover:border-mainText">
                        <button id="recordTab" class="cursor-pointer">Records</button>
                    </div>
                    <div class="px-4 hover:border-b-4 hover:border-mainText cursor-pointer">
                        <a href="{{ route('attendance.room', [ 'id' => $encodedID, 'key' => 'attendance-table']) }}">Attendance</a>
                    </div>
                    <div class="ratingTabDiv px-4 hover:border-b-4 hover:border-mainText">
                        <button id="ratingTab" class="cursor-pointer">Grading Sheet</button>
                    </div>
                </div> --}}
                {{-- <div class=" flex-1 flex items-center gap-2 bg-gray-100">

                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='M.C'>Mid-C</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='M.P'>Mid-P</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='M.A'>Mid-A</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='MidGr'>MidGr</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='Mid.N.Eqv.'>Mid.N.Eqv.</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='F.C'>Fin-C</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='F.P'>Fin-P</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='F.A'>Fin-A</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='T.F.Gr.'>T.F.Gr.</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='F.N.Eqv.'>F.N.Eqv.</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='Mid%'>Mid%</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='Fin%'>Fin%</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='FR%Eqv.'>FR%Eqv.</button>
                        <button class=" cursor-pointer bg-gray-300 px-2" data-col='FR.N.Eqv.'>FR.N.Eqv.</button>
                </div> --}}
            {{-- </div>
        </div> --}}


        {{-- Body --}}
        <section id="sample" class="teacher-choices row-start-2 col-start-1 col-span-6 cp:col-start-1 cp:col-span-16 cp:row-start-3 cp:row-span-13 small-bp">
            

            <div class="row-start-3 w-full h-full overflow-x-auto relative text-sm">
                <div class="t-ble w-full ">
                    {{-- If Condition Starts Here!!! --}}
                    <div id="logic" class="fixed inset-0 z-20 bg-transparent pointer-events-none hidden">
                        <div class="w-full h-full grid place-content-center pointer-events-none">
                            <div id="draggable" class=" w-[35rem] bg-bgcolor border border-cursor rounded-md pointer-events-auto">
                                <div id="header" class=" flex justify-between">
                                    <div class="">
                                        <h1 class=" px-4 py-2 font-semibold">If Condition</h1>
                                    </div>
                                    <div class="flex items-center">
                                        <button class=" font-semibold cursor-default hover:bg-sgline px-5 py-2">?</button>
                                        <button id="closeIf" class=" font-semibold cursor-default hover:bg-red-500 hover:text-bgcolor px-5 py-2">x</button>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col px-[5.5rem] py-8 gap-4 bg-sgcolorSub border-y border-cursor">
                                    <div class="w-full flex gap-4 items-center">
                                        <div class=" w-[25%] text-end text-sm">
                                            <h1>Condition</h1>
                                        </div>
                                        <div class="flex-1">
                                            <input id="condition" type="text" class=" w-full border border-sgline outline-none px-2 text-sm py-0.5" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="w-full flex gap-4 items-center">
                                        <div class=" w-[25%] text-end text-sm">
                                            <h1>Result if True</h1>
                                        </div>
                                        <div class="flex-1">
                                            <input id="resultTrue" type="text" class=" w-full border border-sgline outline-none px-2 text-sm py-0.5" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="w-full flex gap-4 items-center">
                                        <div class=" w-[25%] text-end text-sm">
                                            <h1>Result if False</h1>
                                        </div>
                                        <div class="flex-1">
                                            <input id="resultFalse" type="text" class=" w-full border border-sgline outline-none px-2 text-sm py-0.5" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class=" w-full px-4 py-3 flex justify-end bg-sgcolorSub gap-4">
                                    <button id="ifOk" class="border bg-sgline border-cursor w-[6rem] py-0.5 text-sm grid place-content-center hover:border-blue-500 cursor-default">
                                        <h1>Ok</h1>
                                    </button>
                                    <button id="cancelIf" class="border bg-sgline border-cursor w-[6rem] py-0.5 text-sm grid place-content-center hover:border-blue-500 cursor-default">
                                        <h1>Cancel</h1>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    {{-- If Condition Ends Here!!! --}}

                    {{-- If Condition Color Starts Here!!! --}}
                    <div id="logicColor" class="fixed inset-0 z-20 bg-transparent pointer-events-none hidden">
                        <div class="w-full h-full grid place-content-center pointer-events-none">
                            <div id="draggableColor" class=" w-[35rem] bg-bgcolor border border-cursor rounded-md pointer-events-auto">
                                <div id="headerColor" class=" flex justify-between">
                                    <div class="">
                                        <h1 class=" px-4 py-2 font-semibold">If Condition</h1>
                                    </div>
                                    <div class="flex items-center">
                                        <button class=" font-semibold cursor-default hover:bg-sgline px-5 py-2">?</button>
                                        <button id="closeIfColor" class=" font-semibold cursor-default hover:bg-red-500 hover:text-bgcolor px-5 py-2">x</button>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col px-[5.5rem] py-8 gap-4 bg-sgcolorSub border-y border-cursor">
                                    <div class="w-full flex gap-4 items-center">
                                        <div class=" w-[25%] text-end text-sm">
                                            <h1>Condition</h1>
                                        </div>
                                        <div class="flex-1">
                                            <input id="conditionColor" type="text" class=" w-full border border-sgline outline-none px-2 text-sm py-0.5" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="w-full flex gap-4 items-center">
                                        <div class=" w-[25%] text-end text-sm">
                                            <h1>Color of text if True</h1>
                                        </div>
                                        <div class="w-[45%]">
                                            <input id="resultTrueColor" type="text" class=" w-full border border-sgline outline-none px-2 text-sm py-0.5">
                                        </div>
                                        <div class=" flex-1">
                                            <div class="w-full flex justify-between">
                                                <div class="flex relative border border-cursor hover:bg-gray-300">
                                                    <div class="relative w-full flex group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <button id="showingIfTrueFontColor" class="dropdownTrueOptionColorFont w-6 h-5 text-red-500 font-bold" data-shade="#ef4444">A</button>
                                                        <button id="ifCaretTrueColorFont" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                            <i class="bx bx-caret-down"></i>
                                                        </button>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Font Color</h1>
                                                                <p class="text-[0.8rem]">Change the color of your text.</p>
                                                            </div>  
                                                        </div>
                                                    </div>   
                                    
                                                    <div id="dropdownIfTrueColorFont" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-50">
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                                        <x-ifTrueColorFont />
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                                        <div class="flex items-center gap-1">
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-black border cursor-pointer" data-shade="#000000"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-white border cursor-pointer" data-shade="#ffffff"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-bg_red border cursor-pointer" data-shade="#ff0000"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="#0000ff"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="#ffff00"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-bg_green border cursor-pointer" data-shade="#00b050"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="#800080"></div>
                                                            <div class="dropdownTrueOptionColorFont w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="#ffa500"></div>
                                                        </div>
                                                        <div class="flex items-center gap-2 my-2">
                                                            <button id="delete-font-colors" class="w-full flex items-center gap-2.5">
                                                                <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                                <h1 class=" text-sm">No Color</h1>
                                                            </button>
                                                        </div>
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                                        <div class="flex items-center gap-2">
                                                            <input type="color" id="colorFontPicker" class="w-7 h-7">
                                                            <span id="colorName" class="text-sm">Select a Color</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex relative border border-cursor hover:bg-gray-300">
                                                    <div class="relative  w-full flex group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <button class="w-6 h-5 grid place-content-center">
                                                            <div id="showingIfTrueColor" class="dropdownTrueOptionColors w-4 h-3 bg-red-500" data-shade="red-500"></div>
                                                        </button>
                                                        <button id="ifCaretTrueColor" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                            <i class="bx bx-caret-down"></i>
                                                        </button>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Fill Color</h1>
                                                                <p class="text-[0.8rem]">Color the background of cells to make them stand out.</p>
                                                            </div>  
                                                        </div>
                                                    </div>
                                    
                                                    <div id="dropdownIfTrueColor" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                                        <x-ifTrueColor />
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                                        <div class="flex items-center gap-1">
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-black border cursor-pointer" data-shade="black"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-white border cursor-pointer" data-shade="white"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-bg_red border cursor-pointer" data-shade="bg_red"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="bg_blue"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="bg_yellow"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-bg_green border cursor-pointer" data-shade="bg_green"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="bg_purple"></div>
                                                            <div class="dropdownTrueOptionColors w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="bg_orange"></div>
                                                        </div>
                                                        <div class="flex items-center gap-2 my-2">
                                                            <button id="delete-colors" class="w-full flex items-center gap-2.5">
                                                                <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                                <h1 class=" text-sm">No Fill</h1>
                                                            </button>
                                                        </div>
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                                        <div class="flex items-center gap-2">
                                                            <input type="color" id="colorPicker" class="w-7 h-7">
                                                            <span id="colorName" class="text-sm">Select a Color</span>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full flex gap-4 items-center">
                                        <div class=" w-[25%] text-end text-sm">
                                            <h1>Color of text if False</h1>
                                        </div>
                                        <div class="w-[45%]">
                                            <input id="resultFalseColor" type="text" class=" w-full border border-sgline outline-none px-2 text-sm py-0.5">
                                        </div>
                                        <div class="flex-1">
                                            <div class="w-full flex justify-between">
                                                <div class="flex relative border border-cursor hover:bg-gray-300">
                                                    <div class="relative w-full flex group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <button id="showingIfFalseFontColor" class="dropdownFalseOptionColorFont w-6 h-5 text-red-500 font-bold" data-shade="#ef4444">A</button>
                                                        <button id="ifCaretFalseColorFont" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                            <i class="bx bx-caret-down"></i>
                                                        </button>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Font Color</h1>
                                                                <p class="text-[0.8rem]">Change the color of your text.</p>
                                                            </div>  
                                                        </div>
                                                    </div>   
                                    
                                                    <div id="dropdownIfFalseColorFont" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                                        <x-ifFalseColorFont />
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                                        <div class="flex items-center gap-1">
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-black border cursor-pointer" data-shade="#000000"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-white border cursor-pointer" data-shade="#ffffff"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-bg_red border cursor-pointer" data-shade="#ff0000"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="#0000ff"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="#ffff00"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-bg_green border cursor-pointer" data-shade="#00b050"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="#800080"></div>
                                                            <div class="dropdownFalseOptionColorFont w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="#ffa500"></div>
                                                        </div>
                                                        <div class="flex items-center gap-2 my-2">
                                                            <button id="delete-font-colors" class="w-full flex items-center gap-2.5">
                                                                <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                                <h1 class=" text-sm">No Color</h1>
                                                            </button>
                                                        </div>
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                                        <div class="flex items-center gap-2">
                                                            <input type="color" id="colorFontPicker" class="w-7 h-7">
                                                            <span id="colorName" class="text-sm">Select a Color</span>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="flex relative border border-cursor hover:bg-gray-300">
                                                    <div class="relative  w-full flex group" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <button class="w-6 h-5 grid place-content-center">
                                                            <div id="showingIfFalseColor" class="dropdownFalseOptionColors w-4 h-3 bg-red-500" data-shade="red-500"></div>
                                                        </button>
                                                        <button id="ifCaretFalseColor" class="inline-flex items-center text-[0.7rem] bg-sgcolor">
                                                            <i class="bx bx-caret-down"></i>
                                                        </button>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 mt-7 shadow-md flex-col items-center mb-3 transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Fill Color</h1>
                                                                <p class="text-[0.8rem]">Color the background of cells to make them stand out.</p>
                                                            </div>  
                                                        </div>
                                                    </div>
                                    
                                                    <div id="dropdownIfFalseColor" class="hidden absolute origin-top-right left-0 mt-7 bg-bgcolor border border-sgline p-0.5 rounded-sm z-10">
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 mb-0.5">Theme Colors</div>
                                                        <x-ifFalseColor />
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Core Colors</div>
                                                        <div class="flex items-center gap-1">
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-black border cursor-pointer" data-shade="black"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-white border cursor-pointer" data-shade="white"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-bg_red border cursor-pointer" data-shade="bg_red"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-bg_blue border cursor-pointer" data-shade="bg_blue"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-bg_yellow border cursor-pointer" data-shade="bg_yellow"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-bg_green border cursor-pointer" data-shade="bg_green"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-bg_purple border cursor-pointer" data-shade="bg_purple"></div>
                                                            <div class="dropdownFalseOptionColors w-4 h-4 bg-bg_orange border cursor-pointer" data-shade="bg_orange"></div>
                                                        </div>
                                                        <div class="flex items-center gap-2 my-2">
                                                            <button id="delete-colors" class="w-full flex items-center gap-2.5">
                                                                <div class=" w-7 h-5 bg-bgcolor border border-sgline"></div>
                                                                <h1 class=" text-sm">No Fill</h1>
                                                            </button>
                                                        </div>
                                                        <div class=" w-full bg-sgline text-sm font-semibold p-1 my-0.5">Other Colors</div>
                                                        <div class="flex items-center gap-2">
                                                            <input type="color" id="colorPicker" class="w-7 h-7">
                                                            <span id="colorName" class="text-sm">Select a Color</span>
                                                        </div>
                                                    </div>
                                                </div>     
                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" w-full px-4 py-3 flex justify-end bg-sgcolorSub gap-4">
                                    <button id="ifOkColor" class="border bg-sgline border-cursor w-[6rem] py-0.5 text-sm grid place-content-center hover:border-blue-500 cursor-default">
                                        <h1>Ok</h1>
                                    </button>
                                    <button id="cancelIfColor" class="border bg-sgline border-cursor w-[6rem] py-0.5 text-sm grid place-content-center hover:border-blue-500 cursor-default">
                                        <h1>Cancel</h1>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>  
                    {{-- If Condition Color Ends Here!!! --}}

                    <div id="loadingScreen" class="loadingScreen fixed hidden inset-0 bg-white z-50">
                        <div class=" w-full h-full flex items-center justify-center">
                            <div class=" w-full h-full flex items-center justify-center gap-3 -ml-5">
                                <div class=" flex flex-col">
                                    <img class="w-16 h-20 animate-jump" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo"  id="mascot">
                                </div>
                                <div class=" flex gap-3 mt-5">
                                    <div class=" w-2 h-2 rounded-full animate-jump1 bg-black"></div>
                                    <div class=" w-2 h-2 rounded-full animate-jump2 bg-black"></div>
                                    <div class=" w-2 h-2 rounded-full animate-jump3 bg-black"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="overallLoadingScreen" class=" fixed inset-0 bg-white z-50">
                        <div class=" w-full h-full flex items-center justify-center gap-3 -ml-5">
                            <div class=" flex flex-col">
                                <img class="w-16 h-20 animate-jump" src="{{ Vite::asset('resources/images/saptlogo.svg') }}" alt="Logo"  id="mascot">
                            </div>
                            <div class=" flex gap-3 mt-5">
                                <div class=" w-2 h-2 rounded-full animate-jump1 bg-black"></div>
                                <div class=" w-2 h-2 rounded-full animate-jump2 bg-black"></div>
                                <div class=" w-2 h-2 rounded-full animate-jump3 bg-black"></div>
                            </div>
                        </div>
                    </div>
                    <div id="showImport" class="showImport hidden fixed inset-0 z-40">
                        @if(session('successImport'))
                            <p id="success-message" class="hidden">{{ session('success') }}</p>
                        @endif

                        @if(session('errorImport'))
                            <p id="error-message" class="hidden">{{ session('error') }}</p>
                        @endif
                        <div class=" w-full h-full grid place-content-center">
                            <div class=" bg-bgcolor flex flex-col rounded-sm px-5 py-3 border border-sgline shadow-md">
                                <form action="{{ route('importHighlighted', $room->id) }}" id="importHighlightedForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class=" flex flex-col gap-3">
                                        <div class=" flex flex-col">
                                            <label class=" text-lg pb-7 font-bold" for="file">Upload Excel File:</label>
                                            <input type="file" id="file" name="file" accept=".xlsx,.xls" required>
                                        </div>
                                    </div>
                                    <div class=" mt-8 flex justify-end gap-2">
                                        <button class=" cancelImport bg-slate-200 w-24 text-sm py-0.5">Cancel</button>
                                        <button type="submit" class="bg-slate-200 w-24 text-sm py-0.5">Ok</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @php
                    $mergedCells = [];
                    $nextSequence = function($index) {
                        if ($index < 26) {
                            // A-Z
                            return chr($index + ord('A'));
                        } else {
                            $index -= 26;
                            $firstChar = chr(floor($index / 26) + ord('A'));
                            $secondChar = chr(($index % 26) + ord('A'));
                            return $firstChar . $secondChar;
                        }
                    };
                    $sequences = [];
                    for ($i = 0; $i < $totalColumn; $i++) {
                        $sequences[] = $nextSequence($i);
                    }

                    $organizedData = [];
                    foreach ($data as $cell) {
                        $organizedData[$cell->row][$cell->column] = $cell;
                        $organizedStudent[$cell->student_name][$cell->column] = $cell;
                    }

                    $organizedDataRatings = [];
                    foreach ($dataRatings as $cell) {
                        $organizedDataRatings[$cell->row][$cell->column] = $cell;
                        $organizedDataStudent[$cell->student_name][$cell->column] = $cell;

                    }


                    @endphp


                    <div class=" zoom-container">
                        <table class=" main-table w-full mb-[1.3rem]" id="main-table" data-table="main-table" data-room-id="{{ $room->id }}" data-teacher-id="{{ $room->teacher_id }}" data-row-start="{{ $selected }}">
                
                            <thead class="t-head sticky top-0 z-10">
                                <tr>
                                    <th class="border-b border-sgcolor bg-sgcolorSub  pt-2"></th>
                                    @for ($col = 0; $col < count($sequences); $col++)
                                    @php
                                        $columnValue = $sequences[$col];
                                        $isDone = in_array($columnValue, $doneCheck);
                                    @endphp
                                    <th class=" font-normal text-sm pt-2 bg-sgcolorSub">
                                        <div class="colHeader border-l cursor-pointer {{ $isDone ? 'bg-green-200' : 'bg-sgcolorSub' }} border-cursor w-full">
                                            {{ $columnValue }}
                                        </div>
                                    </th>
                                    @endfor
                                </tr>
                            </thead>
                                        
                                    
                            <tbody class="sub-table relative">
                                {{-- NEW TD FOR STUDENTS --}}
                                @for ($row = 1; $row <= $totalRow; $row++)
                                    <tr class="">
                                        <th class=" min-w-[2rem] h-[1.6rem] sticky left-0 font-normal bg-sgcolorSub ">
                                            <div class=" h-full border-t border-cursor text-sm grid place-content-center">
                                                {{ $row }}
                                            </div>
                                        </th>
    
                                
                                        @for ($col = 0; $col < count($sequences); $col++)
                                            @php
                                                if ($names->has($row - ($selectedRow->row ?? $totalRow+1))) {
                                                    $cell = $organizedStudent[$names[$row - $selected]->name_3][$sequences[$col]] ?? null;
                                                } else {
                                                    $cell = $organizedData[$row][$sequences[$col]] ?? null;
                                                }
                                
                                                $rowspan = $cell->rowspan ?? 1;
                                                $colspan = $cell->colspan ?? 1;
                                                if (isset($mergedCells[$row][$col])) {
                                                    continue;
                                                }
                                                for ($r = 0; $r < $rowspan; $r++) {
                                                    for ($c = 0; $c < $colspan; $c++) {
                                                        if ($r !== 0 || $c !== 0) {
                                                            $mergedCells[$row + $r][$col + $c] = true;
                                                        }
                                                    }
                                                }

                                                $textCondition = $row >= $selected && $row <= $endingRow && ( $allName2Null ? $col == $colCount+1 : $col == $colCount+2 );
                                                $textConditions = $selected ? ( $allName2Null ? $col == $colCount+1 : $col == $colCount+2 ) : null;

                                            @endphp
                                            <td id="cell" class=" h-auto text-14px student-cell cursor-cell border-t border-b border-l border-r border-t-cursor  border-b-cursor  border-l-cursor  border-r-cursor caret-transparent {{ $textCondition  ? 'text-start' : 'text-center' }} {{ $textConditions  ? 'text-start bg-bgcolor sticky left-[2rem]' : '  ' }}"
                                                contenteditable="true"
                                                @if($col == 0 && $names->has($row - ($selectedRow->row ?? $totalRow+1)))
                                                    data-room-student="{{ $names[$row-$selected]->name_3 }}"
                                                    data-operation="operation"
                                                @endif
    
                                                @if(!$names->has($row - ($selectedRow->row ?? $totalRow+1)))
                                                    data-sum="operation"
                                                @endif   
                                                @if($col == 0)
                                                    data-room-student="{{ $teacher->teacher_name }}"
                                                @endif             
                                                data-row="{{ $row }}"
                                                data-column="{{ $sequences[$col]}}"
                                                data-id="{{ $cell->id ?? '' }}"
                                                data-merged="{{ ($rowspan > 1 || $colspan > 1) ? 'true' : 'false' }}"
                                                data-original="{{$cell->content ?? ''}}"
                                                @if($rowspan >= 1) rowspan="{{ $rowspan }}" @endif
                                                @if($colspan >= 1) colspan="{{ $colspan }}" @endif
                                                >

                    
                                                
                                                @if ($row >= $selected && $row <= $endingRow)
                                                    @if ($col == $colCount)
                                                        {{-- If name_1 is null, replace it with an incremented number --}}
                                                        {{ $names[$row - $selected]->name_1 !== null && $names[$row - $selected]->name_1 !== '' 
                                                            ? $names[$row - $selected]->name_1 
                                                            : $name1Counter++ }}
                                                    @elseif ($col == $colCount+1)
                                                        {{ $allName2Null ? ($names[$row - $selected]->name_3 ?? '') : ($names[$row - $selected]->name_2 ?? '') }}
                                                    @elseif ($col == $colCount+2 && !$allName2Null)
                                                        {{ $names[$row - $selected]->name_3 ?? '' }}
                                                    @else
                                                        @if (!empty($cell) && is_numeric($cell->content))
                                                            {{ round($cell->content) }}
                                                        @else
                                                            {{ $cell->content ?? '' }}
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (!empty($cell) && is_numeric($cell->content))
                                                        {{ round($cell->content) }}
                                                    @else
                                                        {{ $cell->content ?? '' }}
                                                    @endif
                                                @endif
                                            
                                                
                                            
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor      
                            </tbody>
                        </table>
                    </div>








                    <div id="ratingSection" class="hidden w-full px-8 pt-8 pb-16 font-noto bg-sgcolorSub">
                        <div class="flex justify-center">
                            <div class=" w-auto">
                                <div class=" flex flex-col items-center gap-8">
                                   
                                    @php
                                        $studentsPerTable = 30;
                                        $totalTables = ($numberOfStudents > 0) ? ceil($numberOfStudents / $studentsPerTable) : 1;
                                    @endphp

                                    @for ($tableIndex = 0; $tableIndex < $totalTables; $tableIndex++)
                                    <div id="printable-content-{{ $tableIndex + 1 }}" class="print-container printable-content w-full h-auto bg-white p-4 border border-sgline shadow-md">
                                        <div id="header" class="w-auto flex flex-col whitespace-nowrap">
                                            <!-- Header with Logo & Title in Same Line -->
                                            <div class="headTitle flex justify-center items-center space-x-4 mr-[70px]">
                                                <!-- Logo -->
                                                <div class="w-14">
                                                    <img src="{{ Vite::asset('resources/images/cpsubanner.png') }}" alt="CPSULOGO">
                                                </div>
                                        
                                                <div class="flex flex-col items-center mt-2">
                                                    <h1 class="text-[13px] font-bold text-center">
                                                        CENTRAL PHILIPPINES STATE UNIVERSITY
                                                    </h1>
                                                    <h1 class="font-light text-[12px]">Kabankalan City, Negros Occidental</h1>

                                                </div>
                                            </div>

                                            <div class="flex-1 text-center">
                                                <h1 class="text-[13px] font-bold">GRADING SHEET</h1>
                                            </div>
                                        
                                            <div class="flex-1 flex text-[10px] mt-3">
                                                <div class="w-auto pr-12">
                                                    <h1>Subject Code: <span class="font-bold text-[11px]">{{$room->class_name}}</span></h1>
                                                </div>
                                                <div class="w-auto">
                                                    <h1>Descriptive Title: <span class="font-bold text-[11px]">{{$room->subject}}</span></h1>
                                                </div>
                                            </div>
                                        
                                            <div class="flex-1 flex text-[10px]">
                                                <div class="w-auto pr-12">
                                                    <h1>Course/Year/Section: <span class="font-bold text-[11px]">{{$room->section}}</span></h1>
                                                </div>
                                                <div class="w-auto pr-12 relative">
                                                    <h1>Units: 
                                                        <input type="text" id="units" name="units"
                                                        class="units font-bold text-[11px] border-b border-sgline outline-none"
                                                        oninput="this.classList.toggle('border-b', this.value.trim() === '')"
                                                        value="{{$unit->column ?? ''}}">                                                 
                                                    </h1>
                                                </div>
                                                <div>
                                                    <h1>Semester: 
                                                        <input type="text" id="semester" name="semester"
                                                        class="semester font-bold text-[11px] border-b border-sgline outline-none"
                                                        oninput="this.classList.toggle('border-b', this.value === '')"
                                                        value="{{$sem->column ?? ''}}">                                                 
                                                    </h1>
                                                </div>
                                            </div>
                                        
                                            <div class="flex-1 text-[11px] text-center italic mt-1">
                                                <h1>(Grades must be written in <span class="font-bold">BLACK</span> or <span class="font-bold text-blue-600">BLUE</span> ink. <span class="font-bold">CONDITIONAL OR FAILURE</span> in <span class="font-bold text-red-600">RED</span> ink)</h1>
                                            </div>
                                        </div>          
            
                                        <table class="rating-table w-full content" id="rating-table" data-table="rating-table" data-room-id="{{ $room->id }}" data-teacher-id="{{ $room->teacher_id }}" data-row-start="{{ $selected }}">
                        
                                            <tbody class="sub-table relative">
                                                {{-- NEW TD FOR STUDENTS --}}
                                                @for ($row = 1; $row <= 2; $row++)
                                                    <tr class="leftBorder text-center font-bold text-[12px] {{ $row == 1 ? 'border-t' : '' }} border-cursor">
                                                        @php $colIndex = 0; @endphp
                                                
                                                        <!-- Column A: colspan="2" with different text per row -->
                                                        <td colspan="2" class="firstRow h-auto student-cell border-x border-cursor caret-transparent {{ $row == 1 ? 'font-bold text-[12px]' : 'italic font-normal text-[10px]' }}"
                                                            data-column="{{ $sequences[$colIndex] ?? 'Undefined' }}">
                                                            {{ $row == 1 ? 'NAMES' : '(Arrange alphabetically regardless of sex)' }}
                                                        </td>
                                                        @php $colIndex += 2; @endphp <!-- Skip 2 columns for colspan -->
                                                
                                                        <!-- Columns with rowspan="2", only added in the first row -->
                                                        @if ($row == 1)
                                                            @foreach (['C', 'P', 'A'] as $text)
                                                                <td rowspan="2" class="firstRow h-auto border-x border-cursor caret-transparent"
                                                                    data-column="{{ $sequences[$colIndex] ?? 'Undefined' }}">
                                                                    {{ $text }}
                                                                </td>
                                                                @php $colIndex++; @endphp
                                                            @endforeach
                                                        @endif
                                                
                                                        <!-- Columns with different text per row -->
                                                        @foreach ([['Mid', 'Gr.'], ['N.', 'Eqv.']] as [$firstRow, $secondRow])
                                                            <td class="firstRow h-auto student-cell border-x border-cursor caret-transparent"
                                                                data-column="{{ $sequences[$colIndex] ?? 'Undefined' }}">
                                                                {{ $row == 1 ? $firstRow : $secondRow }}
                                                            </td>
                                                            @php $colIndex++; @endphp
                                                        @endforeach
                                                
                                                        @if ($row == 1)
                                                            @foreach (['C', 'P', 'A'] as $text)
                                                                <td rowspan="2" class="firstRow h-auto border-x border-cursor caret-transparent"
                                                                    data-column="{{ $sequences[$colIndex] ?? 'Undefined' }}">
                                                                    {{ $text }}
                                                                </td>
                                                                @php $colIndex++; @endphp
                                                            @endforeach
                                                        @endif
                                                
                                                        @foreach ([['T.', 'F.Gr.'], ['N.', 'Eqv.'], ['Mid', '40%'], ['Final', '60%'], ['FR %', 'Eqv.'], ['N.', 'Eqv.']] as [$firstRow, $secondRow])
                                                            <td class="firstRow h-auto student-cell border-x border-cursor caret-transparent"
                                                                data-column="{{ $sequences[$colIndex] ?? 'Undefined' }}">
                                                                {{ $row == 1 ? $firstRow : $secondRow }}
                                                            </td>
                                                            {{-- @php $colIndex++; @endphp --}}
                                                        @endforeach
                                                
                                                        @if ($row == 1)
                                                            @foreach (['Credits', 'Remarks', ''] as $text)
                                                                <td rowspan="2" class="firstRow h-auto border-x border-cursor caret-transparent"
                                                                    data-column="{{ $sequences[$colIndex] ?? 'Undefined' }}">
                                                                    {{ $text }}
                                                                </td>
                                                                @php $colIndex++; @endphp
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                @endfor
                                            
                                                {{-- Students Data --}}
                                                @php
                                                    $startRow = $tableIndex * $studentsPerTable;
                                                    $endRow = min($startRow + $studentsPerTable, $numberOfStudents);
                                                @endphp
                                             
                                                @for ($row = $startRow + $selected; $row < $endRow + $selected; $row++)
                                                    <tr class="relative">                             
                                                        @for ($col = 0; $col < count($columnLabels); $col++)
                                                            @php
                                        
                                                                // $cellRating = $organizedDataRatings[$row][$columnLabels[$col]] ?? null;
                                                                $cellRating = $organizedDataStudent[$names[$row - $selected]->name_3][$columnLabels[$col]] ?? null;
                                                                
                                                                $excludedColumns = ['Mid.N.Eqv.', 'F.N.Eqv.', 'FR.N.Eqv'];
                                                                $textCondition = ( $allName2Null ? $col == $colCount+1 : $col == $colCount+1 );
                                                            @endphp
                                                            <td id="cell" class=" h-auto grade-cell border-t border-b border-l border-r border-t-cursor  border-b-cursor  border-l-cursor  border-r-cursor caret-transparent {{ $textCondition  ? 'text-start pl-2 w-60 text-[12.5px]' : 'text-center text-[11px]' }} {{ $col == $colCount+15 || $col == $colCount+11 || $col == $colCount+6 ? ' font-bold' : '' }}"
                                                                contenteditable="true"
                                                                @if($col == 0 && $names->has($row - ($selectedRow->row ?? $numberOfStudents+$selected+1)))
                                                                    data-room-student="{{ $names[$row-$selected]->name_3 }}"
                                                                    data-operation="operation"
                                                                @endif
                    
                                                                @if(!$names->has($row - ($selectedRow->row ?? $numberOfStudents+$selected+1)))
                                                                    data-sum="operation"
                                                                @endif   
                                                                @if($col == 0)
                                                                    data-room-student="{{ $teacher->teacher_name }}"
                                                                @endif             
                                                                data-row="{{ $row }}"
                                                                data-column="{{ $columnLabels[$col]}}"
                                                                data-id="{{ $cellRating->id ?? '' }}"
                                                                data-merged="{{ ($rowspan > 1 || $colspan > 1) ? 'true' : 'false' }}"
                                                                data-original="{{ $cellRating->content ?? '' }}"
                                                                @if($rowspan >= 1) rowspan="{{ $rowspan }}" @endif
                                                                @if($colspan >= 1) colspan="{{ $colspan }}" @endif
                                                                >
                
                                    
                                                                
                                                                {{-- @if ($row >= $selected && $row <= $endingRow) --}}
                                                                    @if ($col == $colCount)
                                                                        {{ $nameCounter++ }}
                                                                    @elseif ($col == $colCount+1)
                                                                        {{ $selected ? ($names[$row - $selected]->name_3 ?? '') : '' }}
                                                                        {{-- {{ $allName2Null ? '' : ($names[$row - $selected]->name_3 ?? '') }} --}}
                                                                    @elseif ($col == $colCount+16)
                                                                        {{ $unit->column ?? '' }}
                                                                    @elseif ($col == $colCount+18)
                                                                        {{ $name2Counter++ }}
                                                                    @else
                                                                        @if (!empty($cellRating) && is_numeric($cellRating->content) && !in_array($columnLabels[$col] ?? '', $excludedColumns))
                                                                            {{ round($cellRating->content) }}
                                                                        @else
                                                                            {{ $cellRating->content ?? '' }}
                                                                        @endif
                                                                    @endif
                                                                {{-- @else
                                                                    {{ $cell->content ?? '' }}
                                                                @endif --}}
                                                            
                                                                
                                                            
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                @endfor
                                            
                                            </tbody>
                                            
                                        </table>
    
                                        <div id="footer" class=" w-full flex flex-col whitespace-nowrap">
                                            <div class=" flex-1 text-[12px] text-center italic mt-1">
                                                <h1 class="font-bold">% Equivalent No. Equivalent 1.0 - Excellent 2.0 - Thorough 3.0 - Lowest Passing Grade 5.0 - Failure</h1>
                                            </div>
                                            <div class=" flex-1 flex mt-1 gap-4">
                                                <div class="gradeEquivalent pl-4 w-[25%] text-[11px] pt-6">
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">97-100</div>
                                                        <div class="font-bold">1.0</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">94-96</div>
                                                        <div class="font-bold">1.25</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">91-93</div>
                                                        <div class="font-bold">1.50</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">88-90</div>
                                                        <div class="font-bold">1.75</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">85-87</div>
                                                        <div class="font-bold">2.0</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">82-84</div>
                                                        <div class="font-bold">2.25</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">79-81</div>
                                                        <div class="font-bold">2.50</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">76-78</div>
                                                        <div class="font-bold">2.75</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">75</div>
                                                        <div class="font-bold">3.0</div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">74-70</div>
                                                        <div class="font-bold">4.0 <span class="font-normal">(Conditional)</span></div>
                                                    </div>
                                                    <div class=" flex gap-2">
                                                        <div class="w-[40%]">69 & Below</></div>
                                                        <div class="font-bold">5.0 <span class="font-normal">(Failure)</span></div>
                                                    </div>
                                                </div>
                                                <div class="signatureNames flex-1 text-[13px] text-center">
                                                    <div>
                                                        <div class=" text-center font-bold mt-2">
                                                            <h1>MIDTERM</h1>
                                                        </div>
                                                        <div class="flex justify-between mt-2">
                                                            <div>
                                                                <h1 class="font-bold">CHRISTI MYR L. CHU</h1>
                                                                <h4 class="text-[12px]">Instructor's Signature</h4>
                                                                <h4 class="text-[12px]">Over Printed Name</h4>
                                                            </div>
                                                            <div>
                                                                <h1 class="font-bold">CHESTER L. COFINO, PhD</h1>
                                                                <h4 class="text-[12px]">Dean, College of Computer Studies</h4>
                                                            </div>
                                                            <div>
                                                                <h1 class="font-bold">RHONELO M. LOBRIQUE, MPA</h1>
                                                                <h4 class="text-[12px]">Registrar's Signature</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" flex-1 text-center">
                                                        <div class="text-center font-bold mt-2">
                                                            <h1>FINAL</h1>
                                                        </div>
                                                        <div class="flex justify-between mt-2">
                                                            <div>
                                                                <h1 class="font-bold">CHRISTI MYR L. CHU</h1>
                                                                <h4 class="text-[12px]">Instructor's Signature</h4>
                                                                <h4 class="text-[12px]">Over Printed Name</h4>
                                                            </div>
                                                            <div>
                                                                <h1 class="font-bold">CHESTER L. COFINO, PhD</h1>
                                                                <h4 class="text-[12px]">Dean, College of Computer Studies</h4>
                                                            </div>
                                                            <div>
                                                                <h1 class="font-bold">RHONELO M. LOBRIQUE, MPA</h1>
                                                                <h4 class="text-[12px]">Registrar's Signature</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" flex justify-end gap-8 mt-1 text-[12px]">
                                                        <div class="text-center">
                                                            <div class=" w-36 h-4 border-b border-black"></div>
                                                            <h1>Date Received</h1>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class=" w-36 h-4 border-b border-black"></div>
                                                            <h1>Posted by</h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" flex justify-evenly mt-2 italic font-bold text-[13px]">
                                                <div>
                                                    <h1>INC- Incomplete</h1>
                                                </div>
                                                <div>
                                                    <h1>NN - No Name</h1>
                                                </div>
                                                <div>
                                                    <h1>NG - No Grade</h1>
                                                </div>
                                                <div>
                                                    <h1>NGS - No Grading Sheet</h1>
                                                </div>
                                            </div>
                                            <div class=" flex justify-evenly mt-2 text-[10px]">
                                                <div>
                                                    <h1>Doc Control Code:CPSU-F-REG-07     Effective Date:09/12/2018     Page No.: {{$tableIndex + 1}} of {{$totalTables}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor

                                </div>
                            </div>
                        </div>
                    </div>





                        <div class=" fixed bottom-0 left-0 right-0">
                            <div class="flex h-[2.3rem] pb-2 bg-gray-100 gap-4 px-8 border-t border-sgline">
                                <div class="recordTabDiv bg-bgcolor px-4 border-x border-b-4 border-mainText hover:border-b-4 hover:border-mainText">
                                    <button id="recordTab" class="cursor-pointer">Records</button>
                                </div>
                                <div class="px-4 hover:border-b-4 hover:border-mainText cursor-pointer">
                                    <a href="{{ route('attendance.room', [ 'id' => $encodedID, 'key' => 'attendance-table']) }}">Attendance</a>
                                </div>
                                <div class="ratingTabDiv px-4 hover:border-b-4 hover:border-mainText">
                                    <button id="ratingTab" class="cursor-pointer">Grading Sheet</button>
                                </div>

                                <div class=" flex-1 flex gap-2 bg-gray-100">
                                    <div class="flex-1 flex justify-center gap-8">
                                        <div class=" relative">
                                            <div class=" flex gap-2 items-cent px-2 py-1">
                                                <h1 class="font-bold">ROW OF TOTAL SCORE : </h1>
                                                <input type="text" id="rowOfTotalScore" name="rowOfTotalScore" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 text-center" value="{{$getRowOfTotal->column ?? ''}}">
                                            </div>
                                        </div>
                                        <div class=" relative">
                                            <div class="dropMidTerm flex gap-2 items-center hover:bg-gray-200 cursor-pointer px-2 py-1 rounded-md">
                                                <h1 class="font-bold">MID-TERM</h1>
                                                <i class="bx bx-caret-up"></i>
                                            </div>
                                            <div class="showDropMidTerm hidden">
                                                <div class=" flex flex-col gap-4 p-3 rounded-md shadow-sm bg-gray-100 absolute -top-[11rem] text-nowrap">
                                                    <div class="flex items-center gap-1">
                                                        <label for="M.C" class="font-semibold">C :</label>
                                                        <input type="text" id="M.C" name="M.C" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="M.P" class="font-semibold">P :</label>
                                                        <input type="text" id="M.P" name="M.P" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="M.A" class="font-semibold">A :</label>
                                                        <input type="text" id="M.A" name="M.A" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <div class="flex items-center gap-1">
                                                            <label for="MidGr." class="font-semibold">Mid Grade :</label>
                                                            <input type="text" id="MidGr." name="MidGr." class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center"  value="{{$getMidGr->column ?? ''}}">
                                                        </div>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full left-0 transform -translate-x-0 -mt-20  shadow-md flex-col items-center transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Midterm Grade Column</h1>
                                                                <p class="text-[0.8rem]">Specify the midterm grade column.</p>                                                        
                                                            </div>  
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <div class="dropFinalTerm flex gap-2 items-center hover:bg-gray-200 cursor-pointer px-2 py-1 rounded-md">
                                                <h1 class="font-bold">FINAL-TERM</h1>
                                                <i class="bx bx-caret-up"></i>
                                            </div>
                                            <div class="showDropFinalTerm hidden">
                                                <div class="flex flex-col gap-4 p-3 rounded-md shadow-sm bg-gray-100 absolute -top-[11rem] text-nowrap">
                                                    <div class="flex items-center gap-1">
                                                        <label for="F.C" class="font-semibold">C :</label>
                                                        <input type="text" id="F.C" name="F.C" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="F.P" class="font-semibold">P :</label>
                                                        <input type="text" id="F.P" name="F.P" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="F.A" class="font-semibold">A :</label>
                                                        <input type="text" id="F.A" name="F.A" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <div class="flex items-center gap-1">
                                                            <label for="T.F.Gr." class="font-semibold">Final Grade :</label>
                                                            <input type="text" id="T.F.Gr." name="T.F.Gr." class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center"  value="{{$getFinGr->column ?? ''}}">
                                                        </div>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full -left-24 transform -translate-x-0 -mt-20  shadow-md flex-col items-center transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Finalterm Grade Column</h1>
                                                                <p class="text-[0.8rem]">Specify the finalterm grade column.</p>                                                        
                                                            </div>  
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="flex-1 flex justify-start gap-2">
                                        <div class="relative">
                                            <div class="dropFinalTerm flex gap-2 items-center hover:bg-gray-200 cursor-pointer px-2 py-1 rounded-md">
                                                <h1 class="font-bold">FINAL-TERM</h1>
                                                <i class="bx bx-caret-up"></i>
                                            </div>
                                            <div class="showDropFinalTerm hidden">
                                                <div class="flex gap-4 p-3 rounded-md shadow-sm bg-gray-100 absolute -top-[3.5rem] text-nowrap">
                                                    <div class="flex items-center gap-1">
                                                        <label for="F.C" class="font-semibold">C :</label>
                                                        <input type="text" id="F.C" name="F.C" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="F.P" class="font-semibold">P :</label>
                                                        <input type="text" id="F.P" name="F.P" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="F.A" class="font-semibold">A :</label>
                                                        <input type="text" id="F.A" name="F.A" class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center">
                                                    </div>
                                                    <div class="relative group z-50" onmouseenter="unhideTooltip(this)" onmouseleave="hideTooltip(this)" onclick="hideTooltip(this)">
                                                        <div class="flex items-center gap-1">
                                                            <label for="T.F.Gr." class="font-semibold">Final Grade :</label>
                                                            <input type="text" id="T.F.Gr." name="T.F.Gr." class="w-8 h-5 outline-none bg-gray-100 border-b border-gray-600 focus:border-blue-500 text-center"  value="{{$getFinGr->column ?? ''}}">
                                                        </div>
                                                        <div class="tooltip opacity-0 pointer-events-none absolute top-full -left-24 transform -translate-x-0 -mt-20  shadow-md flex-col items-center transition-opacity duration-300">
                                                            <div class="relative bg-bgcolor text-sm border border-cursor whitespace-nowrap py-1 px-2">
                                                                <h1 class="text-[0.8rem] font-bold">Finalterm Grade Column</h1>
                                                                <p class="text-[0.8rem]">Specify the finalterm grade column.</p>                                                        
                                                            </div>  
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>

                            </div>
                        </div>


                </div>
            </div>



        </section>

    </section>


    <script>
        window.formulas = @json($usedFormula);
        let startRow = @json($selected);
        let midTermCol = @json($getMidGr->column ?? null);
        let finTermCol = @json($getFinGr->column ?? null);
        const totalNumberOfStudents = @json($numberOfStudents);

        let showTimeout, hideTimeout;

        function unhideTooltip(element) {
            clearTimeout(hideTimeout);
            const tooltip = element.querySelector('.tooltip');

            showTimeout = setTimeout(() => {
                tooltip.classList.remove('pointer-events-none');
                tooltip.style.opacity = 0;
                setTimeout(() => {
                    tooltip.style.opacity = 1;
                }, 10);
            }, 2000);
        }

        function hideTooltip(element) {
            clearTimeout(showTimeout);
            const tooltip = element.querySelector('.tooltip');
            
            hideTimeout = setTimeout(() => {
                tooltip.style.opacity = 0;
                setTimeout(() => {
                    tooltip.classList.add('pointer-events-none');
                }, 100);
            }, 0); 
        }

        document.addEventListener("DOMContentLoaded", function() {
            const unitInput = document.querySelectorAll(".units");
            const semInput = document.querySelectorAll(".semester");
      
            unitInput.forEach(unit => {            
                if (unit.value.trim() !== "") {
                    unit.classList.remove("border-b"); 
                }
            });
            semInput.forEach(sem => {
                if (sem.value.trim() !== "") {
                    sem.classList.remove("border-b");
                }
            });
        });
    </script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- <script src="https://chir.ag/projects/ntc/ntc.js"></script> --}}
    
</body>

</html>
