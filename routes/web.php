<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoldersController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomStudentController;
use App\Http\Controllers\TableButtonsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UsefulController;
use App\Models\Folders;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Overall Use
Route::get('auth/chooseGoogle', [GoogleAuthController::class, 'redirectChoose'])->name('google-choose');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);


// Auth Pages
Route::middleware(['auth', 'no-cache'])->group(function() {

    Route::get('/printable', [PrintController::class, 'show']);

    // HERE IS THE PASTEEEEE CODEEEE
    Route::get('/teacher-room/records/paste-form/{id}', [UsefulController::class, 'getRoomID'])->name('paste-name');
    // Route::post('/teacher-room/records/class-record/{id}', [UsefulController::class, 'showSample'])->name('name');
    Route::post('/teacher-room/records/class-record/{id}', [UsefulController::class, 'pasteStudentNames'])->name('paste.student.names');

    
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    // Route::view('/home', 'navigations.home')->name('home');
    // Route::view('/settings', 'navigations.setting')->name('setting');
    // Route::view('/home/teacher-room/settings', 'teacher.setting')->name('teacher-setting');
    // Route::view('/home/student-room/settings', 'student.setting')->name('student-setting');
    // Route::view('/home', 'navigations.home')->name('home');

    // Display of Teacher Rooms
    // Route::view('/teacher', 'users.teacher')->name('teacher');
    // Route::get('/teacher-room', [RoomController::class, 'show'])->name('teacher-room');

    // Display Rooms Of Teacher And Student
    // Route::get('/home/teacher-room', [RoomController::class, 'show'])->name('teacher-home');
    // Route::get('/home/student-room', [RoomStudentController::class, 'show'])->name('student-home');

    // Route::get('/teacher-room/knowledge/{id}', [RoomController::class, 'showRoom'])->name('teacher.room');

    // // Display of Records
    // Route::get('/teacher-room/records/{id}', [RoomController::class, 'showRecord'])->name('teacher.room');
    // Route::get('/teacher-room/records/ratings/{id}', [RoomController::class, 'showRatings'])->name('ratings.room');
    // Route::get('/teacher-room/records/skills/{id}', [RoomController::class, 'showSkills'])->name('skills.room');
    // Route::get('/teacher-room/records/attitude/{id}', [RoomController::class, 'showAttitude'])->name('attitude.room');

    // Display of Student Rooms
    // Route::post('/student-room/track', [RoomStudentController::class, 'checkCode']);
    // // Route::get('/student-room', [RoomStudentController::class, 'show'])->name('student-room');
    // Route::get('/home/student-room/{id}', [RoomStudentController::class, 'showRoom'])->name('student.room');
    // // Route::get('/student-room', [RoomStudentController::class, 'showUp']);

    // View For Creating and Tracking Data
    Route::view('/home/create', 'choices.create')->name('create');
    Route::view('/home/track', 'choices.track')->name('track');
    Route::view('/home/track/phone-number', 'choices.phoneNumber')->name('phoneNumber');

    // Cancel Button
    Route::get('/cancel', [UsefulController::class, 'choices'])->name('choices');


    // Creating Room
    Route::get('/create', [RoomController::class, 'showCodeForm'])->name('create');
    Route::get('/create/folder-room/{id}', [RoomController::class, 'showFolderForm'])->name('createRoomFolder');
    Route::post('/choices/create', [RoomController::class, 'storeRoom'])->name('room');
    Route::post('/choices/create/folder-room/{id}', [RoomController::class, 'storeRoomFolder'])->name('roomFolder');
    Route::post('/track', [RoomStudentController::class, 'checkCode'])->name('checkCode');
    Route::post('track/phone-number', [RoomStudentController::class, 'checkPhoneNumber'])->name('checkPhoneNumber');
    Route::resource('folders', FoldersController::class);

    // Tracking Room
    Route::get('/track/folder-room/{id}', [RoomStudentController::class, 'showFolderFormTrack'])->name('trackRoomFolder');
    Route::post('/choices/track/folder-room/{id}', [RoomStudentController::class, 'storeTrackFolder'])->name('trackFolder');

    Route::get('home/teacher-room/folder/{id}', [FoldersController::class, 'teacherFolderShow'])->name('teacher-folder');
    Route::get('home/student-room/folder/{id}', [FoldersController::class, 'studentFolderShow'])->name('student-folder');

    Route::view('/home/calendar', 'navigations.calendar')->name('calendar');


    // Table Knowledge
    // Route::get('/teacher-room', [TableController::class, 'index']);
    Route::post('/update/{tableData}', [TableController::class, 'update']);
    Route::delete('/delete/{id}', [TableController::class, 'delete']);
    Route::post('/create', [TableController::class, 'create']);
    Route::get('/fetch-table-data', [TableController::class, 'fetchTableData']);
    // Route::get('/create/{id}', [TableController::class, 'create']);

    // Table Skills
    Route::post('/update-skills/{tableData}', [TableController::class, 'updateSkills']);
    Route::delete('/delete-skills/{id}', [TableController::class, 'deleteSkills']);
    Route::delete('/delete-formula/{formulaId}', [TableController::class, 'deleteFormula']);
    Route::post('/create-skills', [TableController::class, 'createSkills']);
    Route::post('/create-calculations', [TableController::class, 'createCalculation']);
    Route::post('/create-formulas', [TableController::class, 'createFormula']);
    Route::post('/update-student/{id}', [TableController::class, 'deleteSkills']);


    Route::post('/save-all-data', [TableController::class, 'saveAllData']);


    // Update Student Names
    Route::post('/update-student-cell/{tableData}', [TableController::class, 'updateSkills']);
    
    // Table Attendance
    // Route::post('/create-attendance', [TableController::class, 'createAttendance']);
    // Route::post('/update-skills/{tableData}', [TableController::class, 'updateAttendance']);
    // Route::delete('/delete-skills/{id}', [TableController::class, 'deleteAttendance']);

    // Table Attitude
    // Route::post('/update-attitude/{tableData}', [TableController::class, 'updateAttitude']);
    // Route::delete('/delete-attitude/{id}', [TableController::class, 'deleteAttitude']);
    // Route::post('/create-attitude', [TableController::class, 'createAttitude']);

    // Route::get('/teacher-room/{room_id}', [TableController::class, 'create']);

    // SEARCH FUNCTIONALITY HERE!
    Route::get('/search-student-class', [UsefulController::class, 'searchStudent']);
    Route::get('/search-teacher-class', [UsefulController::class, 'searchTeacher']);

    // // Displaying of SETTINGS of every ID's
    // Route::get('/teacher-room/records/settings/{id}', [RoomController::class, 'settings'])->name('room-setting');

    // Updating Class Information With Teacher Side
    Route::post('/update-class/{id}', [RoomController::class, 'updateClass']);

    // // Sample Route
    // Route::get('/teacher-room/records/sample/{id}', [UsefulController::class, 'showSample'])->name('sample.room');


    Route::post('/save-merged-cell', [UsefulController::class, 'saveMergedCell']);
    Route::post('/update-merged-cell/{id}', [UsefulController::class, 'updateMergedCell']);
    Route::post('/update-content-cell/{id}', [UsefulController::class, 'updateContentCell']);
    Route::post('/bulk-update-content-cell', [UsefulController::class, 'bulkUpdateCells']);
    Route::post('/save-calculated-cell', [UsefulController::class, 'saveCalculatedContentCell']);
    Route::post('/bulk-update-cells', [UsefulController::class, 'saveCalculatedContentCell']);
    // Update Student Names
    Route::post('/update-studentNames/{id}', [UsefulController::class, 'updateStudentCell']);
    // Route::post('/update-merged-cell/{id}', [UsefulController::class, 'updateAttendance']);
    Route::post('/unmerge-cell/{id}', [UsefulController::class, 'unMergedCell']);
    Route::get('/load-table-state', [UsefulController::class, 'loadTableState']);
    Route::get('/update-table-content/{id}', [RoomController::class, 'showSkills']);

    Route::get('/get-cell-states', [UsefulController::class, 'getCellStates']);


    Route::get('/load-page', [UsefulController::class, 'loadPage']);

    Route::get('/fetch-students/{id}', [UsefulController::class, 'fetchStudents']);

    // TABLE BUTTONS
    Route::post('/save-borders', [TableButtonsController::class, 'saveBorders']);
    Route::post('/save-borders-position-cells', [TableButtonsController::class, 'saveBordersTopRightBottomLeft']);
    Route::get('/get-borders/{id}/{tableID}', [TableButtonsController::class, 'getBorders']);
    // Route::post('/delete-borders', [TableButtonsController::class, 'deleteBorders']);
    Route::post('/delete-borders-all-cells', [TableButtonsController::class, 'deleteBordersAllCells']);
    Route::post('/push-names', [TableButtonsController::class, 'pushNames']);
    Route::post('/push-student-id', [TableButtonsController::class, 'pushStudentId']);
    Route::delete('/delete-push-names/{id}', [TableButtonsController::class, 'deletePushNames'])->name('delete-push-names');
    Route::post('/save-underline-fonts', [TableButtonsController::class, 'saveUnderline']);
    Route::get('/get-styles/{id}/{tableID}', [TableButtonsController::class, 'getStyles']);
    Route::post('/delete-styles', [TableButtonsController::class, 'deleteFontStyle']);
    Route::post('/save-colors', [TableButtonsController::class, 'saveColors']);
    Route::get('/get-colors/{id}/{tableID}', [TableButtonsController::class, 'getColors']);
    Route::post('/delete-colors-all-cells', [TableButtonsController::class, 'deleteColorssAllCells']);




    // UPDATING DATA_ROOM_STUDENT
    Route::post('/update-students', [UsefulController::class, 'updatesStudents']);
    Route::post('/create-student-name/{id}', [UsefulController::class, 'addStudent'])->name('add-student');

    Route::put('/update-skills', [UsefulController::class, 'updateTotal']);

    // DELETING OF ROOM
    Route::delete('/teacher-room/{id}', [RoomController::class, 'destroyRoom'])->name('teacherRoom.delete');
    Route::delete('/student-room/{id}', [RoomStudentController::class, 'destroyRoom'])->name('studentRoom.delete');

    // DELETING NAME OF STUDENT IN PASTE ROOM
    Route::delete('/teacher-room/paste-form/delete-students', [RoomController::class, 'destroyStudentName'])->name('pasteRoom.delete');


    // Route::post('/structure-names', [UsefulController::class, 'structureNames']);

     // Link Page
     Route::get('/r/{token}', [RoomStudentController::class, 'showLink'])->name('share');
     Route::post('/joined-class/{id}', [RoomStudentController::class, 'joinedClass'])->name('joinClass');

    //  IMPORTING ROUTES
    Route::post('/teacher-room/import/{id}', [OtherController::class, 'importHighlighted'])->name('importHighlighted');
    Route::post('/upload-pdf/import/{id}', [PdfController::class, 'upload'])->name('importPdf');
    // EXPORTING ROUTES
    Route::post('/export-to-excel/{id}', [OtherController::class, 'exportToExcel']);

    // NOTIFICATIONS
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/delete-all/{id}', [NotificationController::class, 'deleteNotifs']);
    
});

