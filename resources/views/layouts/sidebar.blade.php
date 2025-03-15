<aside>
    <div class="flex-shrink-0 sidebar">
        <div class="nav col-md-11">
            <a href="./index.php" class="mx-auto">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" height="50px" class="mx-auto lightLogo">
            </a>
            <a href="./index.php" class="mx-auto">
                <img src="{{ asset('assets/images/logo_1.png') }}" alt="" height="50px" class="mx-auto darkLogo"
                    style="display: none;">
            </a>
        </div>
        @php

            // if(session('role_id')) {
            //                     // Update the role_id of the authenticated user
            //     $user = Auth::user();
            //     $user->role_id = session('role_id');  // Update role_id from the session
            //     Auth::setUser($user);  // Re-set the user to reflect the new role_id
            //     // dd(Auth::user());
            // }
            $user = auth()->user();
            //  dd($user->role_id, session('role_id'), Auth::user());
            // dd($user);
            // echo $user->role_id ;
            // Get user role details
            $role_get = DB::table('roles')
                ->join('users', 'users.role_id', '=', 'roles.id')
                ->where('users.id', $user->id)
                ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
                ->first();
            $r_id = $user->role_id;
            // if ($r_id == 3 || $r_id == 4 || $r_id == 5) {
            //     $route = 'hr.dashboard';
            // } elseif ($r_id == 12) {
            //     $route = 'dashboard';
            // } elseif($r_id==11){
            //             $route = 'cluster.dashboard';
            // }else {
            //     $route = 'mydash.dashboard';
            // }
            $route = [
                3 => ['route' => 'hr.dashboard', 'over' => 'HR'],
                4 => ['route' => 'hr.dashboard', 'over' => 'HR'],
                5 => ['route' => 'hr.dashboard', 'over' => 'HR'],
                12 => ['route' => 'dashboard', 'over' => 'Store'],
                11 => ['route' => 'cluster.dashboard', 'over' => 'Cluster'],
                10 => ['route' => 'area.dashboard', 'over' => 'Area'],
                7 => ['route' => 'fin.index', 'over' => 'Finance'],
                30 => ['route' => 'maintain.index', 'over' => 'Maintain'],
                37 => ['route' => 'warehouse.index', 'over' => 'Warehouse'],
                41 => ['route' => 'purchase.index', 'over' => 'Purchase'],
            ];

        @endphp

        <ul class="list-unstyled mt-5 ps-0">
            <li class="mb-1">
                <a href="{{ route($route[$r_id]['route'] ?? 'mydash.dashboard') }}">
                    <button
                        class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('dashboard.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false">
                        <div class="btnname">
                            <i class="bx bxs-dashboard"></i> &nbsp;Dashboard
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <!--<i class="fa-solid fa-angle-right"></i>-->
                        </div>
                    </button>
                </a>
            </li>
            @if(hasAccess($r_id, 'store'))

                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('store.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-shop"></i> &nbsp;Store
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse3">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('store.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Store
                                    List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'employee'))

                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('employee.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-user"></i> &nbsp;Employee
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse2">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('employee.index',['status'=>1]) }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Employee List</a>
                            </li>
                            <li><a href="{{ route('employee.index',['status'=>2]) }}"
                                    class="d-inline-flex text-decoration-none rounded">Inactive List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'cluster'))
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('cluster.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false">
                        <div class="btnname">
                            <i class="fas fa-users-gear"></i> &nbsp;Cluster
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse10">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('cluster.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Cluster List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'area'))
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('area.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-chart-area"></i> &nbsp;Area
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse11">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('area.list') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Area
                                    List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'task'))
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('task.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-list-check"></i> &nbsp;Task
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse mt-3" id="collapse4">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            @if(hasAccess($r_id, 'all_task'))
                                <li><a href="{{ route('task.index') }}" class="d-inline-flex text-decoration-none rounded">Task
                                        List</a>
                                </li>
                            @endif
                            <li><a href="{{ route('task.completed-task') }}"
                                    class="d-inline-flex text-decoration-none rounded">Completed Task
                                    List</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'payroll'))
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('recruit.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-user-plus"></i> &nbsp;Recruitment
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse8">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('recruit.list') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Job
                                    Posting</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'payroll'))
            <li class="mb-1">
                <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('resign.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" data-bs-target="#collapse12" aria-expanded="false">
                    <div class="btnname">
                        <i class="fa-solid fa-user-xmark"></i> &nbsp;Resignation
                    </div>
                    <div class="righticon d-flex mx-auto">
                        <i class="fa-solid fa-angle-down toggle-icon"></i>
                    </div>
                </button>
                <div class="collapse" id="collapse12">
                    <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                        <li><a href="{{ route('resign.list') }}"
                                class="d-inline-flex text-decoration-none rounded mt-3">Resignation List</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            @if(hasAccess($r_id, 'payroll'))
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('payroll.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-credit-card"></i> &nbsp;Payroll
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse9">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('payroll.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Salary Generation
                                    List</a>
                            </li>
                            <li><a href="{{ route('payroll.payroll_list') }}"
                                    class="d-inline-flex text-decoration-none rounded">View Salary
                                    List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'attendance'))
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('attendance.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-clipboard-user"></i> &nbsp;Attendance
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse5">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('attendance.daily') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Daily</a>
                            </li>
                            <li><a href="{{ route('attendance.monthly') }}"
                                    class="d-inline-flex text-decoration-none rounded">Monthly</a>
                            </li>
                            <li><a href="{{ route('attendance.individual') }}"
                                    class="d-inline-flex text-decoration-none rounded">Individual</a>
                            </li>
                            <li><a href="{{ route('attendance.overtime') }}"
                                    class="d-inline-flex text-decoration-none rounded">Overtime / Late</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'request'))
                <li class="mb-1">
                    <button
                        class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('leave.*', 'repair.*', 'transfer.*', 'resignation.*', 'recruitment.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-comment-dots"></i> &nbsp;Request
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse6">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('leave.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Leave
                                    Request</a>
                            </li>
                            {{-- <li><a href="{{ route('repair.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Repair
                                    Request</a>
                            </li> --}}
                            {{-- <li><a href="{{ route('transfer.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Transfer
                                    Request</a>
                            </li> --}}
                            <li><a href="{{ route('resignation.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Resignation
                                    Request</a>
                            </li>
                            @if(hasAccess($r_id, 'all_task'))
                                <li><a href="{{ route('recruitment.index') }}"
                                        class="d-inline-flex text-decoration-none rounded">Recruitment
                                        Request</a>
                            @endif
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(hasAccess($r_id, 'approval'))
                        <li class="mb-1">
                            <a href="{{ route('approve.index') }}">
                                <button
                                    class="btn0 mx-auto btn-toggle collapsed {{ Request::routeIs('approve.*') ? 'active' : '' }}"
                                    data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false">
                                    <div class="btnname">
                                        <i class="fa-solid fa-clipboard-check"></i> &nbsp;Approval List
                                    </div>
                                    @php

                                        // Initialize total count
                                        $total_count = 0;

                                        if ($role_get) {
                                            if ($role_get->role_id == 12) {
                                                // Count requests where request_status = 'Pending' and created_by = user
                                                $leave_count = DB::table('leaves')
                                                    ->where('request_status', 'Pending')
                                                    ->where('created_by', $user->id)
                                                    ->count();

                                                $transfer_count = DB::table('transfers')
                                                    ->where('request_status', 'Pending')
                                                    ->where('created_by', $user->id)
                                                    ->count();

                                                $resign_count = DB::table('resignations')
                                                    ->where('request_status', 'Pending')
                                                    ->where('created_by', $user->id)
                                                    ->count();

                                                // $recruit_count = DB::table('recruitments')
                                                //     ->where('request_status', 'Pending')
                                                //     ->where('created_by', $user->id)
                                                //     ->count();

                                                // Ensure $repair_count is defined
                                                $repair_count = 0; // You might need to fetch this from a table

                                                // Calculate total count
                                                $total_count =
                                                    $leave_count + $repair_count + $transfer_count + $resign_count + ($recruit_count ?? 0);
                                            } elseif ($role_get->role_id == 3) {
                                                // Count requests where escalate_status = 'Pending' and created_by = user
                                                $leave_count = DB::table('leaves')
                                                    ->where('esculate_status', 'Pending')
                                                    ->where('created_by', $user->id)
                                                    ->count();

                                                $transfer_count = DB::table('transfers')
                                                    ->where('esculate_status', 'Pending')
                                                    ->where('created_by', $user->id)
                                                    ->count();

                                                $resign_count = DB::table('resignations')
                                                    ->where('esculate_status', 'Pending')
                                                    ->where('created_by', $user->id)
                                                    ->count();

                                                // $recruit_count = DB::table('recruitments')
                                                //     ->where('esculate_status', 'Pending')
                                                //     ->where('created_by', $user->id)
                                                //     ->count();

                                                // Calculate total count
                                                $total_count = $leave_count + $transfer_count + $resign_count + ($recruit_count ?? 0);
                                            }
                                        }
                                    @endphp
                                    <!--<div class="righticon d-flex mx-auto approvalno">-->
                                    <!--    <h6 class="mb-0">{{ $total_count }}</h6>-->
                                    <!--</div>-->
                                </button>
                            </a>
                        </li>
            @endif
            <li class="mb-3">
                @php
                    $user_check = Auth::user()->id;
                    $attd = DB::table('attendance')->where('user_id', $user_check)->whereDate('c_on', date('Y-m-d'))->count();
                @endphp

                @if($attd == 0)
                    <a onclick="getLocation()">
                        <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-right-to-bracket" style="color: green;"></i> &nbsp;CheckIn
                            </div>
                        </button>
                    </a>
                @else
                    <a onclick="getLocation()">
                        <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-right-to-bracket" style="color: red;"></i> &nbsp;CheckOut
                            </div>
                        </button>
                    </a>
                @endif
            </li>

        </ul>

        <ul class="list-unstyled lgt">
            <li class="mb-1">
                <a href="{{ route('logout') }}">
                    <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-right-from-bracket" style="color: red;"></i> &nbsp;Logout
                        </div>
                    </button>
                </a>
            </li>
        </ul>
    </div>
</aside>

<?php
/*
<!-- Responsive Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <div class="user ps-1">
            <img src="{{ asset('assets/images/avatar.png') }}" height="43px" class="rounded-5" alt="">
            <h6 class="px-3 py-1 m-0">
                <span class="username">{{ Auth::user()->name }}</span>
                <br>
                @php
                    $auth_id = Auth::user()->id;
                    $role = DB::table('users')
                        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                        ->where('users.id', $auth_id)
                        ->select('roles.role')
                        ->first();
                @endphp
                <span class="userrole">{{ $role->role ?? 'No role assigned' }}</span>
            </h6>
        </div>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="flex-shrink-0 sidebar">
            <ul class="list-unstyled mt-2 ps-0">
                <!--<li class="mb-1">-->
                <!--    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"-->
                <!--        data-bs-target="#collapse1" aria-expanded="false">-->
                <!--        <div class="btnname">-->
                <!--            <i class="bx bxs-dashboard"></i> &nbsp;Dashboard-->
                <!--        </div>-->
                <!--        <div class="righticon d-flex mx-auto">-->
                <!--            <i class="fa-solid fa-angle-down toggle-icon"></i>-->
                <!--        </div>-->
                <!--    </button>-->
                <!--    <div class="collapse" id="collapse1">-->
                <!--        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">-->
                <!--            <li><a href="./general_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded mt-3">General</a>-->
                <!--            </li>-->
                <!--            <li><a href="./hr_dashboard.php" class="d-inline-flex text-decoration-none rounded">HR</a>-->
                <!--            </li>-->
                <!--            <li><a href="./operational_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded">Operational</a>-->
                <!--            </li>-->
                <!--            <li><a href="./area_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded">Area</a>-->
                <!--            </li>-->
                <!--            <li><a href="./cluster_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded">Cluster</a>-->
                <!--            </li>-->
                <!--            <li><a href="./repair_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded">Repair</a>-->
                <!--            </li>-->
                <!--            <li><a href="./warehouse_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded">Warehouse</a>-->
                <!--            </li>-->
                <!--            <li><a href="./store_dashboard.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded">Store</a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--</li>-->
                <li class="mb-1">
                    <a href="{{ route('dashboard') }}">
                        <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                            data-bs-target="#collapse1" aria-expanded="false">
                            <div class="btnname">
                                <i class="bx bxs-dashboard"></i> &nbsp;Dashboard
                            </div>
                            <div class="righticon d-flex mx-auto">
                                <!--<i class="fa-solid fa-angle-right"></i>-->
                            </div>
                        </button>
                    </a>
                </li>
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse3" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-shop"></i> &nbsp;Store
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse3">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('store.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Store
                                    List</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse2" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-user"></i> &nbsp;Employee
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse2">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('employee.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Employee List</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse8" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-user-plus"></i> &nbsp;Recruitment
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse8">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('recruit.list') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Job
                                    Posting</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--<li class="mb-1">-->
                <!--    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"-->
                <!--        data-bs-target="#collapse9" aria-expanded="false">-->
                <!--        <div class="btnname">-->
                <!--            <i class="fa-solid fa-credit-card"></i> &nbsp;Payroll-->
                <!--        </div>-->
                <!--        <div class="righticon d-flex mx-auto">-->
                <!--            <i class="fa-solid fa-angle-down toggle-icon"></i>-->
                <!--        </div>-->
                <!--    </button>-->
                <!--    <div class="collapse" id="collapse9">-->
                <!--        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">-->
                <!--            <li><a href="./list_pay_salary.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded mt-3">Salary Disbursement</a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--</li>-->
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse4" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-list-check"></i> &nbsp;Task
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse4">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('task.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Task
                                    List</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse5" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-clipboard-user"></i> &nbsp;Attendance
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse5">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('attendance.daily') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Daily</a>
                            </li>
                            <li><a href="{{ route('attendance.monthly') }}"
                                    class="d-inline-flex text-decoration-none rounded">Monthly</a>
                            </li>
                            <li><a href="{{ route('attendance.individual') }}"
                                    class="d-inline-flex text-decoration-none rounded">Individual</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse6" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-comment-dots"></i> &nbsp;Request
                        </div>
                        <div class="righticon d-flex mx-auto">
                            <i class="fa-solid fa-angle-down toggle-icon"></i>
                        </div>
                    </button>
                    <div class="collapse" id="collapse6">
                        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">
                            <li><a href="{{ route('leave.index') }}"
                                    class="d-inline-flex text-decoration-none rounded mt-3">Leave
                                    Request</a>
                            </li>
                            <li><a href="{{ route('repair.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Repair
                                    Request</a>
                            </li>
                            <li><a href="{{ route('transfer.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Transfer
                                    Request</a>
                            </li>
                            <li><a href="{{ route('resignation.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Resignation
                                    Request</a>
                            </li>
                            <li><a href="{{ route('recruitment.index') }}"
                                    class="d-inline-flex text-decoration-none rounded">Recruitment
                                    Request</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <a href="./list_approval.php">
                        <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                            data-bs-target="#collapse7" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-clipboard-check"></i> &nbsp;Approval List
                            </div>
                            @php
                                $user_id = Auth::user()->id;
                                $leave_count = DB::table('leaves')
                                    ->where('status', 'Pending')
                                    ->where('created_by', $user_id)
                                    ->count();
                                $repair_count = DB::table('repairs')
                                    ->where('status', 'Pending')
                                    ->where('created_by', $user_id)
                                    ->count();
                                $transfer_count = DB::table('transfers')
                                    ->where('status', 'Pending')
                                    ->where('created_by', $user_id)
                                    ->count();
                                $resign_count = DB::table('resignations')
                                    ->where('request_status', 'Pending')
                                    ->where('created_by', $user_id)
                                    ->count();
                                $recruit_count = DB::table('recruitments')
                                    ->where('status', 'Pending')
                                    ->where('created_by', $user_id)
                                    ->count();
                                $total_count =
                                    $leave_count + $repair_count + $transfer_count + $resign_count + $recruit_count;
                            @endphp
                            <div class="righticon d-flex mx-auto approvalno">
                                <h6 class="mb-0">{{ $total_count }}</h6>
                            </div>
                        </button>
                    </a>
                </li>
                <li class="mb-3">
                    {{-- <a>
                        <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-right-to-bracket" style="color: green;"
                                    onclick="getLocation()"></i> &nbsp;CheckIn
                            </div>
                        </button>
                    </a> --}}
                </li>

            </ul>

            <ul class="list-unstyled lgt">
                <li class="mb-1">
                    <a href="{{ route('logout') }}">
                        <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-right-from-bracket" style="color: red;"></i> &nbsp;Logout
                            </div>
                        </button>
                    </a>
                </li>
            </ul>

            <ul class="list-unstyled ps-2 icons">
                <li class="mb-1">
                    <a href="{{ route('settings') }}">
                        <button class="btn0 mx-auto">
                            <div class="btnname">
                                <i class="bx bx-cog"></i> &nbsp;Settings
                            </div>
                        </button>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="">
                        <button class="btn0 mx-auto">
                            <div class="btnname">
                                <i class="bx bx-bell"></i> &nbsp;Notifications
                            </div>
                        </button>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
*/
?>



<script>
    function getLocation() {


        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                // Get latitude and longitude
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // console.log(latitude,longitude);

                $.ajax({
                    url: "{{ route('get.coordinates') }}", // Make sure this matches your route
                    type: 'POST',
                    dataType: 'json', // Expecting a JSON response
                    data: {
                        latitude: latitude,
                        longitude: longitude,
                        _token: '{{ csrf_token() }}' // CSRF token for security
                    },
                    success: function (data) {
                        // Process and display the location data
                        // if (data.attd_status=='Success') {
                        //  alert('Status: ' + data.attd_status);
                        window.location.reload();


                    },
                    error: function (xhr, status, error) {
                        // Handle errors here
                        window.location.reload();
                        // console.error('Error:', error);
                        // alert('Something went wrong.');
                    }
                });
            });
        }
    }

    // getLocation();

</script>
