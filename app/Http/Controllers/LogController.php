<?php

namespace App\Http\Controllers;


use App\Helper\Helper_Function;
use App\Models\Type;

class LogController extends Controller
{

    //添加上报日志
    public static function addLog(){
        $report_type = request('report_type');
        $body = request('body');

        $types = Type::getTypes();
        if(empty($types)){
            return Helper_Function::error('没有日志类型');
        }

        $names = array_column($types,'name');
        if(!in_array($report_type,$names)){
            return Helper_Function::error('日志类型上报错误！');
        }

        $body = json_decode($body,true);
        //var_dump($body);die;
        if(!is_array($body)){
            return Helper_Function::error('body需要传递一个json字符串数组');
        }

        $client = Helper_Function::getEsClient();
        $response = $client->index([
            'index'=>Helper_Function::$index,
            'type'=>$report_type,
            'body'=>$body,
        ]);
        if($response){
            return Helper_Function::success("保存成功！");
        }else{
            return Helper_Function::error('保存失败！');
        }
    }

}
