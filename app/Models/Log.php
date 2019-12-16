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

        $field = request('fields','_all'); //搜索字段
        $keyword = request('searchKey'); //搜索关键字

        $start = ($page-1)*$perPage;

        $params = [
            'from'=>$start,
            'size'=> $perPage,
            'index' => Helper_Function::$index,
            'type' => $log_type,
            'body' => [
                'query' => [
                    "match_all" => new \stdClass()
                ],
                'sort' => [
                    '_timestamp_'=>[
                        'order'=>'desc',
                    ],
                ],
            ]
        ];

        //存在搜索字段的时候
        if(!empty($keyword)){
            $params['body'] =[
                'query' => [
                    'match' => [
                        $field => $keyword
                    ]
                ]
            ];
        }

        $data = Helper_Function::getEsClient()->search($params);

        //var_dump($data);die;

        $ret = [];
        foreach ($data['hits']['hits'] as $hit) {
            $ret[] = $hit['_source'];
        }

        $ret = static::hydrate($ret);

        $total = 0;
        if(!empty($ret['hits']['total'])){
            $total = $ret['hits']['total'];
        }
        $paginator = new LengthAwarePaginator($ret, $total, $perPage);

        $paginator->setPath(url()->current());

        return $paginator;
    }

}
