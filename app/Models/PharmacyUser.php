<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pharmacy;
use App\Models\UserModel;


class PharmacyUser extends Model
{
    use HasFactory;
    protected $table = "pharmacyusers";
    protected $primaryKey = 'UserId';

    public $timestamps = false;
    protected $fillable = ['PharmacyId'];


    public function User()
    {
        return $this->belongsTo(UserModel::class,'UserId');        
    }
    public function Pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'PharmacyId');        
    }
}
