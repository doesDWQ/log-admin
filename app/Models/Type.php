<?php

namespace App\Models;


class Type extends BaseModel
{
    protected $guarded = [];

    //这里可以加上缓存
    public static function getTypes(){
        $types = Type::select('id','name','keys')->get();

        if(empty($types)){
            return [];
        }else{
            $types = $types->toArray();
        }

        return $types;
    }
}
