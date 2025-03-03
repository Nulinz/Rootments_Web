<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clusterstore extends Model
{
    use HasFactory;

    protected $table= 'cluster_store';

    public function m_cluster(){
        return $this->belongsTo(Cluster::class,'cluster_id');
    }
}
