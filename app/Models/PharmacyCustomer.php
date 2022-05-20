<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pharmacy;
use App\Models\Customer;
use App\Models\CustomerVisit;


class PharmacyCustomer extends Model
{
    use HasFactory;
    protected $table = "pharmacycustomer";
    protected $primaryKey = 'CustomerId';
    public $timestamps = false;

    protected $fillable = [
        'CustomerId', 'PharmacyId'
    ];

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'CustomerId');
    }

    public function Pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'PharmacyId');
    }

    public function CustomerVisit()
    {
        return $this->hasOne(CustomerVisit::class,'CustomerId');
    }
}
