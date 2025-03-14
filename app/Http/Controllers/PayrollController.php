<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

            $user_list = collect(); // This will set $u_list to an empty collection

            $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

              // stores for dropdown....

        $stores = DB::table('stores')->get();


        return view ('payroll.list',['u_list'=>$user_list,'dept'=>$dept,'stores'=> $stores]);
    }

    public function drop_show(Request $req)
    {
        $sal_mon = $req->sal_mon;

        $sal = explode('-',$sal_mon);

        $year = $sal[0];
        $mon = $sal[1];

        $store = DB::table('stores') // Start by selecting all stores
        ->leftJoin('m_salary', function($join) use ($mon, $year) {
            $join->on('stores.id', '=', 'm_salary.store') // Join on the store id
                 ->where('m_salary.month', '=', $mon)  // Filter by the provided month
                 ->where('m_salary.year', '=', $year); // Filter by the provided year
        })
        ->whereNull('m_salary.store') // Exclude stores that have matching records in m_salary
        ->select('stores.id','stores.store_name','stores.store_code') // Select the columns from stores table
        ->get(); // Retrieve the result

    // The $store will now contain only those stores that don't have a matching entry in the m_salary table for the given month and year




        // return $store;

        return response()->json($store);


        //   return view('payroll.list',['store'=>$store]);
    }
    /**
     * Show the form for creating a new resource.
     */


    public function store_per(Request $req)
    {
        // $user_list = DB::table('users as us')->where('store_id',$req->store)
        // ->select('us.name','us.emp_code','us.base_salary')
        // ->get();

        // dd($req->all());

        $sal_mon = $req->month;

        $user_list = DB::table('users as us')
        ->leftJoin('roles', 'roles.id', '=', 'us.role_id')
        ->leftJoin('attendance as a', function($join) use ($sal_mon) {
            $join->on('a.user_id', '=', 'us.id')
                ->whereRaw("DATE_FORMAT(a.c_on, '%Y-%m') = ?", [$sal_mon]);  // Filter attendance by month (YYYY-MM)
        })
        ->leftJoin('attd_ot as ot', function($join) use ($sal_mon) {
            $join->on('a.id', '=', 'ot.attd_id')  // Join attendance with attd_ot on the ID and attd_id
                 ->where('ot.status', '=', 'approved')  // Filter attd_ot where status is 'approved'
                 ->whereRaw("DATE_FORMAT(ot.created_at, '%Y-%m') = ?", [$sal_mon]);  // Filter attd_ot by month (YYYY-MM)
        })
        ->when($req->dept == 'Store', function ($join) use ($req) {
            return $join->leftJoin('stores', 'users.store_id', '=', 'stores.id')
                         ->where('users.store_id', $req->store);
        })
        ->when($req->dept != 'Store', function ($join) use ($req) {
            return $join->where('roles.role_dept', '=', $req->dept);
        })
        // ->where('us.store_id', $req->store)  // Filter by store_id
        ->select(
            'us.id as emp_id',
            'us.name',
            'us.emp_code',
            'us.base_salary',
            DB::raw('COUNT(a.id) as attendance_count'),  // Count attendance records for the given month
            DB::raw('SUM(CASE WHEN ot.cat = "late" THEN ot.amount ELSE 0 END) as total_late'),  // Sum for 'late'
            DB::raw('SUM(CASE WHEN ot.cat = "ot" THEN ot.amount ELSE 0 END) as total_ot')  // Sum for 'ot'
        )
        ->groupBy('us.id')  // Group by user_id
        ->get();



        $sal = explode('-',$sal_mon);

        $year = $sal[0];
        $mon = $sal[1];



        //   return $user_list;

          return view ('payroll.list',['u_list'=>$user_list,'post_store'=>$req->store,'post_mon'=>$sal_mon,'twd'=>$req->twd]);
    }

    public function payroll_list()
    {
    //    $store =  DB::table('m_salary')
    //     ->groupBy('store')
    //     ->leftJoin('stores','stores.id','=','m_salary.store')
    //     ->select('stores.store_name','stores.id')
    //     ->get();

    $dept = DB::table('roles')
    ->where('id', '!=', 1)
    ->select('role_dept')
    ->distinct()
    ->get();

      // stores for dropdown....

    $store = DB::table('stores')->get();
        // return $store;
         return view ('payroll.payroll_list',['store'=>$store,'dept'=>$dept]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {

        $sal_mon = $req->month;

        $sal = explode('-',$sal_mon);

        $year = $sal[0];
        $mon = $sal[1];


        foreach ($req->empId as $key=> $value) {
            // Optionally calculate the total here if it isn't already calculated in $emp->total
            // $total = $emp->salary + $emp->incentive + $emp->ot + $emp->bonus - $emp->deduct - $emp->advance;

            DB::table('m_salary')->insert([
                'month' => $mon,
                'year' => $year,
                'store' => $req->store,
                'emp_id' => $req->empId[$key],
                'salary' => $req->salary[$key],
                'total_work' => $req->totalWork[$key],
                'present' => $req->present[$key],
                'lop' => $req->lop[$key],
                'incentive' => $req->incentive[$key],
                'ot' => $req->ot[$key],
                'deduct' => $req->deduct[$key],
                'bonus' => $req->bonus[$key],
                'advance' => $req->advance[$key],
                'total' => $req->total[$key],  // Ensure the total is calculated before insertion
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }



        return redirect()->route('payroll.payroll_list')->with([
            'status' => 'success',
            'message' => 'Salary Added successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function store_list(Request $req)
    {

            $store = $req->store;



            $store_lt = DB::table('m_salary') // Start by selecting all stores
            ->where('store',$store)
           ->select('m_salary.month')
           ->groupBy('store') // Select the columns from stores table
            ->get(); // Retrieve the result

        // The $store will now contain only those stores that don't have a matching entry in the m_salary table for the given month and year


        $monthMapping = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        $monthArray = [];

        // Iterate through each record in the result
        foreach ($store_lt as $item) {
            // Convert the month to a two-digit format
            $monthNumber = str_pad($item->month, 2, '0', STR_PAD_LEFT);

            // If the month number exists in the $monthMapping array, add it to the $monthArray
            if (isset($monthMapping[$monthNumber])) {
                $monthArray[$monthNumber] = $monthMapping[$monthNumber];
            }
        }

            // return $monthArray;

            return response()->json($monthArray);


            //   return view('payroll.list',['store'=>$store]);
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function salary_list(Request $req)
    {
        //  dd($req->all());

        $sal_mon = $req->month;

        $sal = explode('-',$sal_mon);

        $year = $sal[0];
        $mon = $sal[1];

        $sal_list = DB::table('m_salary as ms')->where('ms.month', $mon)
        ->leftJoin('users', 'users.id', '=', 'ms.emp_id')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        ->when($req->dept == 'Store', function ($join) use ($req) {
            return $join->where('ms.store', $req->store);
        })
        ->when($req->dept != 'Store', function ($join) use ($req) {
            return $join->where('roles.role_dept', '=', $req->dept);
        })
        ->select('ms.*', 'users.name','users.emp_code')
        ->get();

         return view ('payroll.payroll_list',['sal_list'=>$sal_list]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
