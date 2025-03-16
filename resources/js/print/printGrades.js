import printJS from "print-js";



// document.addEventListener('DOMContentLoaded', ()=> {
    document.getElementById("print-grade").addEventListener("click", printDiv);
// }); 

function printDiv() {
    printJS({
        printable: 'printable-content',
        type: 'html',
        css: 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
        scanStyles: false,
        style: `
            @media print {
    
                /* Set text sizes */
                h1, h2, h3 {
                    font-size: 18px !important;
                }
                p, td, th {
                    font-size: 14px !important;
                }
                td {
                    border: 1px solid #cccccc;
                }
                /* Adjust Tailwind text classes */
                .text-sm { font-size: 12px !important; }
                .text-base { font-size: 14px !important; }
                .text-lg { font-size: 16px !important; }
                .text-xl { font-size: 18px !important; }
                .text-2xl { font-size: 22px !important; }
                .text-3xl { font-size: 26px !important; }
                .text-4xl { font-size: 30px !important; }
                .text-5xl { font-size: 36px !important; }
            }
        `
    });
}


