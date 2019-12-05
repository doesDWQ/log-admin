<?php

namespace App\Models;

class NovelChapter extends BaseModel
{
    protected $guarded = [];
    protected $table = 'novels_chapter';

    //关联小说名
    public function novel(){
        return $this->hasOne(Novel::class,'id','novel_id')->select('title','id');
    }
}
