<div class="container-fluid px-0 header">
    <div class="container px-0 mt-2 tabbtns">
        @php
        $user = auth()->user();

        // Get user role details
        $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $user->id)
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->first();
        $r_id = $role_get->role_id;
        if ($r_id == 3 || $r_id == 4 || $r_id == 5) {
            $route = 'hr.dashboard';
        } elseif ($r_id == 12) {
            $route = 'dashboard';
        } elseif($r_id==11){
                    $route = 'cluster.dashboard';
        }else {
            $route = 'mydash.dashboard';
        }

    @endphp
        @if($r_id == 3 || $r_id == 4 || $r_id == 5 || $r_id==12 || $r_id==11)
        <div class="my-2">
            <a href="{{ route($route) }}"><button class="dashtabs">Overview</button></a>
        </div>
        @endif
        @if($r_id == 3 || $r_id == 4 || $r_id == 5)
        <div class="my-2">
            <a href="{{ route('hrkpi.dashboard') }}"><button class="dashtabs ">KPI
                    Dashboard</button></a>
        </div>
        @endif
        @if($r_id==12)
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
        <div class="my-2">
            <a href="{{ route('mydash.dashboard') }}"><button class="dashtabs ">My
                    Dashboard</button></a>
        </div>
    </div>
</div>


<link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">
