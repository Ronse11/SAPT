// Clicking Eye
const eye = document.querySelector('.bxs-show');
const pass = document.querySelector('.pass');

      eye.addEventListener('click', () => {
        pass.type = pass.type === 'password' ? 'text' : 'password';
      });