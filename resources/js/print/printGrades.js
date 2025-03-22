import printJS from "print-js";



// // document.addEventListener('DOMContentLoaded', ()=> {
//     document.getElementById("print-grade").addEventListener("click", printDiv);
// // }); 

// function printDiv() {
//     printJS({
//         printable: 'printable-content',
//         type: 'html',
//         css: 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
//         scanStyles: false,
//         style: `
//             @media print {
    
//                 /* Set text sizes */
//                 h1, h2, h3 {
//                     font-size: 18px !important;
//                 }
//                 p, td, th {
//                     font-size: 14px !important;
//                 }
//                 td {
//                     border: 1px solid #cccccc;
//                 }
//                 /* Adjust Tailwind text classes */
//                 .text-sm { font-size: 12px !important; }
//                 .text-base { font-size: 14px !important; }
//                 .text-lg { font-size: 16px !important; }
//                 .text-xl { font-size: 18px !important; }
//                 .text-2xl { font-size: 22px !important; }
//                 .text-3xl { font-size: 26px !important; }
//                 .text-4xl { font-size: 30px !important; }
//                 .text-5xl { font-size: 36px !important; }
//             }
//         `
//     });
// }




// document.addEventListener('DOMContentLoaded', ()=> {
    document.getElementById("print-grade").addEventListener("click", printMultipleDivs);
// }); 


function printMultipleDivs() {
    // Get the content of both divs
    const content1 = document.getElementById("printable-content-1").innerHTML;
    const content2 = document.getElementById("printable-content-2").innerHTML;

    // Create a temporary div to hold both contents
    const printableDiv = document.createElement("div");
    printableDiv.innerHTML = content1 + '<div style="page-break-before: always;"></div>' + content2;
    
    // Append to body
    document.body.appendChild(printableDiv);

    // Print using PrintJS
    printJS({
        printable: printableDiv,
        type: 'html',
        css: 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
        scanStyles: false,
        style: `
            @media print {
                table {
                    font-size: 14px;
                }
                .leftBorder {
                    border-left: solid 1px #cccccc;
                }
                .firstRow {
                    border-right: solid 1px #cccccc;
                }
                .headTitle {
                    margin-right: 75px;
                }
            }
        `
    });

    // Remove the temporary div after printing
    setTimeout(() => document.body.removeChild(printableDiv), 1000);
}












// table {
//     width: 100%;
//     border-collapse: collapse;
//     page-break-inside: auto;
// }

// thead {
//     display: table-header-group;
// }

// tbody tr {
//     page-break-inside: avoid;
// }

// /* Set text sizes */
// h1, h2, h3 {
//     font-size: 18px !important;
// }
// p, td, th {
//     font-size: 14px !important;
// }
// td {
//     border: 1px solid #cccccc;
// }
// /* Adjust Tailwind text classes */
// .text-sm { font-size: 12px !important; }
// .text-base { font-size: 14px !important; }
// .text-lg { font-size: 16px !important; }
// .text-xl { font-size: 18px !important; }
// .text-2xl { font-size: 22px !important; }
// .text-3xl { font-size: 26px !important; }
// .text-4xl { font-size: 30px !important; }
// .text-5xl { font-size: 36px !important; }