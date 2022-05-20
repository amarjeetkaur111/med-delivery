<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PharmacyUser;
use App\Models\BatchAssign;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory ;

    protected $table = 'users';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    public function PharmacyUsers()
    {
        return $this->hasOne(PharmacyUser::class,'UserId');
    }

    public function BatchAssigned()
    {
        return $this->hasMany(BatchAssign::class,'DriverID');
    }

    public function Activity()
    {
        return $this->hasMany(ActivityTable::class,'UserID');
    }

    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($user)
        {
          //delete related
          if($user->PharmacyUsers()->delete()){
             return true;
          }
          // return false;
        });
    }

}
