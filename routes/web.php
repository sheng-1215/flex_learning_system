<?php

use App\Http\Middleware\checkauth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ajaxController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\AdminController;

Route::controller(ViewController::class)->group(function () {
    Route::get('/','login')->name('login');
    Route::get('/register','register')->name('register');
    Route::get('/admin_dashboard', 'adminDashboard')->name('admin_dashboard');
    Route::get('/register/studentVerify','register_studentVerify')->name('register.studentVerify');
    Route::get('/studentVerifyForm/{id}','studentVerifyForm')->name('register.verifyForm');
    Route::get('/verifyStudent','verifyStudent')->name("VerifyStudent");
    
    
});

Route::controller(FunctionController::class)->group(function () {
    Route::post('/login', 'login')->name('loginFunction');
    Route::post('/register', 'register')->name('registerFunction');
    Route::post('/logout', 'logout')->name('logoutFunction');
    Route::post('/register/studentVerify','register_studentVerify')->name('register.studentVerify.function');
    Route::post('/register/studentVerifyForm/{id}','verifyForm')->name('register.verifyForm.function');
});

Route::middleware(checkauth::class)->group(function () {
    Route::controller(AdminController::class)->group(function() {
        Route::post("/admin/importStudent/{id}", 'importStudent')->name('admin.importStudent');
        Route::get('/admin/courses', 'courses')->name('admin.courses');
        Route::post('/admin/courses', 'addCourse')->name('admin.addCourse');
        Route::get('/admin/courses/{course}/edit', 'editCourse')->name('admin.editCourse');
        Route::put('/admin/courses/{course}', 'updateCourse')->name('admin.updateCourse');
        Route::delete('/admin/courses/{course}', 'destroyCourse')->name('admin.destroyCourse');
        Route::get('/admin/addUserToCourse/{course}','addUserToCourse')->name('admin.addUserToCourse');
        Route::post('/admin/addUserToCourse/{course}','submitUserToCourse')->name('admin.submitUserToCourse');
        Route::delete('/admin/removeUserFromCourse','removeUserFromCourse')->name('admin.removeUserFromCourse');

        
        Route::get('/admin/student/register', 'registerStudentView')->name('admin.registerStudentView');
        Route::post('/admin/student/register', 'registerStudent')->name('admin.registerStudent');

        Route::get('/admin/users', 'users')->name('admin.users');
        Route::get('/admin/user/{user}/edit', 'editUser')->name('admin.editUser');
        Route::put('/admin/user/{user}', 'updateUser')->name('admin.updateUser');
        Route::delete('/admin/user/{user}', 'destroyUser')->name('admin.destroyUser');
        Route::get('/admin/courses/{course}/cuactivities', 'viewCourseActivities')->name('admin.courseActivities');
        Route::get("/admin/activities/{activity}/edit",'viewEditActivity')->name('admin.editCUActivity');
        Route::put("/admin/activities/{activity}/edit",'EditActivity')->name('admin.activity.edit');
        Route::delete('/admin/activities/{activity}/destroy','destroyActivity')->name('admin.destroyCUActivity');
        Route::post('/admin/courses/cuactivities/add', 'addCourseActivity')->name('admin.addCourseActivity');
        Route::get('/admin/cuactivities/{activity}/topic', 'viewActivitiesTopic')->name('admin.viewActivitiesTopic');
        Route::post("/admin/topic/add", 'addTopicToActivity')->name('admin.addTopicToActivity');
        Route::delete('admin/topics/{topic}/delete','deleteActivityTopic')->name('admin.deleteActivityTopic');
        
        Route::get("/admin/topics/{topic}/download", 'downloadTopic')->name('admin.downloadTopic');

        
        Route::get('/admin/assignments/select-course', 'selectCourseForAssignment')->name('admin.selectCourseForAssignment');
        Route::get('/admin/assignments/select-cuactivity/{course}', 'selectActiviryForAssignment')->name('admin.selectActiviryForAssignment');
        Route::get('/admin/topics/{activity}/assignments', 'viewActivityAssignments')->name('admin.activityAssignment.view');
        Route::post('/admin/assignments/{course}/add', 'addAssignmentToActivity')->name('admin.activityAssignment.add');
        Route::delete('/admin/cuactivities/{activity}/assignment/{assignment}/delete', 'deleteAssignmentFromActivity')->name('admin.activity.assignment.delete');
        Route::get('/admin/courses/{course}/assignments', 'viewCourseAssignments')->name('admin.assignments.view');
        Route::get('/admin/courses/{course}/assignments/add', 'addAssignmentToCourse')->name('admin.assignments.add');
        Route::post('/admin/courses/{course}/assignments/add', 'addAssignmentToCourse')->name('admin.assignments.add.post');
        Route::post('/admin/courses/{course}/assignments', 'storeAssignmentToCourse')->name('admin.assignments.store');
        Route::get('/admin/courses/{course}/assignments/{assignment}/edit', 'editAssignment')->name('admin.assignment.edit');
        Route::put('/admin/courses/{course}/assignments/{assignment}', 'updateAssignment')->name('admin.assignment.update');
        Route::delete('/admin/courses/{course}/assignments/{assignment}', 'deleteAssignment')->name('admin.assignment.delete');
        Route::get('/admin/assignments/{assignment}/topics', 'viewAssignmentTopics')->name('admin.assignment.topics');
        Route::get('/admin/check-assignments/{assignment}', 'checkAssignments')->name('admin.checkAssignments');
        Route::post('/admin/grade-assignments/{assignmentsubmit}', 'gradeAssignments')->name('admin.gradeAssignments');
        Route::post('/admin/feedback-assignments/{assignmentsubmit}', 'feedbackAssignments')->name('admin.feedbackAssignments');
        Route::get('/admin/assignments/{assignment}/topics/add', 'addTopic')->name('admin.topic.add');
        Route::post('/admin/assignments/{assignment}/topics/add', 'storeTopic')->name('admin.topic.store');
        Route::get('/admin/topics/{topic}/edit', 'editTopic')->name('admin.topic.edit');
        // Route::put('/admin/topics/{topic}', 'updateTopic')->name('admin.topic.update');
        Route::put('/admin/topics/{assignment}/{topic}', 'updateTopic')->name('admin.topic.update');
        Route::delete('/admin/topics/{topic}', 'deleteTopic')->name('admin.topic.delete');
        Route::get('/admin/assignments/{assignment}/topics/{topic}/files', 'viewTopicFiles')->name('admin.topic.files');
        Route::get('/admin/check-assignments/activities', 'showCheckAssignments')->name('admin.checkassignmentsStatus');
    });
});

