<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GoodsType;
use App\Models\VisitStatus;
use App\Models\SchedulerRecurrence;


class Goods extends Model
{
    use HasFactory;
    protected $table = "goods";
    protected $primaryKey = 'GoodsId';
    public $timestamps = false;

    
    public function GoodsType()
    {
        return $this->belongsTo(GoodsType::class);
    }

    public function SchedulerRecurrence()
    {
        return $this->hasMany(SchedulerRecurrence::class,'GoodsID');
    }

    public function VisitStatus()
    {
        return $this->hasMany(VisitStatus::class,'GoodsID');
    }
}
