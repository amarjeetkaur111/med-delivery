<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchedulerCustomer;
use App\Models\SchedulerRecurrence;
use App\Models\CustomerVisit;

class Scheduler extends Model
{
    use HasFactory;
    protected $table = 'scheduler';
    protected $primaryKey = 'SchedulerID';
    public $timestamps = false;


    public function SchedulerRecurrence()
    {
        return $this->hasMany(SchedulerRecurrence::class,'SchedulerID')
            ->select(['SchedulerID','GoodsID','RecurrenceTypeID','RecurrenceSelectedDays']);
    }

    public function SchedulerCustomer()
    {
        return $this->hasOne(SchedulerCustomer::class,'SchedulerID');
    }

    public function Visit()
    {
        return $this->hasMany(CustomerVisit::class,'SchedulerID');
    }

    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($scheduler)
        {
          //delete related
          $scheduler->SchedulerRecurrence()->delete();
          $scheduler->SchedulerCustomer()->delete();
        });
    }
}
