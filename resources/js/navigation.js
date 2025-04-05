// Sidebar Function
const toggle = document.getElementById('menu-bar');
const toggleAssist = document.getElementById('menu-assist');
const show = document.querySelector('.show');
const sidebar = document.querySelector('.sidebar');
const hero = document.getElementById('hero');
const box = document.querySelector('.content-box');
      
toggle.addEventListener('click', () => {
  sidebar.classList.toggle('close');
  hero.classList.toggle('expand');
  // hero.classList.remove('w-[85%]');
  box.classList.toggle('three');
});
toggleAssist.addEventListener('click', () => {
  sidebar.classList.toggle('close');
  hero.classList.toggle('expand');
  // hero.classList.remove('w-[85%]');
  box.classList.toggle('three');
});

document.addEventListener('DOMContentLoaded', function() {
  const btnPlus = document.querySelector('.btn-pluss');
  const createPop = document.querySelector('.create-pop');

  btnPlus.addEventListener('click', () => {
    createPop.classList.toggle('hidden');
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const div = document.querySelector('.hero');
  const wSearch = document.querySelector('.w-search');
  const headerHolder = document.querySelector('.header-holder');
  const contentBox = document.querySelector('.content-box');
  const adjustPleft = document.querySelector('.adjust-pleft');


  toggle.addEventListener('click', () => {

    headerHolder.classList.remove('pl-16');
    headerHolder.classList.add('pl-8');
    contentBox.classList.remove('pl-16');
    contentBox.classList.add('pl-8');
    adjustPleft.classList.remove('pl-16');
    adjustPleft.classList.add('pl-8');
    div.style.width = '100%';
    setTimeout(() => {
        const computedStyle = window.getComputedStyle(div);
        const width = parseFloat(computedStyle.width);
        const parentWidth = parseFloat(window.getComputedStyle(div.parentElement).width);
        const widthPercentage = (width / parentWidth) * 100;

        if (widthPercentage >= 90) {
            show.classList.remove('hidden');
            sidebar.classList.add('hidden');
        }
    }, 163); 

  });

  toggleAssist.addEventListener('click', () => {
    if (div.style.width === '100%') {
      div.style.width = '83%'; 
      show.classList.add('hidden');
      headerHolder.classList.remove('pl-8');
      headerHolder.classList.add('pl-16');
      contentBox.classList.add('pl-16');
      contentBox.classList.remove('pl-8');
      adjustPleft.classList.add('pl-16');
      adjustPleft.classList.remove('pl-8');
      sidebar.classList.remove('hidden');
    } else {
      div.style.width = '100%';
    }
  });

  div.addEventListener('transitionend', function(event) {
    if (event.propertyName === 'width') {
      const computedStyle = window.getComputedStyle(div);
      const width = parseFloat(computedStyle.width);
      const parentWidth = parseFloat(window.getComputedStyle(div.parentElement).width);
      const widthPercentage = (width / parentWidth) * 100;

      const wid = widthPercentage - 82;
      wSearch.style.width = `19rem`;
      wSearch.classList.add('mr-16');

      if (widthPercentage == '83') {
        show.classList.add('hidden');
        wSearch.style.width = '';
        wSearch.classList.remove('mr-16');
      }
    }
  });


  
});

function adjustHeroWidth() {
  const hero = document.getElementById('hero');
  if (window.innerWidth <= 375) {
    hero.style.width = '100%';
  } else {
    hero.style.width = ''; // Reset to default if needed
  }
}

// Run on page load
document.addEventListener('DOMContentLoaded', adjustHeroWidth);

// Run on window resize
window.addEventListener('resize', adjustHeroWidth);

  // const myDiv = document.getElementById('myDiv');
//   div.style.width = '100%';
  
//   const interval = setInterval(() => {
//     const computedStyle = window.getComputedStyle(div);
//     const width = parseFloat(computedStyle.width);
//     const parentWidth = parseFloat(window.getComputedStyle(div.parentElement).width);
//     const widthPercentage = (width / parentWidth) * 100;

//     // Add class when width is 90% or more
//     if (widthPercentage >= 90) {
//         myDiv.classList.add('full-width');
//     }

//     // Stop checking after reaching 100% width
//     if (widthPercentage >= 100) {
//         clearInterval(interval);
//     }
// }, 40);

  // if(div.style.width === '100%') {
  //   div.style.width = '';
  //   div.classList.remove('full-width');
  //   show.classList.add('hidden');
  // } else {
  //   div.style.width = '100%';
  // }

 // Initially set wSearch width to match



  // const div = document.querySelector('.hero');

  // setTimeout(() => {
  //     div.style.width = '100%';
  // }, 100);

  // div.addEventListener('transitionend', function(event) {
  //     if (event.propertyName === 'width' && this.style.width === '100%') {
  //         this.classList.add('full-width');
  //     }
  // });


// document.getElementById('menu-bar').addEventListener('click', function() {
//   const gridItem = document.getElementById('hero');
//   const gridItem2 = document.getElementById('sidebar');
//   if (gridItem.classList.contains('expand')) {
//       gridItem.classList.remove('w-[85%]');
//       gridItem.classList.add('w-[100%]');
//   } else {
//       gridItem.classList.remove('w-full-screen');
//       gridItem.classList.add('w-half-screen');
//   }
// });



// Choosing between to Track and Create
// const trackBtn = document.querySelector('.trackBtn');
// const createBtn = document.querySelector('.createBtn');

// if()

// Adjust Padding when Scrollbar is Out
// function adjust() {
//   const container = document.querySelector('.overflow-scrolls');
//   const adjustable = document.querySelector('.content-box');

//   const hasVerticalSroll = container.scrollHeight > container.clientHeight;

//   if (hasVerticalSroll) {
//     adjustable.classList.add('pr-8');
//   } else {
//     adjustable.classList.remove('pr-8');
//   }
// }

// adjust();

// window.addEventListener('resize', adjust());

// document.querySelector('.overflow-scrolls').addEventListener('scroll', adjust);


document.addEventListener('DOMContentLoaded', function() {
  const classButton = document.querySelector('.class-button');
  const folderButton = document.querySelector('.folder-button');
  const showClass = document.querySelector('.show-class');
  const caretRight = document.querySelector('.class');
  const caretRightFolder = document.querySelector('.folder');
  const classNav = document.querySelector('.class-nav');
  const folderNav = document.querySelector('.folder-nav');
  const headerHolder = document.querySelector('.header-holder');

  classButton.addEventListener('click', function() {
    // showClass.classList.toggle('hidden');
    caretRight.classList.toggle('bx-chevron-right');
    caretRight.classList.toggle('bx-chevron-down');
    
    if(classNav.style.height === '100%') {
      classNav.style.height = '0%';
      // classNav.classList.remove('mt-4');
    } else {
      classNav.style.height = '100%';
      folderNav.style.height = '0%';
      caretRightFolder.classList.remove('bx-chevron-down');
      caretRightFolder.classList.add('bx-chevron-right');
      // classNav.classList.add('mt-4');
    }
  });

  folderButton.addEventListener('click', function() {
    // showClass.classList.toggle('hidden');
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