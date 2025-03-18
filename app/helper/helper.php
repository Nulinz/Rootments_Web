<?php
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

if (!function_exists('hasAccess')) {
    /**
     * Check if the user has access to a specific menu item.
     *
     * @param string $role
     * @param string $menuItem
     * @return bool
    */
    function hasAccess($role, $menuItem)
    {
           $menuItems = [

                        'store' => [3,4,5,12], // Roles allowed for HR section
                        'employee' => [3,4,5,12,7,30,37,41], // Roles allowed for CRM section
                        'area' => [3,4,5,10], // Roles allowed for CRM section
                        'cluster' => [3,4,5,11], // Roles allowed for Task section
                        'all_task' => [3,4,5,6,7,8,9,10,11,12,30,37,41],
                        'task' => [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44],
                        'recruitment' => [3,4,5],
                        'payroll' => [3,4,5],
                        'attendance' => [3,4,5],
                        'request' => [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44],
                        'approval' => [3,4,5,12,7,30,37,41],
                        'recruit_req' => [3,4,5,6,7,8,9,10,11,12],
                        'cat/sub'=>[3,4,5],
                        'leave'=>[10,11,12,7,30,37,41],
                        'mob_task'=>[1,2,3,4,5,6,7,8,9,10,11,12,13,30,37,41],
                        'resign'=>[10,11,12,7,30,37,41],

                        ];
        return in_array($role, $menuItems[$menuItem]);
    }
}


function enc($par) {
    return Crypt::encrypt($par);
}

function dec($par) {
    return Crypt::decrypt($par);
}
