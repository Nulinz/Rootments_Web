const currentUrl = window.location.href;

const lastPart = new URL(currentUrl).pathname.split('/').pop();
// console.log(lastPart);
const classMap = {
    'dashboard.php': 'bn1',
    'dashboard_hr.php': 'bn1',
    'dashboard_operational.php': 'bn1',
    'dashboard_area.php': 'bn1',
    'dashboard_cluster.php': 'bn1',
    'dashboard_repair.php': 'bn1',
    'dashboard_store.php': 'bn1',
    'list_employee.php': 'bn2',
    'add_employee.php': 'bn2',
    'view_employee.php': 'bn2',
    'edit_employee.php': 'bn2',
    'list_project.php': 'bn3',
    'add_project.php': 'bn3',
    'view_project.php': 'bn3',
    'edit_project.php': 'bn3',
    'list_labour.php': 'bn4',
    'add_labour.php': 'bn4',
    'edit_labour.php': 'bn4',
    'list_contractor.php': 'bn5',
    'add_contractor.php': 'bn5',
    'view_contractor.php': 'bn5',
    'edit_contractor.php': 'bn5',
    'list_attendance.php': 'bn6',
    'list_report.php': 'bn7',
    'add_report.php': 'bn7',
    'edit_report.php': 'bn7',
    'view_report.php': 'bn7'
};

if (classMap[lastPart]) {
    $(`.${classMap[lastPart]}`).addClass("active");
}
