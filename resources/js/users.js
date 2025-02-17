// Sidebar Function
// const toggle = document.getElementById('menu-bar'),
//       menuClose = document.getElementById('menu-bar-close'),
//       sidebar = document.querySelector('.sidebar-user');
      
//       toggle.addEventListener('click', () => {
//         sidebar.classList.add('close-sidebar');
//       });
//       menuClose.addEventListener('click', () => {
//         sidebar.classList.remove('close-sidebar');
//       });

// Clicking Avatar
document.addEventListener('click', (event) => {
  const clickedBtn = event.target.closest('.btn-user');
  const log = document.querySelector('.log-user');

  if (clickedBtn) {
      if (clickedBtn.classList.contains('btn-plus')) {
        //   log.classList.add('hidden');
      } else if (clickedBtn.classList.contains('btn-user')) {
          log.classList.toggle('hidden');
      }
  } else {
      log.classList.add('hidden');
  }
});