<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected  $table= 'm_cluster';

    public function cluster_store(){

       return $this->hasMany(Clusterstore::class,'cluster_id');
    }
}
