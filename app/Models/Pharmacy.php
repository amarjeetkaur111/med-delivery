<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PharmacyUser;


class Pharmacy extends Model
{
    use HasFactory;
    protected $table = 'pharmacies';
    protected $primaryKey = 'PharmacyId';
    public $timestamps = false;
    
    public function PharmacyCustomer()
    {
        return $this->hasMany(PharmacyCustomer::class,'PharmacyId');
    }

    public function PharmacyUsers()
    {
        return $this->hasMany(PharmacyUser::class,'PharmacyId');
    }
    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($pharmacy)
        {
          //delete related  
          if($pharmacy->PharmacyUsers()->delete()){
             return true;
          }          
        });
    }
}
