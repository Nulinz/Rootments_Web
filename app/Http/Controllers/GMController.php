<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\trait\common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GMController extends Controller
{
    use common;
    public function index()
    {

        $user = Auth::user();
        $hr_emp = $this->attd_index('HR');

        $roleData = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->select('roles.role', DB::raw('COUNT(users.id) as count'))
        ->groupBy('roles.role')
        ->orderByDesc('count')
        ->get();

        $roleNames = [];
        $userCounts = [];

        foreach ($roleData as $data) {
            $roleNames[] = $data->role;
            $userCounts[] = (int) $data->count;
        }

        // dd($hr_emp);

        $pendingLeaves = DB::table('leaves')->where('leaves.request_to', $user->id)->where('leaves.request_status', 'Pending')
        ->leftJoin('users', 'users.id', '=', 'leaves.user_id')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        ->select('leaves.id', 'users.name', 'users.emp_code','users.profile_image', 'roles.role', 'roles.role_dept', 'leaves.request_status', 'leaves.request_type','leaves.start_date','leaves.end_date')
        ->get();


        $store_per = DB::table('stores')
        ->join('users as us', 'us.store_id', '=', 'stores.id')
        ->join('attendance as att', 'att.user_id', '=', 'us.id')
        ->whereDate('att.c_on', Carbon::today()) // Filter for today's date
        ->where('att.attend_status', 'Present') // Assuming 'status' holds the present status, adjust if needed
        ->select(
            'stores.id',
            'stores.name',
            DB::raw('count(us.id) as members_count'),
            DB::raw('count(att.id) as present_today_count') // Count of present users today
        )
        ->groupBy('stores.id', 'stores.name') // Group by store ID and name
        ->get();

        // dd($store_per);

        return view('generalmanager.overview',['hr_emp'=>$hr_emp,'roleNames'=>$roleNames,'userCounts'=>$userCounts,'pendingRequests'=>$pendingLeaves]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
