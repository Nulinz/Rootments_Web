<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // require_once app_path('helper/helper.php');


         // Get the current Git branch
         $branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

         // Default DB name
         $dbName = 'rootments';

         // Set the DB name based on the current branch
         if ($branch === 'main') {
             $dbName = 'rootments';
         } elseif ($branch === 'error') {
             $dbName = 'rootments_error';
         } else {
             // For any other branch, append the branch name to the DB name
             $dbName = 'rootments_' . $branch;
         }

         // Dynamically update the DB_DATABASE config
         Config::set('database.connections.mysql.database', $dbName);
    }
}
