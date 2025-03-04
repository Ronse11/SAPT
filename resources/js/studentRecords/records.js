document.addEventListener("DOMContentLoaded", function () {

    //TABS JS STARTS HER!!
    const tableTab = document.getElementById("tableTab");
    const chartTab = document.getElementById("chartTab");
    const attendanceTab = document.getElementById("attendanceTab");
    const tableSection = document.getElementById("tableSection");
    const chartSection = document.getElementById("chartSection");
    const attendanceSection = document.getElementById("attendanceSection");

    const recordsBtn = document.querySelector(".records-btn");


    function activateTab(activeTab, inactiveTab, activeSection, inactiveSectionOne, inactiveSectionTwo) {
        // Add full border to active tab
        // activeTab.classList.add("text-bgcolor");
        // activeTab.classList.remove("text-gray-600");

        // // Remove border from inactive tab
        // inactiveTab.classList.add("text-gray-600");
        // inactiveTab.classList.remove("text-bgcolor");

        // Show active section, hide inactive section
        activeSection.classList.remove("hidden");
        inactiveSectionOne.classList.add("hidden");
        inactiveSectionTwo.classList.add("hidden");
    }

    tableTab.addEventListener("click", function () {
        activateTab(tableTab, chartTab, tableSection, chartSection, attendanceSection);
    });

    chartTab.addEventListener("click", function (event) {
        activateTab(chartTab, tableTab, chartSection, tableSection, attendanceSection);
    });

    // attendanceTab.addEventListener("click", function (event) {
    //     activateTab(attendanceTab, tableTab, attendanceSection, chartSection, tableSection);
    //     attendanceTab.classList.remove('text-gray-400');
    //     attendanceTab.classList.add('text-bgcolor', 'bg-mainText');

    //     recordsBtn.classList.remove('text-bgcolor', 'bg-mainText');
    //     recordsBtn.classList.add('text-gray-400');
    // });
    
    recordsBtn.addEventListener("click", function (event) {
        activateTab(recordsBtn, tableTab, chartSection, tableSection, attendanceSection);
        recordsBtn.classList.remove('text-gray-400');
        recordsBtn.classList.add('text-bgcolor', 'bg-mainText');

        attendanceTab.classList.remove('text-bgcolor', 'bg-mainText');
        attendanceTab.classList.add('text-gray-400');
    });
    // TABS JS ENDS HERE!

    // CHARTS JS STARTS HERE
    document.querySelectorAll(".progress-container").forEach(container => {
        function startAnimation() {
            const progressCircle = container.querySelector(".progress-circle");
            const progressText = container.querySelector(".progress-text");
    
            const score = parseInt(container.getAttribute("data-score"), 10) || 0;
            const total = parseInt(container.getAttribute("data-total"), 10) || 1;
            const percentage = (score / total) * 100;
            const targetDegrees = percentage * 3.6;
    
            let currentProgress = 0;
    
            function animateProgress() {
                if (currentProgress < targetDegrees) {
                    currentProgress += (targetDegrees - currentProgress) * 0.1; // f1c40f
                    progressCircle.style.background = `conic-gradient(#333333 ${currentProgress}deg, rgba(214, 214, 215, 0.5) ${currentProgress}deg)`;
            
                    let animatedScore = Math.round((currentProgress / 360) * total);
                    progressText.innerHTML = `<span class="score">${animatedScore}</span><span class="divider">/</span><span class="total">${total}</span>`;
            
                    requestAnimationFrame(animateProgress);
                } else {
                    progressText.innerHTML = `<span class="score">${score}</span><span class="divider">/</span><span class="total">${total}</span>`;
                }
            }
            
    
            // Observer to detect when the tab is revisited
            const observer = new IntersectionObserver(entries => {
                if (entries[0].isIntersecting) {
                    // Reset progress and restart animation
                    currentProgress = 0;
                    animateProgress();
                }
            }, { threshold: 0.3 });
    
            observer.observe(container);
        }
    
        startAnimation();
    });
    
    
    
    // CHARTS JS ENDS HERE!


});

// TABS FOR CATEGORY CHART VIEW STARTS HERE!
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("#quizTabs button").forEach(button => {
        button.addEventListener("click", function () {
            let tab = this.dataset.tab;

            // Hide all content and remove active tab styles
            document.querySelectorAll("#quizContent > div").forEach(div => div.classList.add("hidden"));
            document.querySelectorAll("#quizTabs button").forEach(btn => btn.classList.remove( "bg-mainText", "text-bgcolor"));

            // Show selected content and highlight active tab
            document.querySelector(`[data-content="${tab}"]`).classList.remove("hidden");
            this.classList.add("bg-mainText", "text-bgcolor");
        });
    });
});
// TABS FOR CATEGORY CHART VIEW ENDS HERE!


// DROPDOWN FOR SIDEBAR STARTS HERE!
// document.addEventListener("DOMContentLoaded", function() {
//     const homeButton = document.getElementById("homeButton");
//     const homeSubmenu = document.getElementById("homeSubmenu");
//     const arrowIcon = document.getElementById("arrowIcon");

//     homeButton.addEventListener("click", function() {
//         const isOpen = !homeSubmenu.classList.contains("max-h-0");

//         if (isOpen) {
//             homeSubmenu.classList.add("max-h-0");
//             homeSubmenu.classList.remove("max-h-40", "py-2");
//             arrowIcon.classList.remove("rotate-180");
//         } else {
//             homeSubmenu.classList.remove("max-h-0");
//             homeSubmenu.classList.add("max-h-40", "py-2");
//             arrowIcon.classList.add("rotate-180");
//         }
//     });
// });
// DROPDOWN FOR SIDEBAR ENDS HERE!