// Guest Pages
Route::middleware('guest')->group(function() {

    // Authentication
    Route::view('auth/register', 'auth.register')->name('register');
    Route::post('auth/register', [AuthController::class, 'register']);
    
    Route::view('auth/login', 'auth.login')->name('login');
    Route::post('auth/login', [AuthController::class, 'login']);

    // Landing Page
    // Route::view('/', 'navigations.landing')->name('landing');

    // Landing Page
    Route::view('/', 'navigations.landing')->name('landing');

});

// Route::get('/home', [OtherController::class, 'track'])->middleware('restrictHomeAfterNavigation')->name('home');

Route::middleware(['auth', 'role:Teacher', 'no-cache'])->group(function() {
    // Display Rooms
    Route::get('/home/teacher-room', [RoomController::class, 'show'])->name('teacher-home');
    // Calendar
    Route::get('/home/teacher-room/calendar', [RoomController::class, 'teacherCalendar'])->name('teacher-calendar');
    // Settings
    Route::get('/home/teacher-room/settings', [RoomController::class, 'teacherSettings'])->name('teacher-setting');
    // Display of Records
    Route::get('/teacher-room/records/{id}', [RoomController::class, 'showRecord'])->name('teacher.room');
    // Sample Route
    Route::get('/teacher-room/records/class-record/{id}', [UsefulController::class, 'showSample'])->name('sample.room');

    // Route::get('/teacher-room/records/class-record/{id}', [UsefulController::class, 'getRoomID'])->name('sample.room');

    // Attendance
    Route::get('/teacher-room/records/attendance/{id}', [UsefulController::class, 'showAttendance'])->name('attendance.room');
    // Ratings 
    Route::get('/teacher-room/records/ratings/{id}', [UsefulController::class, 'showRatings'])->name('ratings.room');

        // Attendance
        // Route::get('/teacher-room/records/attendace/{id}', [UsefulController::class, 'showAttendance'])->name('attendance.room');
    // Displaying of SETTINGS of every ID's
    Route::get('/teacher-room/records/settings/{id}', [RoomController::class, 'settings'])->name('room-setting');

    Route::post('/structure-names', [UsefulController::class, 'structureNames']);

    Route::post('/teacher-room/records/total-cols-rows/{id}', [TableButtonsController::class, 'totalRowsCols'])->name('total-row-col');
    // Saving Donec Checked
    Route::post('/saved-done-check/{id}', [TableButtonsController::class, 'doneCheck']);

    // Performing Formula for Rating Table
    Route::post('/apply-formula/grading-sheet', [UsefulController::class, 'getFormulaForRating']);
    Route::post('/apply-units', [UsefulController::class, 'getUnits']);
    Route::post('/apply-sem', [UsefulController::class, 'getSem']);
    Route::post('/save-number-grade', [UsefulController::class, 'getNumberGrade']);

});

Route::middleware(['auth', 'role:Student', 'no-cache'])->group(function() {
    // Display Rooms
    Route::get('/home/student-room', [RoomStudentController::class, 'show'])->name('student-home');
    // Calendar
    Route::get('/home/student-room/calendar', [RoomStudentController::class, 'studentCalendar'])->name('student-calendar');
    // Settings
    Route::get('/home/student-room/settings', [RoomStudentController::class, 'studentSettings'])->name('student-setting');

    Route::post('/student-room/track/{id}', [RoomStudentController::class, 'checkCode']);
    Route::get('/student-room/student-record/{id}', [RoomStudentController::class, 'showRoom'])->name('studentRecord.room');
    Route::get('/student-room/student-attendance/{id}', [RoomStudentController::class, 'showAttendance'])->name('studentAttendance.room');
    Route::get('/student-room/records/{id}', [RoomStudentController::class, 'showRecord'])->name('student.room');
});

Route::middleware(['auth', 'restrictAfterRole'])->group(function() {

    Route::get('/home', [UsefulController::class, 'home'])->name('home');
    Route::post('/home', [UsefulController::class, 'fullName'])->name('full-name');
    Route::view('/settings', 'navigations.setting')->name('setting');
    
});
