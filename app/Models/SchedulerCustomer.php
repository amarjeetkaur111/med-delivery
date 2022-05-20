<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scheduler;
use App\Models\Customer;


class SchedulerCustomer extends Model
{
    use HasFactory;
    protected $table = 'schedulercustomer';
    protected $primaryKey = 'SchedulerCustomerID';
    public $timestamps = false;


    public function Scheduler()
    {
        return $this->belongsTo(Scheduler::class,'SchedulerID');
    }

     public function Customer()
    {
        return $this->belongsTo(Customer::class,'CustomerID');
    }
}
