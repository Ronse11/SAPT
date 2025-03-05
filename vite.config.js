import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // server: {
    //   hmr: false,  // Disable Hot Module Replacement
    // },
    resolve: {
        alias: {
          '@js': '/resources/js/teacherTable',
        },
      },
    plugins: [
        laravel({
            input: [
              'resources/css/app.css', 
              'resources/css/breakPoints.css', 
              'resources/css/global.css', 
              'resources/css/table.css', 
              'resources/js/app.js', 
              'resources/js/navigation.js', 
              'resources/js/users.js',
            
              'resources/css/app.css',
              'resources/css/breakPoints.css',
              'resources/js/app.js',
              'resources/js/navigation.js',
              'resources/js/users.js',
              // Include all other JS files
              'resources/js/attitude.js',
              'resources/js/auth.js',
              'resources/js/bootstrap.js',
              'resources/js/knowledge.js',
              'resources/js/merging.js',
              'resources/js/pasteNames.js',
              'resources/js/realTime.js',
              'resources/js/sample.js',
              'resources/js/search.js',
              'resources/js/settingUpdate.js',
              'resources/js/skills.js',
              // Include subfolders
              'resources/js/home/delButton.js',
              'resources/js/imports/export.js',
              'resources/js/imports/import.js',
              'resources/js/imports/uploadPdf.js',
              'resources/js/nav/nav.js',
              'resources/js/print/extraPrint.js',
              'resources/js/print/print.js',
              'resources/js/print/printGrades.js',
              'resources/js/studentRecords/records.js',
              'resources/js/table/buttons.js',
              'resources/js/table/indexDB.js',
              'resources/js/table/notification.js',
              'resources/js/table/temporary.js',
              'resources/js/teacherTable/addStudentsNames.js',
              'resources/js/teacherTable/adjustFontSize.js',
              'resources/js/teacherTable/applyBorder.js',
              'resources/js/teacherTable/applyColors.js',
              'resources/js/teacherTable/applyFontStyle.js',
              'resources/js/teacherTable/calculation.js',
              'resources/js/teacherTable/colorLogic.js',
              'resources/js/teacherTable/doneCheck.js',
              'resources/js/teacherTable/dragSum.js',
              'resources/js/teacherTable/handleCellBlur.js',
              'resources/js/teacherTable/logic.js',
              'resources/js/teacherTable/merging.js',
              'resources/js/teacherTable/sample.js',
              'resources/js/teacherTable/tableTab.js',  

              'resources/images/saptlogo.svg',
            
            ],
              
            refresh: true,
        }),
    ],
});

