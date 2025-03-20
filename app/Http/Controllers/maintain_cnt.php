<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\trait\common;

class maintain_cnt extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $main_emp = $this->attd_index('Maintenance');

        $men = DB::table('roles')->where('role_dept', 'Maintenance')
        ->leftJoin('users as us','us.role_id','=','roles.id')
        ->pluck('us.id')->toArray();

        // $men = [];

        //  dd($men);

        // foreach($role_mem as $role_men1){

        //          $men[] = $role_men1->id;

        //         // dd($role_men1->id);
        // }




        if (!empty($men)) {

            $totaltask = DB::table('tasks')
                ->whereIn('tasks.assign_to',$men)
                ->selectRaw("
                    SUM(CASE WHEN task_status = 'To Do' THEN 1 ELSE 0 END) AS todo,
                    SUM(CASE WHEN task_status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                    SUM(CASE WHEN task_status = 'On Hold' THEN 1 ELSE 0 END) AS on_hold,
                    SUM(CASE WHEN task_status = 'Completed' THEN 1 ELSE 0 END) AS completed
                ")
                ->first();

            $task = [
                'todo' => $totaltask->todo ?? 0,
                'in_progress' => $totaltask->in_progress ?? 0,
                'on_hold' => $totaltask->on_hold ?? 0,
                'completed' => $totaltask->completed ?? 0,
            ];
        }
        else {
            $task = [
                'todo' => 0,
                'in_progress' => 0,
                'on_hold' => 0,
                'completed' => 0,
            ];
        }

        //  dd($task);

        if (!empty($men)) {
            $teampertask = DB::table('tasks')
                ->join('users', 'tasks.assign_to', '=', 'users.id')
                ->whereIn('tasks.assign_to',$men)
                ->selectRaw("
                    users.name,
                    COUNT(*) AS total_tasks
                ")
                ->groupBy('users.name')
                ->get();
        } else {
            $teampertask = collect();
        }

        $staffNames = $teampertask->pluck('name')->toArray();
        $taskCounts = $teampertask->pluck('total_tasks')->toArray();

        //   dd($taskCounts);


        // category and subcategory charts.....

            $categoryTask = DB::table('tasks')
                    ->join('categories', 'tasks.category_id', '=', 'categories.id')
                    ->whereIn('tasks.assign_to',$men)
                    ->where('tasks.task_status', 'Completed')
                    ->select(
                        'categories.category',
                        DB::raw('COUNT(*) as total_tasks')
                    )
                    ->groupBy('categories.category')
                    ->get();

                $categoryNames = $categoryTask->pluck('category')->toArray();
                $categorytaskCounts = $categoryTask->pluck('total_tasks')->toArray();

            $subcategoryTask = DB::table('tasks')
                    ->join('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
                    ->join('categories', 'tasks.category_id', '=', 'categories.id')
                    ->whereIn('tasks.assign_to', $men)
                    ->where('tasks.task_status', 'Completed')
                    ->select(
                        'categories.category',
                        'sub_categories.subcategory',
                        DB::raw('COUNT(*) as subtotal_tasks')
                    )
                    ->groupBy('categories.category', 'sub_categories.subcategory')
                    ->get();
            $subcategoryNames = $subcategoryTask->pluck('subcategory')->toArray();
            $subcategorytaskCounts = $subcategoryTask->pluck('subtotal_tasks')->toArray();


                // dd($categoryNames);

               if (!empty($men)) {
                $pendingLeaves = DB::table('leaves')
                    ->leftJoin('users', 'leaves.user_id', '=', 'users.id')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->select(
                        'leaves.*',
                        'users.name',
                        'users.profile_image',
                        'users.emp_code',
                        'users.store_id',
                        'leaves.request_to'
                    )
                    ->whereIn('users.id', $men)
                    ->where('leaves.request_to',  $user->id)
                    ->where('leaves.request_status', 'Pending')
                    ->get();
            } else {
                $pendingLeaves = collect();
            }


        return view('maintain.maintain_index',['main_emp'=>$main_emp,'task'=>$task,'staffNames'=>$staffNames,'taskCounts'=>$taskCounts,'categoryNames'=>$categoryNames,'categorytaskCounts'=>$categorytaskCounts,'subcategoryNames'=>$subcategoryNames,'subcategorytaskCounts'=>$subcategorytaskCounts,'pendingLeaves'=>$pendingLeaves]);
    }

    public function list()
    {
        return view('maintain.maintain_list');
    }

    public function profile(Request $request)
    {
        return view('maintain.maintain_profile');
    }

    public function task(Request $request)
    {
        return view('maintain.maintain_task');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
