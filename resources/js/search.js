document.addEventListener('DOMContentLoaded', () => {
  const userBtn = document.querySelector('.btn-user');
  const logUser = document.querySelector('.log-user');
  
  logUser?.classList.add('hidden');
  
  userBtn?.addEventListener('click', (event) => {
    event.stopPropagation();
    logUser?.classList.toggle('hidden');
  });
  
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.log-user, .btn-user')) {
      logUser?.classList.add('hidden');
    }
  });
});



// Utility functions
const utils = {
    // Modern debounce implementation
    debounce(callback, delay = 500) {
      let timeout;
      return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => callback(...args), delay);
      };
    },
    
    // Create toast notification
    createNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded shadow-lg z-50 opacity-0 transition-opacity duration-300`;
      notification.textContent = message;
      
      document.body.appendChild(notification);
      
      // Force reflow to enable transition
      notification.offsetHeight;
      notification.style.opacity = '1';
      
      setTimeout(() => {
        notification.style.opacity = '0';
        notification.addEventListener('transitionend', () => notification.remove());
      }, 3000);
    }
  };
  
  // Modern search input handling
  const searchInput = document.querySelector('.search-btn');
  if (searchInput) {
    searchInput.addEventListener('focus', () => searchInput.classList.add('search'));
    searchInput.addEventListener('blur', () => searchInput.classList.remove('search'));
  }
  
  // TEACHER SEARCH IMPLEMENTATION
  document.addEventListener('DOMContentLoaded', () => {
    const classList = document.getElementById('class-list');
    const search = document.getElementById('search');
    
    if (!classList || !search) return;
    
    const originalContent = classList.innerHTML;
    let isLoading = false;
    
    // Create loading indicator using template literal
    const loadingTemplate = `
      <div class="w-full flex justify-center items-center py-10">
        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-mainText"></div>
      </div>
    `;
    
    // No results template
    const noResultsTemplate = `
      <div class="text-center p-10">
        <div class="text-mainText text-xl">No classes found</div>
        <p class="text-gray-500 mt-2">Try a different search term</p>
      </div>
    `;
    
    // Error template
    const errorTemplate = `
      <div class="text-center p-10">
        <div class="text-red-500 text-xl">Error loading classes</div>
        <p class="text-gray-500 mt-2">Please try again later</p>
      </div>
    `;
  
    // Modern async fetch classes function
    const fetchClasses = async (query) => {
      try {
        isLoading = true;
        classList.innerHTML = loadingTemplate;
        
        const response = await fetch(`/search-teacher-class?query=${encodeURIComponent(query)}`);
        
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.length === 0) {
          classList.innerHTML = noResultsTemplate;
          return;
        }

        console.log(data);
        
        // Use map to generate HTML and join at the end (more efficient than string concatenation)
        const content = data.map(classItem => `
          <div class="class-item h-[14rem] flex rounded-md shadow-md relative hover:-translate-y-1 transition hover:shadow-lg border border-mainText">
            <div class="flex-1 h-full rounded-l-md">
              <div class="w-full h-full flex flex-col justify-between bg-gray-100 file-vertical shadow-sm relative overflow-visible rounded-l-md p-5">
                <div class="w-full">
                  <div class="w-full mb-1">
                    <h3 class="text-lg text-mainText opacity-80">${classItem.section}</h3>
                  </div>
                  <div class="w-full">
                    <h3 class="text-4xl text-mainText truncated">${classItem.class_name}</h3>
                  </div>
                </div>
                <div class="w-full">
                  <h3 class="truncated text-lg text-mainText opacity-80">${classItem.subject}</h3>
                </div>
              </div>
            </div>
            <div class="w-[15%] h-full flex flex-col justify-between bg-bgcolor rounded-r-md border-l border-mainText py-5">
              <div class="w-full grid items-center">
                <button class="three-dot" aria-label="Options">
                  <i class="bx bx-dots-vertical-rounded text-2xl text-mainText"></i>
                </button>
                                                    <div class="hidden delButton absolute top-[6%] right-[12%] bg-bgcolor py-2 px-2 rounded-md border border-sgline">
                                                        <div class=" flex flex-col text-mainTexts">
                                                            <a href="" class=" w-full px-8 py-2 text-center hover:bg-navHover rounded-md">Move</a>
                                                            <button type="submit" class="delete-btn px-8 py-2 w-full hover:bg-navHover rounded-md delete-confirm-form" data-id="${classItem.id}">Delete</button>
                                                        </div>
                                                    </div>
              </div>
              <div class="w-full flex justify-center -mb-2">
                <a href="${classItem.encoded_url}" class="" aria-label="Open folder">
                  <i class="bx bxs-folder-open text-4xl text-mainText"></i>
                </a>
              </div>
            </div>
          </div>
        `).join('');
  
        classList.innerHTML = content;
        
      } catch (error) {
        console.error('Fetch error:', error);
        classList.innerHTML = errorTemplate;
      } finally {
        isLoading = false;
      }
    };
  
    // Set up event listeners with modern techniques
    const handleSearch = utils.debounce(async (event) => {
      if (isLoading) return;
      
      const query = event.target.value.trim().toLowerCase();
      if (query) {
        await fetchClasses(query);
      } else {
        classList.innerHTML = originalContent;
      }
    });
  
    search.addEventListener('input', handleSearch);
  
    // Use event delegation for dynamic content
    document.addEventListener('click', (event) => {
      // Close menus when clicking outside
      if (!event.target.closest('.three-dot, .delButton')) {
        document.querySelectorAll('.delButton').forEach(menu => menu.classList.add('hidden'));
      }
    });
  
    // Toggle menu with modern event delegation
    classList.addEventListener('click', (event) => {
      const threeDot = event.target.closest('.three-dot');
      if (threeDot) {
        event.stopPropagation();
        
        // Hide all other menus first
        const allMenus = document.querySelectorAll('.delButton');
        allMenus.forEach(menu => {
          if (menu !== threeDot.nextElementSibling) {
            menu.classList.add('hidden');
          }
        });
        
        // Toggle this menu
        const menu = threeDot.nextElementSibling;
        menu?.classList.toggle('hidden');
      }
  
      // Handle delete button
      const deleteBtn = event.target.closest('.delete-btn');
      if (deleteBtn) {
        const classId = deleteBtn.dataset.id;
        const classItem = deleteBtn.closest('.class-item');

        setupDeleteConfirmation(classId);
        
        // if (confirm('Are you sure you want to delete this room?')) {
        //   console.log(classId);
          // Visual feedback
          // classItem.style.opacity = '0.5';
          
          // Modern fetch for delete
          // fetch(`/teacher-room/${classId}`, {
          //   method: 'DELETE',
          //   headers: {
          //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
          //     'Content-Type': 'application/json',
          //     'Accept': 'application/json'
          //   }
          // })
          // .then(response => {
          //   if (!response.ok) throw new Error('Delete failed');
          //   return response.json();
          // })
          // .then(() => {
          //   console.log('In');
          //   // Animate removal with modern Web Animation API
          //   // classItem.animate([
          //   //   { opacity: 0.5, transform: 'translateY(0)' },
          //   //   { opacity: 0, transform: 'translateY(-20px)' }
          //   // ], { 
          //   //   duration: 300, 
          //   //   easing: 'ease-out' 
          //   // }).onfinish = () => {
          //   //   classItem.remove();
          //   //   utils.createNotification('Class deleted successfully');
          //   // };
          // })
          // .catch(error => {
          //   console.error('Delete error:', error);
          //   classItem.style.opacity = '1';
          //   utils.createNotification('Failed to delete class', 'error');
          // });
        // }
      }
    });
  });
  
  // STUDENT SEARCH IMPLEMENTATION
  document.addEventListener('DOMContentLoaded', () => {
    const classList = document.getElementById('class-student');
    const search = document.getElementById('search-student');
    
    if (!classList || !search) return;
    
    // Modern fetch for student classes
    const fetchStudentClasses = async (query) => {
      try {
        // Show loading indicator
        classList.innerHTML = `
          <div class="w-full flex justify-center items-center py-10">
            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-bgcolor"></div>
          </div>
        `;
        
        // Use modern fetch API with proper error handling
        const response = await fetch(`/search-student-class?query=${encodeURIComponent(query)}`);
        
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Use modern array methods
        if (data.length === 0) {
          classList.innerHTML = `
            <div class="text-center p-10">
              <div class="text-bgcolor text-xl">No classes found</div>
              <p class="text-gray-300 mt-2">Try a different search term</p>
            </div>
          `;
          return;
        }
        
        // Map and join for better performance
        classList.innerHTML = data.map(classItem => `
          <div class="class-item bg-mainText rounded-md p-5 shadow-md relative hover:-translate-y-1 transition duration-300">
            <div class="flex flex-col gap-y-1 select-none">
              <h3 class="text-lg text-bgcolor">${classItem.section}</h3>
              <h1 class="text-3xl text-bgcolor truncate max-w-[200px]">${classItem.class_name}</h1>
            </div>
            <h5 class="text-bgcolor pt-8 cp:pt-12">${classItem.teacher_name}</h5>
            <a href="/student-room/${classItem.room_id}" class="absolute right-0 bottom-0 mb-3 mr-5 hover:scale-110 transition">
              <i class="bx bxs-folder-open text-3xl text-bgcolor"></i>
            </a>
          </div>
        `).join('');
        
      } catch (error) {
        console.error('Error fetching student classes:', error);
        classList.innerHTML = `
          <div class="text-center p-10">
            <div class="text-red-300 text-xl">Error loading classes</div>
            <p class="text-gray-300 mt-2">Please try again later</p>
          </div>
        `;
      }
    };
  
    // Set up event handler with modern debounce
    const handleSearch = utils.debounce(async (event) => {
      const query = event.target.value.trim().toLowerCase();
      await fetchStudentClasses(query);
    });
  
    // Modern event listener
    search.addEventListener('input', handleSearch);
    
    // Initial load
    if (classList.children.length === 0) {
      fetchStudentClasses('');
    }
  });




  function setupDeleteConfirmation(classId) {
    const modal = document.getElementById('deleteConfirmationModal');
    const overlay = document.querySelector('.modal-overlay');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const form = document.querySelector('.delete-confirm-form');
    
    console.log(form);
    // Use event delegation to catch all delete form submissions
    // form.addEventListener('click', function(e) {
        if (form) {
            // e.preventDefault();
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    // });

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);

    confirmBtn.addEventListener('click', function() {
        fetch(`/teacher-room/${classId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
          }
        })
        window.location.reload();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

}

function preventDefault(e) {
    e.preventDefault();
}