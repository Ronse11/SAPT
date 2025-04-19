<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Class Attendance</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/table.css', 'resources/js/app.js', 'resources/js/teacherTable/sample.js', 'resources/js/teacherTable/merging.js', 'resources/js/teacherTable/applyBorder.js', 'resources/js/teacherTable/addStudentsNames.js', 'resources/js/teacherTable/applyFontStyle.js', 'resources/js/teacherTable/applyColors.js', 'resources/js/teacherTable/calculation.js', 'resources/js/teacherTable/logic.js', 'resources/js/teacherTable/colorLogic.js', 'resources/js/teacherTable/dragSum.js', 'resources/js/teacherTable/adjustFontSize.js', 'resources/js/teacherTable/doneCheck.js', 'resources/js/imports/import.js', 'resources/js/imports/export.js', 'resources/js/print/print.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <section class=" w-screen h-screen grid grid-cols-6 grid-rows-15 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 bg-bgcolor relative overflow-hidden">

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
                $selected = 0;
                $colCount = 0;
            }

            $allName2Null = collect($names)->every(fn($name) => empty($name->name_2));
            $name1Counter = 1;
        @endphp

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

        <div class=" w-screen flex flex-col row-start-1 row-span-2 z-40">
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
            
                                </div>
                                <div class=" w-full flex gap-2">        
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
                                    <a href="{{ route('paste-name', $room->id) }}" class="w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">
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
                                                        <input type="hidden" name="tableID" value="attendance-table">
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
                                                    <input type="hidden" name="tableID" value="attendance-table">
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
                                    <button id="print-button" class=" w-full h-full grid place-content-center bg-sgline border border-sgcolor hover:border-cursor hover:bg-gray-300 px-3">Print</button>
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
                            <form action="{{ route('total-row-col', [ 'id' => $encodedID, 'key' => 'attendance-table']) }}" method="POST" autocomplete="off">
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
                
                </div>
            </header>

        </div>


        {{-- Body --}}
        <section id="sample" class="teacher-choices row-start-2 col-start-1 col-span-6 cp:col-start-1 cp:col-span-16 cp:row-start-3 cp:row-span-13 small-bp ">
            

            <div class="row-start-3 w-full h-full overflow-x-auto zoom-container relative text-sm">
                <div class="t-ble w-full">
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

                    {{-- <div class="loadingMessage hidden fixed inset-0 z-50">
                        <div class=" w-full h-full grid place-content-center">
                            <h1 class=" text-base text-green-500 px-20 py-4 bg-bgcolor border border-sgline shadow-md rounded-md">Loading...</h1>
                        </div>
                    </div>
                    <div id="loadingLoad" class=" bg-slate-400 fixed inset-0 z-50">
                        <div class=" w-full h-full grid place-content-center">
                            <h1 class=" text-base text-green-500 px-20 py-4 bg-bgcolor border border-sgline shadow-md rounded-md">Loading...</h1>
                        </div>
                    </div> --}}
                    <div id="showImport" class="showImport hidden fixed inset-0 z-50">
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
                    $columnRow = 100;
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
                    for ($i = 0; $i < $totalColumn; $i++) {  // Adjust the count as needed
                        $sequences[] = $nextSequence($i);
                    }

                    $organizedData = [];
                    // $organizedData = [];
                    foreach ($data as $cell) {
                        $organizedData[$cell->row][$cell->column] = $cell;
                        $organizedStudent[$cell->student_name][$cell->column] = $cell;
                    }
                    @endphp


                    <table class="main-table w-full" id="attendance-table" data-table="attendance-table" data-room-id="{{ $room->id }}" data-teacher-id="{{ $room->teacher_id }}" data-row-start="{{ $selected }}">
            
                            <tbody class="sub-table relative">
                                <thead class="t-head sticky top-0 z-10">
                                    <tr>
                                        <th class="border-none bg-sgcolorSub sticky top-0 left-0 pt-2">
                                        </th>
                                        @for ($col = 0; $col < count($sequences); $col++)
                                            @php
                                                $columnValue = $sequences[$col];
                                                $isDone = in_array($columnValue, $doneCheck);
                                            @endphp
                                            <th class="font-normal bg-sgcolorSub text-sm pt-2">
                                                <div class="colHeader border-l cursor-pointer {{ $isDone ? 'bg-green-200' : 'bg-sgcolorSub' }} border-cursor w-full">
                                                    {{ $sequences[$col] }}
                                                </div>
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
            
                        
            
                                {{-- NEW TD FOR STUDENTS --}}
                                @for ($row = 1; $row <= $totalRow; $row++)
                                    <tr class="relative">
                                        <th class="min-w-[2rem] h-[1.6rem] font-normal bg-sgcolorSub sticky left-0">
                                            <div class=" w-full h-full border-t border-cursor text-sm grid place-content-center">
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
                                                    {{ $cell->content ?? '' }}
                                                @endif
                                            
                                                
                                            
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            
                            </tbody>
                    </table>
                </div>
            </div>
            


        </section>

    </section>


    <script>
        window.formulas = @json($usedFormula);
        let startRow = @json($selected);

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


    </script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://chir.ag/projects/ntc/ntc.js"></script>
</body>

</html>
