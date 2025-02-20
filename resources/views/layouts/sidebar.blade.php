<aside>
    <div class="flex-shrink-0 sidebar">
        <div class="nav col-md-11">
            <a href="./index.php" class="mx-auto">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" height="50px" class="mx-auto lightLogo">
            </a>
            <a href="./index.php" class="mx-auto">
                <img src="{{ asset('assets/images/logo_1.png') }}" alt="" height="50px"
                    class="mx-auto darkLogo" style="display: none;">
            </a>
        </div>
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
            } else {
                $route = 'mydash.dashboard';
            }

        @endphp
        <ul class="list-unstyled mt-5 ps-0">
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
            <!--            <li><a href="{{ route('dashboard') }}"-->
            <!--                    class="d-inline-flex text-decoration-none rounded mt-3">General</a>-->
            <!--            </li>-->
            <!--            <li><a href="./hr_dashboard.php" class="d-inline-flex text-decoration-none rounded">HR</a>-->
            <!--            </li>-->
            <!--            <li><a href="./operational_dashboard.php"-->
            <!--                    class="d-inline-flex text-decoration-none rounded">Operational</a>-->
            <!--            </li>-->
            <!--            <li><a href="./area_dashboard.php" class="d-inline-flex text-decoration-none rounded">Area</a>-->
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
            <!--            <li><a href="./store_dashboard.php" class="d-inline-flex text-decoration-none rounded">Store</a>-->
            <!--            </li>-->
            <!--        </ul>-->
            <!--    </div>-->
            <!--</li>-->
            <li class="mb-1">
                <a href="{{ route($route) }}">
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
                <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#collapse3"
                    aria-expanded="false">
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
                <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#collapse2"
                    aria-expanded="false">
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
                <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#collapse10"
                    aria-expanded="false">
                    <div class="btnname">
                        <i class="fa-solid fa-user"></i> &nbsp;Cluster
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
            <li class="mb-1">
                <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#collapse4"
                    aria-expanded="false">
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
            <!--<li class="mb-1">-->
            <!--    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"-->
            <!--        data-bs-target="#collapse8" aria-expanded="false">-->
            <!--        <div class="btnname">-->
            <!--            <i class="fa-solid fa-user-plus"></i> &nbsp;Recruitment-->
            <!--        </div>-->
            <!--        <div class="righticon d-flex mx-auto">-->
            <!--            <i class="fa-solid fa-angle-down toggle-icon"></i>-->
            <!--        </div>-->
            <!--    </button>-->
            <!--    <div class="collapse" id="collapse8">-->
            <!--        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">-->
            <!--            <li><a href="./list_recruit_job.php" class="d-inline-flex text-decoration-none rounded mt-3">Job-->
            <!--                    Posting</a>-->
            <!--            </li>-->
            <!--        </ul>-->
            <!--    </div>-->
            <!--</li>-->
            <li class="mb-1">
                <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                    data-bs-target="#collapse9" aria-expanded="false">
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
                        {{-- <li><a href="{{ route('repair.index') }}"
                                class="d-inline-flex text-decoration-none rounded">Repair
                                Request</a>
                        </li> --}}
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
                <a href="{{ route('approve.index') }}">
                    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"
                        data-bs-target="#collapse7" aria-expanded="false">
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

                                    $recruit_count = DB::table('recruitments')
                                        ->where('request_status', 'Pending')
                                        ->where('created_by', $user->id)
                                        ->count();

                                    // Ensure $repair_count is defined
                                    $repair_count = 0; // You might need to fetch this from a table

                                    // Calculate total count
                                    $total_count =
                                        $leave_count + $repair_count + $transfer_count + $resign_count + $recruit_count;
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

                                    $recruit_count = DB::table('recruitments')
                                        ->where('esculate_status', 'Pending')
                                        ->where('created_by', $user->id)
                                        ->count();

                                    // Calculate total count
                                    $total_count = $leave_count + $transfer_count + $resign_count + $recruit_count;
                                }
                            }
                        @endphp




                        <!--<div class="righticon d-flex mx-auto approvalno">-->
                        <!--    <h6 class="mb-0">{{ $total_count }}</h6>-->
                        <!--</div>-->

                    </button>
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('logout') }}">
                    <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                        <div class="btnname">
                            <i class="fa-solid fa-right-to-bracket" style="color: green;"></i> &nbsp;CheckIn
                        </div>
                    </button>
                </a>
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
                <!--<li class="mb-1">-->
                <!--    <button class="btn0 mx-auto btn-toggle collapsed" data-bs-toggle="collapse"-->
                <!--        data-bs-target="#collapse8" aria-expanded="false">-->
                <!--        <div class="btnname">-->
                <!--            <i class="fa-solid fa-user-plus"></i> &nbsp;Recruitment-->
                <!--        </div>-->
                <!--        <div class="righticon d-flex mx-auto">-->
                <!--            <i class="fa-solid fa-angle-down toggle-icon"></i>-->
                <!--        </div>-->
                <!--    </button>-->
                <!--    <div class="collapse" id="collapse8">-->
                <!--        <ul class="btn-toggle-nav list-unstyled text-start ps-5 pe-0 pb-3">-->
                <!--            <li><a href="./list_recruit_job.php"-->
                <!--                    class="d-inline-flex text-decoration-none rounded mt-3">Job-->
                <!--                    Posting</a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--</li>-->
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
                    <a href="{{ route('logout') }}">
                        <button class="btn0 mx-auto btn-toggle collapsed" aria-expanded="false">
                            <div class="btnname">
                                <i class="fa-solid fa-right-to-bracket" style="color: green;"></i> &nbsp;CheckIn
                            </div>
                        </button>
                    </a>
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
