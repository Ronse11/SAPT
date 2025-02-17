document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.querySelector('.menu-bar');
    const menuButtonNav = document.querySelector('.menu-bar-nav');
    const sidebar = document.querySelector('.sidebar');

    menuButton.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-[0rem]');
        sidebar.classList.remove('-translate-x-[20rem]');
    });

    menuButtonNav.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-[0rem]');
        sidebar.classList.add('-translate-x-[20rem]');
    });

    // Clicking Folder Navbar and Class Navbar
    const classButton = document.querySelector('.class-button');
    const folderButton = document.querySelector('.folder-button');
    const caretRight = document.querySelector('.class');
    const caretRightFolder = document.querySelector('.folder');
    const classNav = document.querySelector('.class-nav');
    const folderNav = document.querySelector('.folder-nav');
  
    classButton.addEventListener('click', function() {
      caretRight.classList.toggle('bx-chevron-right');
      caretRight.classList.toggle('bx-chevron-down');
      
      if(classNav.style.height === '100%') {
        classNav.style.height = '0%';
      } else {
        classNav.style.height = '100%';
        folderNav.style.height = '0%';
        caretRightFolder.classList.remove('bx-chevron-down');
        caretRightFolder.classList.add('bx-chevron-right');
      }
    });
  
    folderButton.addEventListener('click', function() {
      caretRightFolder.classList.toggle('bx-chevron-right');
      caretRightFolder.classList.toggle('bx-chevron-down');
  
      if(folderNav.style.height === '100%') {
        folderNav.style.height = '0%';
      } else {
        folderNav.style.height = '100%';
        classNav.style.height = '0%';
        caretRight.classList.remove('bx-chevron-down');
        caretRight.classList.add('bx-chevron-right');
      }
  });
});