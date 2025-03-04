<?php

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
                        'task' => [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,37,41],
                        'recruitment' => [3,4,5],
                        'payroll' => [3,4,5],
                        'attendance' => [3,4,5],
                        'request' => [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,37,41],
                        'approval' => [3,4,5,12,7,30,37,41],
                        'recruit_req' => [3,4,5,6,7,8,9,10,11,12],
                        'cat/sub'=>[3,4,5],
                        'leave'=>[12,7,30,37,41]

                        ];
        return in_array($role, $menuItems[$menuItem]);
    }
}
