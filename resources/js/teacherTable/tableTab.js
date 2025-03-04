document.addEventListener('DOMContentLoaded', () => {
    const ratingTab = document.getElementById('ratingTab');
    const recordTab = document.getElementById('recordTab');
    const ratingTabDiv = document.querySelector('.ratingTabDiv');
    const recordTabDiv = document.querySelector('.recordTabDiv');
    const ratingSection = document.getElementById('ratingSection');
    const recordSection = document.getElementById('main-table');

    const printButton = document.getElementById('printButton');
    const printGrade = document.getElementById('printGrade');
    

    function activeTable (activeTab, inactiveTab, activeSection, inactiveSection, activePrintBtn, inactivePrintBtn) {

        activeTab.classList.add("bg-bgcolor", "border-x", "border-b-4", "border-mainText");
        inactiveTab.classList.remove("bg-bgcolor", "border-x", "border-b-4", "border-mainText");
        
        activeSection.classList.remove('hidden');
        inactiveSection.classList.add('hidden');

        activePrintBtn.classList.remove('hidden');
        inactivePrintBtn.classList.add('hidden');

    }

    ratingTab.addEventListener('click', () => {
        activeTable(ratingTabDiv, recordTabDiv, ratingSection, recordSection, printGrade, printButton);
    });

    recordTab.addEventListener('click', () => {
        activeTable(recordTabDiv, ratingTabDiv, recordSection, ratingSection, printButton, printGrade);
    });
}); 