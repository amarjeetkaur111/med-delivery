<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerVisit;
use App\Models\Goods;


class VisitStatus extends Model
{
    use HasFactory;
    protected $table = 'visitstatus';
    protected $primaryKey = 'VisitID';
    public $timestamps = false;

    public function CustomerVisit()
    {
        return $this->belongsTo(CustomerVisit::class,'CustomerVisitID');
    }

    public function Goods()
    {
        return $this->belongsTo(Goods::class,'GoodsID')->select(['GoodsId','GoodsName']);
    }

}
