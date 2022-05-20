<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scheduler;
use App\Models\Goods;


class SchedulerRecurrence extends Model
{
    use HasFactory;
    protected $table = 'schedulerrecurrence';
    protected $primaryKey = 'SchedulerRecurrenceID';
    public $timestamps = false;
    protected $fillable = [
        'GoodsID',
    ];


    public function Scheduler()
    {
        return $this->belongsTo(Scheduler::class,'SchedulerID');
    }

    public function Goods()
    {
        return $this->belongsTo(Goods::class,'GoodsID')->select(['GoodsId','GoodsName','Cost']);
    }
}
