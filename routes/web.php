<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashBoardController;
use Illuminate\Support\Facades\DB;


Route::view('/', 'login')->name('login');


Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::post('login', 'AuthenticationController@login')->name('login.submit');
    Route::get('logout', 'AuthenticationController@logout')->name('logout');


    Route::middleware(['auth','asm'])->group(function () {

        // Store Dashboard
        Route::get('dashboard', 'DashBoardController@index')->name('dashboard');
        Route::get('dashboard/checkin', 'attd_cnt@attd_row')->name('dashboard.checkin');
        Route::get('dashboard/checkin', 'attd_cnt@attd_row')->name('dashboard.checkin');
        Route::get('store-dashboard', 'DashBoardController@generalstoreindex')->name('store.dashboard');
        Route::get('mydash-dashboard', 'DashBoardController@mydashboardindex')->name('mydash.dashboard');
        Route::post('update_task', 'DashBoardController@updateTaskStatus')->name('update.task');
        Route::post('store_dashboard', 'DashBoardController@useragainststask')->name('store.usertaskdashboard');
        Route::post('attendance-approve', 'DashBoardController@attendanceApprove')->name('attendance.approve');

        // HR Dashboard
        Route::get('hr-dashboard', 'HrDashBoardController@index')->name('hr.dashboard');
        Route::get('hr-mydashboard', 'HrDashBoardController@mydashboard')->name('hr.mydashboard');
        Route::get('hrkpi-dashboard', 'HrDashBoardController@kpidashboard')->name('hrkpi.dashboard');

        // Settings
        Route::get('settings', 'SettingsController@index')->name('settings');
        Route::get('category', 'SettingsController@categorylist')->name('category');
        Route::post('category-store', 'SettingsController@categorystore')->name('category.store');
        Route::post('update-status/{id}', 'SettingsController@updateStatus')->name('update.status');
        Route::get('subcategory', 'SettingsController@subcategoryList')->name('subcategory');
        Route::post('sub-category-store', 'SettingsController@subcategorystore')->name('subcategory.store');
        Route::post('sub-update-status/{id}', 'SettingsController@subupdateStatus')->name('subupdate.status');
        Route::get('roles', 'SettingsController@roleList')->name('roles');
        Route::post('role-store', 'SettingsController@rolestore')->name('role.store');
        Route::post('role-update-status/{id}', 'SettingsController@roleupdateStatus')->name('roleupdate.status');
        Route::get('password', 'SettingsController@passwordList')->name('password');
        Route::post('settings-update', 'SettingsController@passwordupdate')->name('change_password.update');
        Route::get('theme-list', 'SettingsController@themeList')->name('theme');
        Route::get('permissions', 'SettingsController@permissionList')->name('permission');
        Route::post('permission.store', 'SettingsController@permissionstore')->name('permission.store');
        Route::get('permission.filter/{token}', 'SettingsController@filter')->name('permission.filter');

        Route::get('assign_asm', 'SettingsController@assign_asm')->name('assign.assign_asm');
        Route::post('get_assistant', 'SettingsController@get_asm')->name('get_assistant');
        Route::post('insert_asm', 'SettingsController@insert_asm')->name('insert_asm');

        // Store
        Route::get('store-list', 'StoreController@index')->name('store.index');
        Route::get('store-add', 'StoreController@create')->name('store.add');
        Route::post('storea-store', 'StoreController@store')->name('store');
        Route::get('store-view/{id}', 'StoreController@show')->name('store.view');
        Route::get('store-edit/{id}', 'StoreController@edit')->name('store.edit');
        Route::post('store-update/{id}', 'StoreController@update')->name('store.update');
        Route::get('store-strength/{id}', 'StoreController@strlist')->name('store.strength');
        Route::get('store-details/{id}', 'StoreController@detailslist')->name('store.details');
        Route::get('store-viewemp/{id}', 'StoreController@empview')->name('store.viewemp');



        // Employee
        Route::get('employee-list', 'EmployeeController@index')->name('employee.index');
        Route::get('employee-add', 'EmployeeController@create')->name('employee.add');
        Route::post('employee-store', 'EmployeeController@store')->name('employee.store');
        Route::get('employee-jobdetails/{id}', 'EmployeeController@jobindex')->name('jobdetails');
        Route::post('get-role', 'EmployeeController@getrole')->name('get_role');
        Route::post('employee-jobstore/{id}', 'EmployeeController@jobdetailstore')->name('employee.jobstore');
        Route::get('employee-bankdetails/{id}', 'EmployeeController@bankindex')->name('bankdetails');
        Route::post('employee-bankstore/{id}', 'EmployeeController@bankdetailstore')->name('employee.bankstore');
        Route::get('employee-view/{id}', 'EmployeeController@show')->name('employee.view');
        Route::get('employee-details/{id}', 'EmployeeController@empdetails')->name('employee.details');
        Route::get('employee-salary/{id}', 'EmployeeController@salary')->name('employee.salary');
        Route::get('employee-remark/{id}', 'EmployeeController@remarks')->name('employee.remark');
        Route::get('employee-edit/{id}', 'EmployeeController@edit')->name('employee.edit');
        Route::post('employee-update/{id}', 'EmployeeController@update')->name('employee.update');
        Route::get('employee-jobedit/{id}', 'EmployeeController@jobedit')->name('employee.jobedit');
        Route::post('employee-jobupdate/{id}', 'EmployeeController@jobdetailupdate')->name('employee.jobupdate');
        Route::get('employee-bankedit/{id}', 'EmployeeController@bankedit')->name('employee.bankedit');
        Route::post('employee-bankupdate/{id}', 'EmployeeController@bankdetailupdate')->name('employee.bankupdate');

        // Task
        Route::get('task-list', 'TaskController@index')->name('task.index');
        Route::get('completed-task-list', 'TaskController@completed_list')->name('task.completed-task');
        Route::get('task-add', 'TaskController@create_task')->name('task.add');
        // Route::get('task-add/hr', 'TaskController@create_task')->name('task.hr');
        Route::get('task-add/cluster', 'TaskController@create')->name('task.add.cluster');
        Route::post('get-subcategories', 'TaskController@getSubcategories')->name('get_sub_cat');
        Route::post('task-store', 'TaskController@store')->name('task.store');
        Route::get('task-view/{id}', 'TaskController@show')->name('task.view');
        Route::post('completedtaskstore', 'TaskController@completedtaskstore')->name('completedtaskstore');

        // Leave Request
        Route::get('leave-list', 'LeaveController@index')->name('leave.index');
        Route::get('leave-add', 'LeaveController@create')->name('leave.add');
        Route::post('leave-store', 'LeaveController@store')->name('leave.store');
        Route::post('update-leaveescalate', 'LeaveController@updateEscalate')->name('update.leaveescalate');

        // Repair Request
        Route::get('repair-list', 'RepairController@index')->name('repair.index');
        Route::get('repair-add', 'RepairController@create')->name('repair.add');
        Route::post('repair-store', 'RepairController@store')->name('repair.store');
        Route::post('get-storename', 'RepairController@getstorename')->name('get_store_name');

        // Transfer Request
        Route::get('transfer-list', 'TransferController@index')->name('transfer.index');
        Route::get('transfer-add', 'TransferController@create')->name('transfer.add');
        Route::post('transfer-store', 'TransferController@store')->name('transfer.store');
        Route::post('get-empname', 'TransferController@getempname')->name('get_emp_name');
        Route::post('update-transferescalate', 'TransferController@updateEscalate')->name('update.transferescalate');

        // Resignation Request
        Route::get('resignation-list', 'ResignationController@index')->name('resignation.index');
        Route::get('resignation-add', 'ResignationController@create')->name('resignation.add');
        Route::post('resignation-store', 'ResignationController@store')->name('resignation.store');
        Route::post('update-reginescalate', 'ResignationController@updateEscalate')->name('update.reginescalate');

        // Recruitment Request
        Route::get('recruitment-list', 'RecruitmentController@index')->name('recruitment.index');
        Route::get('recruitment-add', 'RecruitmentController@create')->name('recruitment.add');
        Route::post('recruitment-store', 'RecruitmentController@store')->name('recruitment.store');

        // Request Approval
        Route::get('approve-list', 'ApproveController@index')->name('approve.index');
        Route::get('approveleave-list', 'ApproveController@leaveindex')->name('approveleave.index');
        Route::get('approverepair-list', 'ApproveController@repairindex')->name('approverepair.index');
        Route::get('approvetransfer-list', 'ApproveController@transferindex')->name('approvetransfer.index');
        Route::get('approveresgin-list', 'ApproveController@resginindex')->name('approveresgin.index');
        Route::get('approverecruit-list', 'ApproveController@recruitindex')->name('approverecruit.index');
        Route::post('approveleave-update', 'ApproveController@updateLeave')->name('approveleave.update');
        Route::post('approveleaveesulate-update', 'ApproveController@updateLeave')->name('approveleaveesulate.update');
        Route::post('approvelrepair-update', 'ApproveController@updaterepair')->name('approvelrepair.update');
        Route::post('approvelresgin-update', 'ApproveController@updateresgin')->name('approvelresgin.update');
        Route::post('approvelrecurit-update', 'ApproveController@updaterecuirt')->name('approvelrecurit.update');
        Route::post('approveltransfer-update', 'ApproveController@updatetransfer')->name('approveltransfer.update');

        // Cluster
        Route::get('cluster-list', 'ClusterController@index')->name('cluster.index');
        Route::get('cluster-create', 'ClusterController@drop_show')->name('cluster.new');
        Route::get('cluster-profile/{id}', 'ClusterController@show')->name('cluster.profile');
        Route::get('cluster-edit', 'ClusterController@edit')->name('cluster.edit');
        Route::post('cluster-submit', 'ClusterController@create')->name('cluster.submit');
        Route::get('cluster-overview', 'ClusterController@cluster_overview')->name('cluster.dashboard');
        Route::get('cluster-mydashboard', 'ClusterController@cluster_mydashboard')->name('cluster.mydashboard');
        Route::get('cluster-strength', 'ClusterController@cluster_strength')->name('cluster.strength');

        // AJAX Route
        Route::post('/get_area_per', 'AreaController@area_per')->name('get_area_per');
        Route::post('/get_cluster_store', 'AreaController@cluster_store')->name('get_cluster_store');

        Route::post('/get_cluster_per', 'ClusterController@cluster_det')->name('get_cluster_per');
        Route::post('/get_store_per', 'Attd_cnt@get_store_per')->name('get_store_per');

        Route::post('payroll-drop', 'PayrollController@drop_show')->name('payroll.drop');
        Route::post('payroll-list', 'PayrollController@store_per')->name('payroll.listPerson');
        Route::post('payroll-list-insert', 'PayrollController@store')->name('payroll.insert');
        Route::post('payroll-drop-store', 'PayrollController@store_list')->name('payroll.store_list');

        Route::post('/get_ind_attd', 'Attd_cnt@get_ind_attd')->name('get_ind_attd');

        // Payroll
        Route::get('payroll-list', 'PayrollController@index')->name('payroll.index');
        Route::get('view-salary', 'PayrollController@payroll_list')->name('payroll.payroll_list');
        Route::post('view-salary', 'PayrollController@salary_list')->name('salary.list');

        // Attendance
        Route::get('daily-attendance', 'Attd_cnt@daily_attd')->name('attendance.daily');
        Route::post('daily-attendance', 'Attd_cnt@daily_attd')->name('attendance.list');
        Route::get('monthly-attendance', 'Attd_cnt@monthly_attd')->name('attendance.monthly');
        Route::post('monthly-attendance', 'Attd_cnt@monthly_attd')->name('attendance.monthly_list');
        Route::get('individual-attendance', 'Attd_cnt@individual_attd')->name('attendance.individual');
        Route::get('overtime-attendance', 'Attd_cnt@overtime_attd')->name('attendance.overtime');
        Route::post('get-coordinates', 'location_cnt@index')->name('get.coordinates');
        Route::post('attendance-ot', 'Attd_cnt@ot_approve')->name('ot.approve');

        // Recruitment
        Route::get('recruit-list', 'RecruitController@list')->name('recruit.list');
        Route::get('recruit-add', 'RecruitController@create')->name('recruit.add');
        Route::get('recruit-edit', 'RecruitController@edit')->name('recruit.edit');

        // Area
        Route::get('area-list', 'AreaController@list')->name('area.list');
        Route::get('area-create', 'AreaController@create')->name('area.add');
        Route::post('area-create', 'AreaController@create_area')->name('area.create');
        Route::get('area-profile/{id}', 'AreaController@show')->name('area.profile');
        Route::get('area-edit', 'AreaController@edit')->name('area.edit');
        Route::get('area-overview', 'AreaController@area_overview')->name('area.dashboard');
        Route::get('area-mydashboard', 'AreaController@area_mydashboard')->name('area.mydashboard');
        Route::get('area-kpidashboard', 'AreaController@area_kpi')->name('area.kpidashboard');

        // Route::get('get_model', function() {
        //     //  $role = \App\Models\Role::with(['users_rel:id,name,role_id'])->select('id', 'role_dept')->whereIn('id', [3, 4, 5])->whereHas('users_rel')
        //     // // $role = \App\Models\User::with(['role_rel:id,role_dept'])  // Eager load the 'role_rel' relationship with specified columns
        //     // // ->select('id', 'role_id', 'name')  // Select only 'id', 'role_id', and 'name' from the 'users' table
        //     // // ->whereIn('role_id', [3, 4, 5])  // Filter users with specific role_ids
        //     // ->get();

        //     // $role = \App\Models\Role::all();

        //     DB::enableQueryLog();
        //     // $user = \App\Models\User::with([
        //     //     'role_rel' => function ($query) {
        //     //         $query->where('role_dept', 'store')  // Apply filter on the `role_dept` column
        //     //               ->select('id', 'role', 'role_dept');  // Select specific columns from `Role`
        //     //     },
        //     //     'store_rel' => function ($query) {
        //     //         $query->select('id', 'store_name');  // Select specific columns from `Store`
        //     //     }
        //     // ])
        //     // ->select('id', 'name', 'emp_code', 'store_id', 'role_id')  // Select specific columns from the `User` model
        //     // ->get();


        //     // $roles = \App\Models\Role::  // Add withCount to count the related users
        //     //     with([
        //     //         'users' => function ($query) {
        //     //             // Select specific columns from the `users` model
        //     //             $query->select('id', 'name', 'emp_code', 'role_id');
        //     //         }
        //     //     ])
        //     //     ->withCount('users')
        //     //     ->wherehas('users')  // Only roles that do not have users
        //     //     ->where('role_dept', 'Store')  // Filter roles by `role_dept`
        //     //     ->select('id', 'role_dept')  // Select specific columns from the `Role` model
        //     //     ->get();


        //         // $role = \App\Models\Role::with('users:id,name,role_id')->where('role_dept','Store')->get();  // Find role with id 1
        //         // $users = $role->users;

        //         $role = \App\Models\Role::with('users:id,name,role_id')->where('role_dept','Store')->get();  // Find role with id 1





        //     // $user = \App\Models\User::with([
        //     //     'role_rel',
        //     //     'store_rel'
        //     // ])
        //     // ->select('id', 'name', 'emp_code','store_id','role_id')  // Select specific columns
        //     // ->get();

        //     //  dd(DB::getQueryLog());
        //     // $pwd = Hash::make('123456');


        //     //  dd(DB::getQueryLog());

        //     // $role = \App\Models\Role::with(['users_rel' => function($query) {
        //     //     // Filter users by their IDs (3, 4, 5)
        //     //     $query->whereIn('id', [3, 4, 5]);
        //     // }])->get();
        //     // $role = \App\Models\Role::find(12);  // Find the role with role_id 12
        //     // $users = $role->users_rel;  // Get all users who belong to role_id 12


        //             // $cluster = \App\Models\Cluster::with('cluster_store')->find(1);
        //             // foreach ($role as $u) {
        //             //    echo  $rel =  $u->name."<br>";
        //             //     // foreach($rel as $r){
        //             //     //     echo  $r->name. "<br>";
        //             //     // }
        //             //     // echo $role->role_dept . "<br>";
        //             // }
        //                   return response()->json($role);
        // });

         // Finance
         Route::get('finance_index', 'fin_cnt@index')->name('fin.index');
         // Maintainence
         Route::get('maintain_index', 'maintain_cnt@index')->name('maintain.index');
         // Warehouse
         Route::get('warehouse_index', 'warehouse_cnt@index')->name('warehouse.index');
         // Purchase
         Route::get('purchase_index', 'purchase_cnt@index')->name('purchase.index');

    });

});
