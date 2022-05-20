<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VisitBatch;
use App\Models\BatchAssign;


class Batch extends Model
{
    use HasFactory;

    protected $table = "batch";
    protected $primaryKey = 'BatchID';
    public $timestamps = false;
    protected $fillable = [
            'TrackingID',
        ];

    public function VisitBatch()
    {
        return $this->hasMany(VisitBatch::class,'BatchID');
    }

    public function Assigned()
    {
        return $this->hasOne(BatchAssign::class,'BatchID');
    }

    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($batch)
        {
          //delete related
          if($batch->VisitBatch()->delete() || $batch->Assigned()->delete())
            return true;
        });
    }
}
