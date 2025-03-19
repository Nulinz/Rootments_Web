<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

     Route::post('/auth/login', 'AuthController@login')->name('auth.login');

     Route::post('/update_popup', 'AuthController@popup')->name('app.version');

    // Route::post('/auth/login', function(){
    //     return ("hello");
    // })->name('auth.login');


    Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/auth/logout', 'AuthController@logout')->name('auth.logout');

    Route::post('/auth/authpassword-update','AuthController@update')->name('change_password.update');

        // Task
        Route::get('tasks', 'TaskController@index')->name('tasks');
        Route::get('category', 'TaskController@getcategories')->name('category');
        Route::get('subcategory', 'TaskController@getsubcategories')->name('subcategory');
        Route::get('task-rolelist', 'TaskController@create')->name('task.rolelist');
        Route::post('tasks-store','TaskController@store')->name('tasks.store');
        Route::post('tasks-completedtaskstore','TaskController@completedtaskstore')->name('tasks.completedtaskstore');
        Route::post('update_task','TaskController@updateTaskStatus')->name('update.task');


        // Leave Request

        Route::get('leaverequest-list', 'TaskController@leavelist')->name('leaverequest.list');
        Route::get('reginationrequest-list', 'TaskController@reginationlist')->name('reginationrequest.list');
        Route::get('transferquest-list', 'TaskController@transferlist')->name('transferrequest.list');
        Route::post('leave-store','mobile_cnt@leavestore')->name('leave.store');
        Route::post('regination-store','TaskController@reginationstore')->name('regination.store');
        Route::post('transfer-store','TaskController@transferstore')->name('transfer.store');
        Route::get('store-list', 'TaskController@storelist')->name('store.list');

        // resignation routes

        Route::post('resign-store', 'mobile_cnt@res_store')->name('resign-store'); // insert the resignation table
        Route::post('resign-show', 'mobile_cnt@resign_show')->name('resign-show'); // insert the resignation table



        //Notifications

        Route::get('noty-list', 'TaskController@notification_list')->name('noty.list');

        // Time line

        Route::get('tasktimeline', 'TaskController@tasktimeline')->name('tasktimeline.list');

        // Attendance staus

       Route::get('attd_row', 'mobile_cnt@attd_row')->name('attd_status');

       // Attendance Insert and update

       Route::post('attd_in', 'mobile_cnt@attd_in')->name('attd_in');
       Route::post('attd_out', 'mobile_cnt@attd_out')->name('attd_out');



       Route::post('/assign_to', 'mobile_cnt@assign_to')->name('assign_to');

       // task create show API

       Route::post('/tasks_show', 'mobile_cnt@create_task_show')->name('tasks_show');


    });

              //post method HR
       Route::post('hr_list', 'TaskController@hr_list')->name('hr_list');

});

