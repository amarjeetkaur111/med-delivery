<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTable extends Model
{
    protected $table = 'activities_history';
    protected $primaryKey = 'ActivityID';
    public $timestamps = false;
    
    public function UserActivity()
    {
        return $this->belongsTo(UserModel::class,'UserID');
    }

}