Route::middleware(checkauth::class)->group(function () {
    Route::prefix("student")->group(function () {
        Route::controller(ViewController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('student.dashboard');
            Route::get('/CUActivity/{id}', 'CUActivity')->name('student.CUActivity');
            Route::get('/profile',"profile")->name('student.profile');
            Route::get('/profile/edit',"profile_edit")->name('student.profile.edit');
            Route::get('/assignment','assignment')->name('student.assignment');
            // Route::get('/assignmentDetail/{id}','assignmentDetail')->name('student.assignment.detail');
            Route::get('/assignmentDetail/{id}','assignmentSubmit')->name('student.assignment.detail');
        });
        Route::controller(FunctionController::class)->group(function () {
            Route::post('/login', 'login')->name('student.loginFunction');
            Route::post('/logout', 'logout')->name('student.logoutFunction');
            Route::post('/assignmentSubmit/{id}', 'assignmentSubmit')->name('student.assignment.submit');
            Route::get('/downloadAssignment/{id}', 'downloadAssignment')->name('student.assignment.download');
            Route::delete('/assignmentDelete/{id}', 'assignmentDelete')->name('student.assignment.delete');
        });

        Route::controller(ajaxController::class)->group(function () {
            Route::post('/topic_progress/update', 'topic_progress_update')->name('student.topic.progress.update');
        });
    });
});