document.addEventListener("keydown", function (event) {
    // Check if Ctrl+P is pressed
    if (event.ctrlKey && event.key.toLowerCase() === "p") {
      event.preventDefault(); // Prevent the default browser print dialog
      triggerPrint(); // Call your print function
    }
  });
  
  document.getElementById("print-button").addEventListener("click", function () {
    triggerPrint();
  });
  
  function triggerPrint() {
    const getCurrentTable = document.querySelector('.main-table');
    const rows = Array.from(getCurrentTable.querySelectorAll("tbody tr"));
  
    const tailwindToCSS = {
      "h-auto": "height: auto;",
      "text-14px": "font-size: 14px;",
      "cursor-cell": "cursor: cell;",
      "border-t": "border-top: 1px solid black;",
      "border-b": "border-bottom: 1px solid black;",
      "border-l": "border-left: 1px solid black;",
      "border-r": "border-right: 1px solid black;",
      "text-start": "text-align: left;",
      "text-center": "text-align: center;",
      "font-bold": "font-weight: bold;",
      "uppercase": "text-transform: uppercase;",
      "bg-gray-50": "background-color: #f9fafb;",
      "bg-gray-100": "background-color: #f3f4f6;",
      "bg-gray-200": "background-color: #e5e7eb;",
      "bg-gray-300": "background-color: #d1d5db;",
      "bg-gray-400": "background-color: #9ca3af;",
      "bg-gray-500": "background-color: #6b7280;",
      "bg-gray-600": "background-color: #4b5563;",
      "bg-gray-700": "background-color: #374151;",
      "bg-gray-800": "background-color: #1f2937;",
      "bg-gray-900": "background-color: #111827;",
      "bg-red-100": "background-color: #fee2e2;",
      "bg-red-200": "background-color: #fecaca;",
      "bg-red-300": "background-color: #fca5a5;",
      "bg-red-400": "background-color: #f87171;",
      "bg-red-500": "background-color: #ef4444;",
      "bg-blue-100": "background-color: #dbeafe;",
      "bg-blue-200": "background-color: #bfdbfe;",
      "bg-blue-300": "background-color: #93c5fd;",
      "bg-blue-400": "background-color: #60a5fa;",
      "bg-blue-500": "background-color: #3b82f6;",
      "bg-yellow-50": "background-color: #fefce8;",
      "bg-yellow-200": "background-color: #fde68a;",
      "bg-yellow-300": "background-color: #fcd34d;",
      "bg-yellow-400": "background-color: #fbbf24;",
      "bg-yellow-500": "background-color: #f59e0b;",
      "bg-green-100": "background-color: #d1fae5;",
      "bg-green-200": "background-color: #a7f3d0;",
      "bg-green-300": "background-color: #6ee7b7;",
      "bg-green-400": "background-color: #34d399;",
      "bg-green-500": "background-color: #22c55e;",
      "bg-purple-100": "background-color: #ede9fe;",
      "bg-purple-200": "background-color: #ddd6fe;",
      "bg-purple-300": "background-color: #c4b5fd;",
      "bg-purple-400": "background-color: #a78bfa;",
      "bg-purple-500": "background-color: #8b5cf6;",
      "bg-orange-100": "background-color: #ffedd5;",
      "bg-orange-200": "background-color: #fed7aa;",
      "bg-orange-300": "background-color: #fdba74;",
      "bg-orange-400": "background-color: #fb923c;",
      "bg-orange-500": "background-color: #f97316;",
      "text-white": "color: #ffffff;",
      "text-black": "color: #000000;",
      "text-red-500": "color: #ef4444;",
      "text-blue-500": "color: #3b82f6;",
      "text-green-500": "color: #22c55e;",
    };
  
    let maxRowIndex = -1;
    let maxColIndex = 0;
    const mergeMap = new Set();
  
    rows.forEach((row, rowIndex) => {
      const cells = Array.from(row.querySelectorAll("td"));
      let colIndex = 0;
  
      cells.forEach((cell) => {
        const colspan = parseInt(cell.getAttribute("colspan") || 1);
        const rowspan = parseInt(cell.getAttribute("rowspan") || 1);
  
        if (window.getComputedStyle(cell).display === "none") {
          return;
        }
  
        if (colspan > 1 || rowspan > 1 || cell.innerText.trim()) {
          maxRowIndex = Math.max(maxRowIndex, rowIndex + rowspan - 1);
          maxColIndex = Math.max(maxColIndex, colIndex + colspan - 1);
        }
  
        for (let r = rowIndex; r < rowIndex + rowspan; r++) {
          for (let c = colIndex; c < colIndex + colspan; c++) {
            if (r !== rowIndex || c !== colIndex) {
              mergeMap.add(`${r},${c}`);
            }
          }
        }
  
        colIndex += colspan;
      });
    });
  
    let printContent = `<table style="border-collapse: collapse; width: 100%;">`;
  
    rows.slice(0, maxRowIndex + 1).forEach((row, rowIndex) => {
      let rowContent = "";
      let colIndex = 0;
  
      const cells = Array.from(row.querySelectorAll("td"));
  
      cells.forEach((cell) => {
        if (colIndex > maxColIndex) return;
  
        const style = window.getComputedStyle(cell);
        if (style.display === "none" || mergeMap.has(`${rowIndex},${colIndex}`)) {
          colIndex += parseInt(cell.getAttribute("colspan") || 1);
          return;
        }
  
        const content = cell.innerText.trim();
        const rowspan = cell.getAttribute("rowspan") || 1;
        const colspan = cell.getAttribute("colspan") || 1;
        const cellClasses = cell.getAttribute("class") || "";
  
        const inlineStyles = cellClasses
          .split(" ")
          .map((cls) => tailwindToCSS[cls] || "")
          .join(" ");
  
        // Get computed styles for color and background
        const backgroundColor = "black";
        console.log(backgroundColor);
        const fontColor = style.color || "inherit";
  
        // Add cell with extracted styles
        rowContent += `
          <td 
            style="
              
              border: 1px solid black; 
              padding: 4px; 
            " 
            rowspan="${rowspan}" 
            colspan="${colspan}">
            ${content || "&nbsp;"}
          </td>`;
        colIndex += parseInt(colspan);
      });
  
      while (colIndex <= maxColIndex) {
        rowContent += `<td style="border: 1px solid black; color: white; padding: 4px;">&nbsp;</td>`;
        colIndex++;
      }
  
      printContent += `<tr>${rowContent}</tr>`;
    });
  
    printContent += "</table>";
  
    const printContainer = document.createElement("div");
    printContainer.id = "print-container";
    printContainer.style.display = "none";
    printContainer.innerHTML = `
      <style>
        @media print {
          @page { margin: 1cm; }
          body > *:not(#print-container) { display: none !important; }
          #print-container { display: block !important; }
        }
      </style>
      ${printContent}
    `;
    document.body.appendChild(printContainer);
  
    window.print();
  
    document.body.removeChild(printContainer);
  }
  
  
  
  
  
  
  
  
  
  
  
  