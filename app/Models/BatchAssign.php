<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Batch;
use App\Models\UserModel;

class BatchAssign extends Model
{
    use HasFactory;
    protected $table = 'batchassign';
    protected $primaryKey = 'BatchAssignID';
    public $timestamps = false;
    
    public function Batch()
    {
        return $this->belongsTo(Batch::class,'BatchID');
    }

    public function Driver()
    {
        return $this->belongsTo(UserModel::class,'DriverID');
    }
}
