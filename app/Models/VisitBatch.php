<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Batch;
use App\Models\CustomerVisit;


class VisitBatch extends Model
{
    use HasFactory;
    protected $table = 'visitbatch';
    public $timestamps = false;
    
    public function Batch()
    {
        return $this->belongsTo(Batch::class,'BatchID');
    }

    public function CustomerVisit()
    {
        return $this->belongsTo(CustomerVisit::class,'CustomerVisitID');
    }
}
