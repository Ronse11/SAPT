<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Track Room</title>

    @vite(['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/css/table.css', 'resources/js/app.js', 'resources/js/settingUpdate.js'])
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <section class=" w-screen h-screen grid grid-cols-6 grid-rows-8 gap-x-16 gap-y-0 tablet:grid-cols-16 cp:grid-cols-12 tablet:gap-y-4 bg-bgcolor">

        {{-- Header --}}
        <header class=" w-screen row-start-1 row-span-1 flex justify-between items-center shadow tablet:shadow-none px-5 cp:px-16 z-40">
            <div class="flex gap-4 items-center">
                <a href=" {{ route('teacher.room', $encodedID) }} " class=" cp:text-3xl">
                    <span class='flex gap-2 text-[2rem] text-subtText hover:text-mainText'>
                        <i class="bx bx-arrow-back text-4xl"></i>
                    </span>
                </a>
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

        {{-- Body --}}
        <section class="teacher-choices row-start-2 row-span-6 col-start-1 col-span-6 px-4 tablet:col-start-3 tablet:col-span-11 tablet:row-start-2 tablet:row-span-6 tablet:pb-4 cp:col-start-2 cp:col-span-10 small-bp">
                <div class=" h-full flex flex-col cp:items-start tablet:mr-16 gap-12">
                    <div class="w-[80%] self-center flex items-start">
                        <h1 class=" text-4xl font-semibold text-mainText">Settings</h1>
                    </div>
                    <div class=" flex flex-col w-[80%] self-center gap-8">
                        <div class="w-full flex flex-col">
                            <h1 class="text-2xl font-medium text-mainText mb-2">Class Information</h1>
                            <div class="w-full flex flex-col p-4 gap-6">
                                <div class=" w-full flex gap-16">
                                    <div class="flex-1 flex flex-col gap-2">
                                        <label class="text-lg font-medium" for="class_name">Subject Code:</label>
                                        <input class="p-2 outline-none bg-sgcolor border-b-2 border-sgcolor focus:border-mainText" type="text" name="class_name" id="class_name" value="{{ $record->class_name }}" data-id="{{ $record->id }}" data-name="class_name">
                                    </div>
                                    <div class="flex-1 flex flex-col gap-2">
                                        <label class="text-lg font-medium" for="subject">Descriptive Title:</label>
                                        <input class="p-2 outline-none bg-sgcolor border-b-2 border-sgcolor focus:border-mainText" type="text" name="subject" id="subject" value="{{ $record->subject }}" data-id="{{ $record->id }}" data-name="subject">
                                    </div>
                                </div>
                                <div class=" w-full flex gap-16">
                                    <div class="flex-1 flex flex-col gap-2">
                                        <label class="text-lg font-medium" for="section">Course/Year/Section:</label>
                                        <input contenteditable="true" class="p-2 outline-none bg-sgcolor border-b-2 border-sgcolor focus:border-mainText" type="text" name="section" id="section" value="{{ $record->section }}" data-id="{{ $record->id }}" data-name="section">
                                    </div>
                                    <div class="flex-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full border-b-2 border-subtText"></div>
                        <div class="w-full flex gap-16">
                            <div class="flex-1">
                            <h1 class="text-2xl font-medium text-mainText">Track Code</h1>
                            <div class="py-4 pl-4">
                                <div class="w-full py-2 pr-2 hover:bg-sgcolor transition">
                                    <button id="copyButton" class="w-full flex justify-between">
                                        <h1 id="roomCode" class="font-medium px-2"> {{ $record->room_code }} </h1>
                                        <i class="bx bxs-copy text-2xl"></i>
                                    </button>
                                </div>
                            </div>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-2xl font-medium text-mainText">Invite Link</h1>
                                <div class="py-4 pl-4">
                                    <div class="w-full py-2 pr-2 hover:bg-sgcolor transition">
                                        <button id="copyButtonLink" class="w-full flex justify-between">
                                            <h1 id="roomLink" class="font-medium px-2"> {{ $shareableLink }} </h1>
                                            <i class="bx bxs-copy text-2xl"></i>
                                        </button>
                                    </div>
                                </div>
                                </div>
                            <div class="flex-1">
                            <h1 class="text-2xl font-medium text-mainText">Role</h1>
                                <div class="w-full p-2 py-5">
                                    <h1 class=" font-medium"> {{ $role->role }} </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function copyRoomCodeToClipboard() {
                const roomCode = document.getElementById("roomCode").innerText;
                const tempInput = document.createElement("input");
                tempInput.style.position = "absolute";
                tempInput.style.left = "-9999px";
                tempInput.value = roomCode;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                alert("Copied to clipboard: " + roomCode);
            }

            function copyLinkToClipboard() {
                const shareableLink = document.getElementById("roomLink").innerText;
                const tempInput = document.createElement("input");
                tempInput.style.position = "absolute";
                tempInput.style.left = "-9999px";
                tempInput.value = shareableLink;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                alert("Copied to clipboard: " + shareableLink);
            }
    
            document.getElementById("copyButton").addEventListener("click", copyRoomCodeToClipboard);
            document.getElementById("copyButtonLink").addEventListener("click", copyLinkToClipboard);
        });
    </script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
</body>
</html>
