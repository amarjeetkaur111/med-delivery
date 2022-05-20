<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scheduler;
use App\Models\Customer;
use App\Models\VisitStatus;
use App\Models\VisitBatch;
use App\Models\UserModel;
use App\Models\PharmacyCustomer;


class CustomerVisit extends Model
{
    use HasFactory;
    protected $table = 'customervisit';
    protected $primaryKey = 'CustomerVisitID';
    public $timestamps = false;

    public function Scheduler()
    {
        return $this->belongsTo(Scheduler::class,'SchedulerID');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'CustomerID');
    }

    public function PharmacyUser()
    {
        return $this->belongsTo(PharmacyCustomer::class,'CustomerID');
    }

    public function Status()
    {
        return $this->hasMany(VisitStatus::class,'CustomerVisitID');
    }

    public function VisitBatch()
    {
        return $this->hasOne(VisitBatch::class,'CustomerVisitID');
    }

    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($customervisit)
        {
                //delete related
          $customervisit->Status()->delete();
          $customervisit->VisitBatch()->delete();
        });
    }
}
