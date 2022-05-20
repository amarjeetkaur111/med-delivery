<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Goods;


class GoodsType extends Model
{
    use HasFactory;
    protected $table = "goodstype";
    protected $primaryKey = 'GoodsTypeId';
    public $timestamps = false; 

    public function Goods()
    {
        return $this->hasMany(Goods::class,'GoodsTypeId');
    }
    public static function boot()
    {
        parent::boot();
        // Setup event bindings...
        static::deleting(function($goodstype)
        {
          //delete related  
          if($goodstype->Goods()->delete()){
             return true;
          }          
        });
    }
}
