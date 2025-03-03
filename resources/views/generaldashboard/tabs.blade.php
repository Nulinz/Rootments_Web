<div class="container-fluid px-0 header">
    <div class="container px-0 mt-2 tabbtns">
        @php
        $user = auth()->user();

        $asm_count = DB::table('asm_store')->where('store_id',$user->store_id)->where('emp_id',$user->id)->count();

        // Get user role details
        $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $user->id)
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->first();
        $r_id = $role_get->role_id;
        if ($r_id == 3 || $r_id == 4 || $r_id == 5) {
            $route = 'hr.dashboard';
            $over = 'HR';
        } elseif (($r_id == 12) || ($asm_count>0)) {
            $route = 'dashboard';
            $over = 'Store';
        } elseif($r_id==11){
                    $route = 'cluster.dashboard';
                    $over = 'Cluster';
        }elseif($r_id==10){
                    $route = 'area.dashboard';
                    $over = 'Area';
        }
        else {
            $route = 'mydash.dashboard';
        }

            $cluster_check = DB::table('m_cluster')
            ->where('cl_name','=',$user->id)
            ->count();

    @endphp
        @if($r_id == 3 || $r_id == 4 || $r_id == 5 || $r_id==12 || $r_id==11 || $r_id==10 || ($asm_count>0))
        <div class="my-2">
            <a href="{{ route($route) }}"><button class="dashtabs">{{$over}} Overview</button></a>
        </div>
        @endif
        @if($r_id == 3 || $r_id == 4 || $r_id == 5)
        <div class="my-2">
            <a href="{{ route('hrkpi.dashboard') }}"><button class="dashtabs ">HR KPI
                    Dashboard</button></a>
        </div>
        @endif
        @if($r_id==10)
        <div class="my-2">
            <a href="{{ route('area.kpidashboard') }}"><button class="dashtabs ">Area KPI
                    Dashboard</button></a>
        </div>
        @endif
        @if(($r_id==12)||($asm_count>0))
        <div class="my-2">
            <a href="{{ route('store.dashboard') }}"><button class="dashtabs ">Store
                    Dashboard</button></a>
        </div>
        @endif
        @if($r_id==11)
        <div class="my-2">
            <a href="{{ route('cluster.strength') }}"><button class="dashtabs">Store Strength</button></a>
        </div>
        @endif

      {{-- checking the store manager as cluster manger  --}}

      @if(($r_id==12)&&($cluster_check>0))
        <div class="my-2">
            <a href="{{ route('cluster.dashboard') }}"><button class="dashtabs">Cluster Overview</button></a>
        </div>
      <div class="my-2">
          <a href="{{ route('cluster.strength') }}"><button class="dashtabs">Store Strength</button></a>
      </div>
      @endif

        <div class="my-2">
            <a href="{{ route('mydash.dashboard') }}"><button class="dashtabs">My Dashboard</button></a>
        </div>
    </div>
</div>


<link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">
