// Clicking Plus Button
document.addEventListener('click', (event) => {
    const clickedBtn = event.target.closest('.btn-plus, .btn-user');
    const choices = document.querySelector('.choices');
    const log = document.querySelector('.log-user');
  
    if (clickedBtn) {
        if (clickedBtn.classList.contains('btn-plus')) {
            choices.classList.toggle('hidden');
            log.classList.add('hidden');
        } else if (clickedBtn.classList.contains('btn-user')) {
            log.classList.toggle('hidden');
            choices.classList.add('hidden');
        }
    } else {
        // choices.classList.add('hidden');
        log.classList.add('hidden');
    }
  });




// SEARCH START HERE
const search = document.querySelector('.search-btn');

search.addEventListener('focus', () => {
    search.classList.add('search');
});

search.addEventListener('blur', () => {
    search.classList.remove('search');
});



// SEARCH FOR TEACHER HERE
$(document).ready(function() {
    const $classList = $('#class-list');
    const $search = $('#search');
    const originalContent = $classList.html();

    const fetchClasses = async (query) => {
        try {
            const response = await fetch(`/search-teacher-class?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            let content = '';

            if (data.length > 0) {
                data.forEach((classItem) => {
                    content += `
                    <div class="class-item h-[14rem] flex rounded-md shadow-md relative hover:-translate-y-1 transition hover:shadow-lg">
                        <div class=" flex-1 h-full border-2 border-mainText rounded-l-md">
                            <div class=" w-full h-full flex flex-col justify-between bg-gray-100 file-vertical shadow-sm relative overflow-visible rounded-l-md p-5">
                                <div class=" w-full">
                                    <div class=" w-full mb-1">
                                        <h3 class=" text-lg text-mainText">${classItem.section}</h3>
                                    </div>
                                    <div class=" w-full">
                                        <h3 id="class-name"truncated class=" text-4xl text-mainText">${classItem.class_name}</h3>
                                    </div>
                                </div>
                                <div class=" w-full">
                                    <h3 id="subject" class="truncated text-lg text-mainText">${classItem.subject}</h3>
                                </div>
                            </div>
                        </div>
                        <div class=" w-[15%] h-full flex flex-col justify-between bg-bgcolor rounded-r-md border-y-2 border-r-2 border-mainText py-5">
                            <div class=" w-full grid items-center">
                                <button class=" three-dot">
                                    <i class="bx bx-dots-vertical-rounded text-2xl text-mainText"></i>
                                </button>
                                <div class="hidden delButton absolute top-[11%] right-[12%] bg-bgcolor py-2 px-2 rounded-md border border-sgline">
                                    <div class=" flex flex-col text-mainTexts">
                                        <a href="" class=" w-full px-8 py-2 text-center hover:bg-navHover rounded-md">Move</a>
                                        <button class="delete-btn px-8 py-2 w-full hover:bg-navHover rounded-md" data-id="${classItem.id}">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full flex justify-center -mb-2">
                                <a href="/teacher-room/records/${classItem.id}" class="">
                                    <i class='bx bxs-folder-open text-4xl text-mainText'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    `;
                });
            } else {
                content = '<h1>No classes found</h1>';
            }

            $classList.html(content);
        } catch (error) {
            console.error('Error:', error);
        }
    };

    const debounce = (func, delay) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const handleSearch = debounce(async (event) => {
        const query = event.target.value.trim().toLowerCase();
        if (query) {
            await fetchClasses(query);
        } else {
            $classList.html(originalContent);
        }
    }, 300);

    $search.on('input', handleSearch);

    $classList.on('click', '.three-dot', function() {
        $(this).next('.delButton').toggleClass('hidden');
    });

    // Handle delete button
    $classList.on('click', '.delete-btn', function() {
        const classId = $(this).data('id');
        console.log(`Class ID to delete: ${classId}`);
    
        if (confirm('Are you sure you want to delete this room?')) {
            $.ajax({
                url: `/teacher-room/${classId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    alert('Class deleted successfully');
                    $(`button[data-id="${classId}"]`).closest('.class-item').remove();
                },
                error: function(xhr) {
                    alert(`An error occurred while deleting the class with ID ${classId}`);
                }
            });
        }
    });
    
});



// SEARCH STUDENT HERE
$(document).ready(function() {
    const $classList = $('#class-student');
    const $search = $('#search-student');

    const fetchClasses = (query) => {
        $.ajax({
            url: '/search-student-class',
            type: 'GET',
            data: { query },
            success: (data) => {
                let content = '';

                if (data.length > 0) {
                    data.forEach((classItem) => {
                        content += `
                            <div class="class-item bg-mainText rounded-md p-5 shadow-md relative">
                                <div class="flex flex-col gap-y-1 select-none">
                                    <h3 class="text-lg text-bgcolor">${classItem.section}</h3>
                                    <h1 class="text-3xl text-bgcolor">${classItem.class_name}</h1>
                                </div>
                                <h5 class="text-bgcolor pt-8 cp:pt-12">${classItem.teacher_name}</h5>
                                <button class="absolute top-0 right-0 mt-5 mr-4">
                                    <i class="bx bx-dots-vertical-rounded text-2xl text-bgcolor"></i>
                                </button>
                                <a href="/student-room/${classItem.room_id}" class="absolute right-0 bottom-0 mb-3 mr-5">
                                    <i class='bx bxs-folder-open text-3xl text-bgcolor'></i>
                                </a>
                            </div>
                        `;
                    });
                } else {
                    content = '<h1>No classes found</h1>';
                }

                $classList.html(content);
            },
            error: (error) => {
                console.error('Error:', error);
            }
        });
    };

    // Throttle function to limit the rate of AJAX calls
    const throttle = (func, delay) => {
        let lastCall = 0;
        return function(...args) {
            const now = new Date().getTime();
            if (now - lastCall < delay) {
                return;
            }
            lastCall = now;
            return func(...args);
        };
    };

    const handleSearch = (event) => {
        const query = event.target.value.trim().toLowerCase();

        if (query === '') {
            // If the query is empty, reset to show all classes
            fetchClasses('');
        } else {
            // Otherwise, perform the search
            fetchClasses(query);
        }
    };

    $search.on('keyup', throttle(handleSearch, 150)); // Shorter throttle delay for more responsive feedback
});