window.addEventListener('DOMContentLoaded', () => {

});

document.addEventListener('DOMContentLoaded', () => {

    // HERO ANIMATION
    const divs = ['el-1', 'el-2', 'el-3'];
    divs.forEach(id => {
        const el = document.getElementById(id);
        el.classList.remove('opacity-0', 'translate-y-10');
    });

    const elFour = document.getElementById('el-4');
    const elFive = document.getElementById('el-5');
    const elSix = document.getElementById('el-6');

    elFour.classList.remove('opacity-0', '-translate-x-20');
    elFive.classList.remove('opacity-0', 'translate-y-20');
    elSix.classList.remove('opacity-0', 'translate-x-20');



    // ABOUT ANIMATION
    const elements = document.querySelectorAll('.scroll-animate');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.remove('opacity-0', 'translate-y-10');
        } else {
            entry.target.classList.add('opacity-0', 'translate-y-10');
        }
        });
    }, {
        threshold: 0.15
    });

    elements.forEach(el => observer.observe(el));

    // CP MENU BUTTON
    const menuBar = document.querySelector('.open-menu');
    const closeBar = document.getElementById('close-bar');
    const showBar = document.getElementById('show-bar');

    const home = document.querySelector('.home');
    const about = document.querySelector('.about');

    menuBar.addEventListener('click', () => {
        showBar.classList.remove('opacity-0', 'pointer-events-none');
        showBar.classList.remove('w-0');
        showBar.classList.add('w-[80%]');
    });

    function closeSideBar() {
        showBar.classList.remove('w-[80%]');
        showBar.classList.add('w-0');

        showBar.classList.add('opacity-0', 'pointer-events-none');
    }

    closeBar.addEventListener('click', closeSideBar);
    home.addEventListener('click', closeSideBar);
    about.addEventListener('click', closeSideBar);

    document.addEventListener('click', (event) => {
        if (!showBar.contains(event.target) && !menuBar.contains(event.target)) {
            closeSideBar();
        }
    });

});