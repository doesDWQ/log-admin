<?php

namespace App\Models;
use App\Helper\Helper_Function;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Log extends BaseModel
{
    protected $guarded = [];

    public function paginate()
    {
        $perPage = Request::get('per_page', 10);

        $page = Request::get('page', 1);

        $log_type = request('log_type'); //type值

        $field = request('fields'); //搜索字段
        $keyword = request('searchKey'); //搜索关键字

        $start = ($page-1)*$perPage;

        $index = Helper_Function::$index;
        //直接调用es api去精简查询
        $url = "http://192.168.141.131:9200/{$index}/{$log_type}/_search?size={$perPage}&from={$start}";


        //存在搜索字段就搜索
        if(!empty($feild)){
            $url .= "&q={$field}:{$keyword}";
        }elseif(!empty($keyword)){
            //搜索字段不存在的时候就去搜
            $url .= "&q=*{$keyword}*";
        }

        //var_dump($url);die;
        $data = file_get_contents($url);

        $data = json_decode($data,true);

        $ret = [];
        foreach ($data['hits']['hits'] as $hit) {
            //var_export($hit);die;
            $ret[] = $hit['_source'];
        }
//        echo '<pre>';
//
//        var_export();die;
        $ret = static::hydrate($ret);

        $paginator = new LengthAwarePaginator($ret, $data['hits']['total'], $perPage);

        $paginator->setPath(url()->current());

        return $paginator;
    }

}
