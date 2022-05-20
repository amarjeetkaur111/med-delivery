<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;

    protected $table = "customers";
    protected $primaryKey = 'CustomerId';
    public $timestamps = false;
    protected $fillable = [
        'FirstName','LastName','Status'
    ];

    public function PharmacyCustomer()
    {
        return $this->hasOne(PharmacyCustomer::class,'CustomerId');
    }

    public function CustomersAddress()
    {
        return $this->hasMany(CustomersAddress::class,'CustomerId');
    }

    public function CustomersAddressPrimary()
    {
        $CustomerPrimaryAdd = $this->CustomersAddress()
            ->select('Longitude','Latitude','AddressLine','UnitNumber','City','Province','PostalCode','Country','PhoneTypeId','PhoneNumber','Extension','AdditionalInfo','DoorSecurityCode')
            ->where('SetAsPrimary','=', 1)->first();


        $CustomerPrimaryAdd['PhoneTypeId'] = GetPhoteType($CustomerPrimaryAdd['PhoneTypeId']);
       // $CustomerPrimaryAdd['PhoneType'] = GetPhoteType($CustomerPrimaryAdd['PhoneTypeId']);
       // unset($CustomerPrimaryAdd['PhoneTypeId']);

        return $CustomerPrimaryAdd;
    }

     public function CustomerVisit()
    {
        return $this->hasMany(CustomerVisit::class,'CustomerID');
    }

    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($customer)
        {
          //delete related
          if($customer->CustomersAddress()->delete()){
             return true;
          }
        });
    }
}


