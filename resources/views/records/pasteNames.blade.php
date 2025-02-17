<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Track Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/table.css', 'resources/js/app.js', 'resources/js/users.js', 'resources/js/pasteNames.js', 'resources/js/imports/uploadPdf.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-full h-screen relative overflow-y-auto overflow-x-hidden">

        {{-- <div class=" fixed bottom-0 left-0 right-0 h-24 z-10 flex justify-center">
            <div class=" w-[70rem] pr-4 flex justify-center">
                <div class=" w-[50rem] flex bg-slate-500">
                    <input type="checkbox">
                    <h1>Check all</h1>
                </div>
            </div>
        </div> --}}

        @if(!$getNames->isEmpty())
        <div class="addStudent hidden fixed inset-0 bg-mainText bg-opacity-75 z-30">
            <div class=" w-full h-full grid place-content-center">
                <div class="hideHidden w-[50rem] rounded-md bg-bgcolor px-8 pb-8 pt-8">
                    <div class="w-full pb-8 relative flex justify-center items-center">
                        <h1 class="text-3xl font-bold">Student Names</h1>
                    </div>
                    <form action="{{ route('add-student', ['id' => $room->id]) }}" method="POST">
                        @csrf
                        <textarea id="nameAddInput" class="w-full min-h-[25rem] max-h-[25rem] rounded-md p-8 border border-subtText outline-none" name="pasted_text" rows="5" cols="50" placeholder="Paste names here..."></textarea>
                        <div class="w-full flex justify-center mt-[5%]">
                            <button class="bg-mainText text-bgcolor font-semibold px-[8%] py-[2%] rounded-md border-2 border-mainText hover:bg-bgcolor hover:text-mainText" type="submit">Submit</button>
                        </div>
                    </form>
                    {{-- <form action="{{ route('add-student') }}" method="POST">
                        @csrf
                        <table class=" w-full self-center h-full">
                            <body>         
                                <tr class=" text-xl">
                                    <th class="pb-8 text-start pl-8">Student Id</th>
                                    <th class="pb-8 text-start pl-8">Student Name</th>
                                </tr>
                                <tr>
                                    <td class=" border border-cursor">
                                        <input class="w-full  text-start px-8 outline-none" type="text" id="studentId" name="studentId">
                                    </td>
                                    <td class=" border border-cursor">
                                        <input class="w-full  text-start px-8 outline-none" type="text" id="studentName" name="studentName">
                                    </td>
                                </tr>
                            </body>
                        </table>
                        <input type="hidden" name="roomId" value="{{ $roomId->room_id }}">
                        <div class=" w-full flex justify-end mt-8">
                            <button class="py-3 px-8 bg-mainText rounded-md text-bgcolor font-semibold">Add</button>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
        @endif

        <div class=" w-full flex flex-col sticky top-0 left-0 right-0 z-20 bg-bgcolor">
            {{-- Header --}}
            <header class=" h-[5.5rem] w-screen flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
                <div class="flex items-center">
                    <div class="relative group">
                        <a href=" {{ route('teacher.room', $encodedID) }} " class=" cp:text-3xl">
                            <span class='flex gap-2 text-[2rem] text-subtText hover:text-mainText'>
                                <i class="bx bx-arrow-back text-4xl"></i>
                            </span>
                        </a>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                            <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                <h1>Back</h1>
                                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-700 bg-opacity-75"></div>
                            </div>  
                        </div>
                    </div>  
                </div>
                <div class="flex gap-4 relative">
                    <button class="btn-user text-3xl w-11 h-11 rounded-full cp:text-2xl bg-mainText">
                        <h1 class=" text-2xl text-bgcolor">{{ substr(auth()->user()->username, 0, 1) }}</h1>
                    </button>
                    <div class="log-user bg-bgcolor hidden absolute left-0 ml-[1rem] mt-14 rounded-md shadow-md z-10">
                        <div class="flex flex-col">
                            <a href="{{ route('logout') }}" class=" text-subtText hover:bg-hoverColor hover:text-mainText px-4 py-2 text-start">Logout</a>
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="w-full flex justify-center">
                @if($getNames->isEmpty())
                <div class="w-[50rem] relative flex justify-start items-center">
                    <h1 class="text-3xl font-bold">Student Names</h1>
                    <div type="button" class="uploadPdfButton absolute right-0 top-0 bottom-0 flex items-center gap-1 cursor-pointer">
                        <i class='bx bx-import text-3xl' ></i>
                        <h1 class="text-lg font-medium">Upload</h1>
                    </div>
                </div>
                
                @else
                <div class=" w-[50rem] mb-2 relative flex justify-between items-center">
                    <h1 class="text-3xl font-bold">Student Names</h1>
                    <div class="hidden showPasteButtons">
                        <div class="flex items-center gap-4">
                            <div class="flex gap-2">
                                <input class="checkAllStudent hover:cursor-pointer" type="checkbox">
                                <button>Check all</button>
                            </div>
                            <div>
                                <button type="button" onclick="submitDeletionForm()">Delete</button>
                            </div>
                            <div>
                                <button class="cancelStudentButton">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 pasteButtons">
                        <div class="relative group">
                            <button class="addStudentButton focus:outline-none">
                                <i class="bx bx-plus text-[1.7rem] hover:text-green-500"></i>
                            </button>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                                <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                    <h1>Add</h1>
                                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-mainText bg-opacity-75"></div>
                                </div>
                            </div>
                        </div>  
                        <div class="relative group">
                            <button class="delStudentIcon focus:outline-none">
                                <i class="bx bx-trash text-[1.7rem] hover:text-red-500"></i>
                            </button>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mt-3 hidden group-hover:flex flex-col items-center mb-3">
                                <div class="relative bg-mainText bg-opacity-85 text-white text-sm rounded-lg py-2 px-4">
                                    <h1>Delete</h1>
                                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-mainText bg-opacity-75"></div>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                @endif
            </div>
        </div>
              

        {{-- Body --}}
        <section class=" w-full relative flex-1">

            {{-- @if (session('namePasteError'))
            <div id="success-alert" class="absolute flex justify-self-center top-0 right-0 left-0 alert z-50 transition-all duration-500 ease-in-out opacity-100">
                <div class="py-2 px-16 rounded-md  bg-bgcolor border-2 border-sgcolor shadow-sm transition-all duration-500 ease-in-out namePasteError">
                    <h1 class="text-red-500 text-lg text-center">
                        Column shold not be more than three(3)...
                    </h1>
                </div>
            </div>
            @endif --}}

            <div class=" w-full h-full flex flex-col gap-8 mb-16">
                <div class=" w-[50rem] self-center h-full">
                    @if($getNames->isEmpty())

                        <div id="showImportPdf" class="showImportPdf hidden">
                            <div class=" w-full grid place-content-center mt-8 min-h-[25rem] max-h-[25rem]">
                                <div class=" w-full h-full grid place-content-center">
                                    <div class=" bg-bgcolor flex flex-col rounded-sm px-5 py-3 border border-sgline shadow-md">
                                        <form action="{{ route('importPdf', $room->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class=" flex flex-col gap-3">
                                                <div class=" flex flex-col">
                                                    <label class=" text-lg pb-7 font-bold" for="file">Upload Pdf File:</label>
                                                    <input type="file" name="pdf_file" accept=".pdf" required>
                                                </div>
                                            </div>
                                            <div class=" mt-8 flex justify-end gap-2">
                                                <div class=" cancelImport text-center cursor-default bg-slate-200 w-24 text-sm py-0.5">Cancel</div>
                                                <button type="submit" class="bg-slate-200 w-24 text-sm py-0.5">Ok</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                            {{-- <div class="">
                                <form action="{{ route('importPdf', $room->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="pdf_file" accept=".pdf" required>
                                    <button type="submit">Upload</button>
                                </form>
                            </div> --}}
                        </div>
                        <div class=" pasteNames">
                            <form action="{{ route('paste.student.names', ['id' => $room->id]) }}" method="POST">
                            @csrf
                                <textarea id="nameInput" class="w-full mt-8 min-h-[25rem] max-h-[25rem] rounded-md p-8 border border-subtText outline-none" name="pasted_text" rows="5" cols="50" placeholder="Paste names here..."></textarea>
                                <div class="w-full flex justify-center mt-[5%]">
                                    <button class="bg-mainText text-bgcolor font-semibold px-[8%] py-[2%] rounded-md  border-2 border-mainText hover:bg-bgcolor hover:text-mainText" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Container for Deletion -->
                        <form class="deletionOfStudentNames" action="{{ route('pasteRoom.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                            <div class="hideDelStudent   w-full flex">
                                <table id="studentIdNo" class="w-full self-center h-full">
                                    <tbody>
                                        @php $count = 1; @endphp
                                        @foreach ($getNames as $studentNum => $names)
                                        <tr class="relative">
                                            <!-- Checkbox for Deleting Students -->
                                            <td class="delStudentButton hidden px-4 text-red-500 absolute -top-[0.01rem] -left-[3.2rem]">
                                                <input class="checkDeleteStudent hover:cursor-pointer" type="checkbox" name="ids[]" value="{{ $names->id }}">
                                            </td>
                                    
                                            <!-- User Check -->
                                            <td class="isUserCheck px-4 text-green-500 absolute -top-[0.01rem] -left-[3.2rem]">
                                                @php
                                                    $studentNames = strtolower(trim($names->name_3)); // Trim and convert to lowercase
                                                @endphp
                                    
                                                @foreach ($getRoomAndName as $roomAndName)
                                                    @php
                                                        $studentEntered = strtolower(trim($roomAndName->student_name)); // Trim and convert to lowercase
                                                    @endphp
                                    
                                                    @if ($studentNames == $studentEntered)
                                                        <i class="bx bx-check text-2xl"></i>
                                                        @break <!-- Break loop if a match is found -->
                                                    @endif
                                                @endforeach
                                            </td>
                                    
                                            <!-- Student Count -->
                                            <td class="border border-cursor px-8">{{ $count++ }}</td>
                                    
                                            <!-- Editable Student Name -->
                                            <td class="border border-cursor border-r-0 text-start px-8" 
                                                contenteditable="true" 
                                                data-id="{{ $names->id ?? '' }}" 
                                                data-room-id="{{ $names->room_id }}">
                                                {{ $names->name_2 }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    </tbody>
                                </table>
                
                                <table id="studentName" class="w-full self-center h-full">
                                    <tbody>
                                        @foreach ($getNames as $names)
                                            <tr>
                                                <td class="border border-cursor text-start px-8" contenteditable="true" data-id="{{ $names->id ?? '' }}" data-room-id="{{ $names->room_id }}">{{ $names->name_3 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </section>

    </section>

    <script>
        function submitDeletionForm() {
            const form = document.querySelector('.deletionOfStudentNames');
            
            if (confirm('Are you sure you want to delete the selected items?')) {
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');

            if (successAlert) {
                const alertDelete = successAlert.querySelector('.namePasteError');

                setTimeout(() => {
                    successAlert.classList.add('top-[30%]', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    successAlert.classList.remove('opacity-100');
                }, 1500);

                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 2000);
            }

            // PASTING FOR NAMEINPUT
            document.getElementById('nameInput').addEventListener('paste', function (e) {
                e.preventDefault();

                const pastedData = e.clipboardData.getData('text');

                fetch('/structure-names', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pastedText: pastedData })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server Response:', data);
                    document.getElementById('nameInput').value = data.structuredNames;
                })
                .catch(error => console.error('Error:', error));
            });

        });

        document.addEventListener('DOMContentLoaded', function() {
            // PASTING FOR NAMEADDINPUT
            document.getElementById('nameAddInput').addEventListener('paste', function (e) {
                e.preventDefault();
    
                const pastedData = e.clipboardData.getData('text');
    
                fetch('/structure-names', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pastedText: pastedData })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nameAddInput').value = data.structuredNames;
                })
                .catch(error => console.error('Error:', error));
            });
        });


    </script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
</body>

</html>
