<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/4
 * Time: 18:28
 */
namespace App\Helper;
use Elasticsearch\ClientBuilder;

class Helper_Function
{

    //es客户端
    public static $esClient = null;
    //指定日志库的索引
    public static $index = 'log_pro';

    //获取es客户端
    public static function getEsClient(){

        if(empty(self::$esClient)){
            $host = config('es.host');
            self::$esClient = ClientBuilder::create()->setHosts([$host])->build();
        }

        return self::$esClient;
    }

    //对es数据源进行过滤
    public static function getEsResult($ret){

        return $ret;
    }

    public static function success($data){
        return [
            'code'=>200,
            'msg'=>'ok',
            'data'=>$data,
        ];
    }

    public static function error($msg,$code=100){
        return [
            'code'=>$code,
            'msg'=>$msg,
            'data'=>[],
        ];
    }

}