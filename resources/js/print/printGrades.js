// import printJS from "print-js";

// function printGradingSheet() {
//     const content = document.getElementById("printable-content");
//     if (!content) {
//         console.error("Printable content not found.");
//         return;
//     }

//     // Force width to 100% for printing
//     content.style.width = "100%";
//     content.style.maxWidth = "100%";

//     // Print the content
//     printJS({
//         printable: "printable-content",
//         type: "html",
//         targetStyles: ["*", ":hover"],
//         style: `
//             @media print {
//                 #printable-content {
//                     width: 100%;
//                     max-width: 100%;
//                     overflow: hidden;
//                     page-break-inside: avoid;
//                 }
//             }
//         `
//     });
// }


function printDiv() {
    const content = document.getElementById("printable-content").innerHTML;
    const originalContent = document.body.innerHTML; // Store the original content
    // const logoContainer = document.querySelector(".cpsuLogo"); // Target logo container

    // // Temporarily adjust Tailwind classes for printing
    // if (logoContainer) {
    //     logoContainer.classList.remove("left-28");
    //     logoContainer.classList.add("left-40"); // Adjust as needed
    // }

    document.body.innerHTML = content; // Replace body with only the printable content
    window.print(); // Trigger print

    // Restore original content and Tailwind classes after printing
    document.body.innerHTML = originalContent;
    location.reload();
}

// document.addEventListener('DOMContentLoaded', ()=> {
    document.getElementById("print-grade").addEventListener("click", printDiv);
// }); 