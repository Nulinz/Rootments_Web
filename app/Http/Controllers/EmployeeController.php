<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\trait\common;

class EmployeeController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        // dd($req->status);
        $status = $req->status;
      $user = Auth::user();

    if($user->role_id == 12){

        $query = DB::table('users')->where('status', $status)
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                'users.id',
                'users.name',
                'users.emp_code',
                'users.contact_no',
                'users.email',
                'stores.store_name',
                'roles.role',
                'roles.role_dept'
            );

            if ($user->dept !== 'Admin' && $user->dept !== 'HR') {
                $query->where('users.store_id', $user->store_id)
                    ->where('users.id', '!=', 1);
            }

        $employees = $query->get();

    }else{

         $dept = DB::table('roles')->where('id',$user->role_id)->select('role_dept')->first();

        //  dd($dept);
         $employees = $this->get_emp_dept($dept->role_dept, $status);

        //   dd($employees);
    }

    // /return $employees;

         return view('employee.list',['employees'=>$employees]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $max_id = DB::table('users')->max('id');

        $emp_no = 'Emp' . str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);

        return view('employee.add',['emp_no'=>$emp_no]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contact_no' => 'required|unique:users,contact_no',
            'email' => 'required|unique:users,email',
            'aadhar_no' => 'required|unique:users,aadhar_no',
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->contact_no = $request->contact_no;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->emp_code = $request->emp_code;
        $user->dob = $request->dob ?? null;
        $user->gender = $request->gender;
        $user->marital_status = $request->marital_status ?? null;
        $user->aadhar_no = $request->aadhar_no;
        $user->address = $request->address;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;

        if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
        $path = 'assets/images/Employee/';
        $file->move($path, $name);

        $user->profile_image = $path . $name;
    }
        $user->save();

        $lastInsertedId = $user->id;

        return redirect()->route('jobdetails', ['id' => $lastInsertedId])->with([
            'status' => 'success',
            'message' => 'Basic Details Added successfully!'
        ]);
    }

    public function jobindex($id)
    {
        $store=DB::table('stores')->get();

        $dept = DB::table('roles')
        ->where('id', '!=', 1)
        ->select('role_dept')
        ->distinct()
        ->get();

        return view('employee.jobdetail',['store'=>$store,'dept'=>$dept,'id'=>$id]);

    }

    public function getrole(Request $request)
    {

        $request->validate([
            'dept_id' => 'required',
        ]);

        $roles = DB::table('roles')
            ->where('role_dept', $request->dept_id)
            ->select('id', 'role')
            ->get();

        return response()->json($roles);
    }


    public function jobdetailstore(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->qulification = $request->qulification;
        $user->job_tittle = $request->job_tittle;
        $user->job_type = $request->job_type;
        $user->exprience = $request->exprience;
        $user->pre_start_date = $request->pre_start_date;
        $user->pro_skill = $request->pro_skill;

         if ($request->hasFile('aadhar_img')) {
                $file = $request->file('aadhar_img');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Employee/';
                $file->move($path, $name);

                $user->aadhar_img = $path . $name;
            }

        if ($request->hasFile('agreement')) {
                $file = $request->file('agreement');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Employee/';
                $file->move($path, $name);

                $user->agreement = $path . $name;
            }

        $user->dept = $request->dept;
        $user->role_id = $request->role_id;
        $user->store_id = $request->store_id;
        $user->save();

        return redirect()->route('bankdetails', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Employee Job Details Added successfully!'
        ]);
    }

    public function bankindex($id)
    {
        return view('employee.bankdetails',['id'=>$id]);

    }

    public function bankdetailstore(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->bank_name = $request->bank_name;
        $user->bank_holder_name = $request->bank_holder_name;
        $user->ac_no = $request->ac_no;
        $user->ifcs_code = $request->ifcs_code;
        $user->acount_type = $request->acount_type;
        $user->bank_branch = $request->bank_branch;
        $user->base_salary = $request->base_salary;
        $user->house_rent_allowance = $request->house_rent_allowance;
        $user->conveyance = $request->conveyance;
        $user->medical = $request->medical;
        $user->speical = $request->speical;
        $user->other = $request->other;
        $user->pro_fund = $request->pro_fund;
        $user->emp_state_insurance = $request->emp_state_insurance;
        $user->profession_tax = $request->profession_tax;
        $user->income_tax = $request->income_tax;
        $user->performance_bonus = $request->performance_bonus;
        $user->net_salary = $request->net_salary;
        $user->save();

        return redirect()->route('employee.index')->with([
            'status' => 'success',
            'message' => 'Employee Bank Details Added successfully!'
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = DB::table('users')
        ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.id',$id)
        ->select('users.id','users.profile_image','users.name','users.emp_code','users.contact_no','users.email','stores.store_name','roles.role','roles.role_dept','users.status as u_status')
        ->first();

        return view('employee.profile', ['users' => $users]);
    }

    public function empdetails($id)
    {
        $users = DB::table('users')
        ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.id',$id)
        ->select('users.*','stores.store_name','roles.role','roles.role_dept')
        ->first();

        return view('employee.empdetails',['users'=>$users]);
    }

    public function salary($id)
    {
        return view('employee.salary');

    }

    public function remarks($id)
    {
        return view('employee.remark');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee= DB::table('users')->where('id',$id)->first();

        return view('employee.edit',['employee'=>$employee]);

    }

    public function jobedit(string $id)
    {
        $employee= DB::table('users')->where('id',$id)->first();

        $store=DB::table('stores')->get();

        $dept = DB::table('roles')
        ->where('id', '!=', 1)
        ->select('role_dept')
        ->distinct()
        ->get();

        $assgin = DB::table('roles')
        ->where('id', '!=', 1)
        ->get();

        return view('employee.jobdetailsedit',['employee'=>$employee,'store'=>$store,'dept'=>$dept,'assgin'=>$assgin]);

    }

    public function bankedit(string $id)
    {
        $employee= DB::table('users')->where('id',$id)->first();

        return view('employee.bankdetailsedit',['employee'=>$employee]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $user->name =$request->name;
        $user->contact_no =$request->contact_no;
        $user->email =$request->email;
        $user->emp_code =$request->emp_code;
        $user->dob =$request->dob;
        $user->gender =$request->gender;
        $user->marital_status =$request->marital_status;
        $user->aadhar_no =$request->aadhar_no;
        $user->address =$request->address;
        $user->district =$request->district;
        $user->state =$request->state;
        $user->pincode =$request->pincode;

         if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
        $path = 'assets/images/Employee/';
        $file->move($path, $name);

        $user->profile_image = $path . $name;
    }
        $user->save();

        return redirect()->route('employee.view', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Basic Details Added successfully!'
        ]);

    }
    public function jobdetailupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->qulification = $request->qulification;
        $user->job_tittle = $request->job_tittle;
        $user->job_type = $request->job_type;
        $user->exprience = $request->exprience;
        $user->pre_start_date = $request->pre_start_date;
        $user->pro_skill = $request->pro_skill;
          if ($request->hasFile('aadhar_img')) {
                $file = $request->file('aadhar_img');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Employee/';
                $file->move($path, $name);

                $user->aadhar_img = $path . $name;
            }

        if ($request->hasFile('agreement')) {
                $file = $request->file('agreement');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Employee/';
                $file->move($path, $name);

                $user->agreement = $path . $name;
            }


        $user->dept = $request->dept;
        $user->role_id = $request->role_id;
        $user->store_id = $request->store_id;
        $user->save();

        return redirect()->route('employee.view', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Employee Job Updated Added successfully!'
        ]);
    }
    public function bankdetailupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->bank_name = $request->bank_name;
        $user->bank_holder_name = $request->bank_holder_name;
        $user->ac_no = $request->ac_no;
        $user->ifcs_code = $request->ifcs_code;
        $user->acount_type = $request->acount_type;
        $user->bank_branch = $request->bank_branch;
        $user->base_salary = $request->base_salary;
        $user->house_rent_allowance = $request->house_rent_allowance;
        $user->conveyance = $request->conveyance;
        $user->medical = $request->medical;
        $user->speical = $request->speical;
        $user->other = $request->other;
        $user->pro_fund = $request->pro_fund;
        $user->emp_state_insurance = $request->emp_state_insurance;
        $user->profession_tax = $request->profession_tax;
        $user->income_tax = $request->income_tax;
        $user->performance_bonus = $request->performance_bonus;
        $user->net_salary = $request->net_salary;
        $user->save();

        return redirect()->route('employee.view', ['id' => $id])->with([
            'status' => 'success',
            'message' => 'Employee Bank Details Added successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function emp_active(Request $req)
    {
       $up =  DB::table('users')->where('id',$req->emp_id)->update([
            'status'=>1
        ]);

        if($up){
        return back()->with(['status'=>'success','message'=>'Employee Activated Successfully']);
        }else{
        return back()->with(['status'=>'Failure','message'=>'Employee Activation Failed']);
        }
    }
}
