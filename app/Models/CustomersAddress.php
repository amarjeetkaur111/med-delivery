<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;


class CustomersAddress extends Model
{
    use HasFactory;

    protected $table = "customersaddress";
    protected $primaryKey = 'CustomerAddressId';
    public $timestamps = false;


    public function Customer()
    {
        return $this->belongsTo(Customer::class,'CustomerId');
    }
}
