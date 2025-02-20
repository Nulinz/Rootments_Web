<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\{User,Role,};
use Carbon\Carbon;

class ClusterController extends Controller
{
    public function index()
    {

        $cluster = DB::table('m_cluster as mc')
        ->leftjoin('cluster_store as cs','cs.cluster_id','=','mc.id')
        ->leftJoin('users', function($join) {
            $join->on('users.id', '=', 'mc.cl_name'); // Join on store_id and store_ref_id
        })
        ->select('mc.id','mc.cl_name','mc.location',DB::raw('COUNT(cs.cluster_id) as cl_count'),'users.contact_no','users.email','users.name')
        ->groupBy('mc.id')
        ->get();
        // return $cluster;
          return view('cluster.list',['cluster'=>$cluster]);
    }

    public function cluster_overview(Request $req) 
    {
        return view ('cluster.overview');
    }

    public function cluster_strength(Request $req) 
    {
        return view ('cluster.strength');
    }

    public function create(Request $req)
    {
           $create =DB::table('m_cluster')->insertGetId([
                'cl_name'=>$req->clustername,
                'alter'=>$req->altcontact,
                'location'=>$req->storeloc,
                'created_at' => now(),  // Don't manually include these!
                'updated_at' => now()   // Don't manually include these!

           ]);

            foreach($req->store as $st_id){

               $create_list =  DB::table('cluster_store')->insert([
                        'cluster_id'=>$create,
                        'store_id'=>$st_id,
                        'created_at' => now(),  // Don't manually include these!
                        'updated_at' => now()   // Don't manually include these!
                ]);
                }

            if($create && $create_list){
                return redirect()->route('cluster.index')->with([
                    'status' => 'success',
                    'message' => 'Cluster Added successfully!'
                ]);
            }else{

                return redirect()->route('cluster.index')->with([
                    'status' => 'Failure',
                    'message' => 'Cluster Failed to Add!'
                ]);


            }

    }

    public function drop_show()
    {
        // $cluster = DB::table('users')
        // ->leftjoin('m_cluster','users.id','!=','m_cluster.cl_name')
        // ->where('role_id','11')->where('status','1')->select('id','name')->get();

        $cluster = DB::table('users')
        ->leftJoin('m_cluster', 'users.id', '=', 'm_cluster.cl_name')  // Correct LEFT JOIN condition
        ->where('users.role_id', 11)  // Filter users with role_id = 11
        ->where('users.status', 1)  // Filter users with status = 1
        ->whereNull('m_cluster.cl_name')  // Exclude users whose ID is in the cl_name column
        ->select('users.id', 'users.name')
        ->get();



       // return $store;

          return view('cluster.add',['cluster'=>$cluster]);
    }

     public function cluster_det(Request $req)
    {
        $data = DB::table('users')->where('id',$req->cluster_per)->select('contact_no','email','address','district','state','pincode')->first();

        // $store = DB::table('stores')->where('status','1')->select('*')->get();

        $store = DB::table('store_lists')
        ->leftJoin('stores', 'store_lists.store_ref_id', '=', 'stores.id') // LEFT JOIN with the stores table
        ->leftJoin('users', function($join) {
            $join->on('users.store_id', '=', 'store_lists.store_ref_id') // Join on store_id and store_ref_id
                 ->where('users.role_id', 12); // Additional condition for users' role_id = 12
        })
        ->where('store_lists.role_id', 12) // Filter store_lists by role_id = 12
        ->select(
            'store_lists.id',
            'store_lists.store_ref_id',
            'stores.store_code',
            'stores.store_name',
            'users.name as user_name', // Select the user name from the users table
            'stores.store_geo',
            'stores.store_contact'
        )
        ->get(); // Execute the query and get the result

        $new_obg = [];

        foreach($store as $st){

            $count = DB::table('cluster_store')->where('store_id',$st->store_ref_id)->count();

            if($count>0){
                continue;
            }else{
                $new_obg[] = $st;
            }
        }


        $arr = [
            'data'=>$data,
            'store'=>$new_obg,
        ];

         return response()->json($arr, 200);
        // return view('');
    }

    public function show(string $id)
    {
        $cluster = DB::table('m_cluster as mc')
        ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
        ->where('mc.id', $id) // Filter by the cluster id
        ->select('user.name', 'user.contact_no','user.email','mc.alter','user.address','user.pincode','mc.location','user.profile_image') // Select the required fields from users table
        ->first(); // Get the first matching result


        $cluster_list= DB::table('cluster_store as cs')
        ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
        ->where('cluster_id',$id)->get();

        foreach($cluster_list as $cs){

        $st_man = DB::table('users')->where('role_id',12)->where('store_id',$cs->store_id)->select('users.name')->first();

        $cs->st_name = $st_man ? $st_man->name : null;

        }



        //  return $cluster_list;
           return view('cluster.profile',['mc'=>$cluster,'clust_store'=> $cluster_list]);
    }
     public function edit()
    {
        return view('cluster.edit');
    }


     public function update()
    {
        return view('');
    }
     public function delete()
    {
        return view('');
    }
}
