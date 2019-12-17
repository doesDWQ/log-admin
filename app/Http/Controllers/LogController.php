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

        $body = json_decode($body,true);
        if(!is_array($body)){
            return Helper_Function::error('body需要传递一个json字符串数组');
        }

        if(empty($body)){
            return Helper_Function::error('body需要传递一个非空的json字符串数组');
        }

        $body_keys = array_keys($body);

        $types = Type::getTypes();
//        if(empty($types)){
//            return Helper_Function::error('没有日志索引,请先初始化索引');
//        }

        $names = array_column($types,'name');
        if(!in_array($report_type,$names)){
            //return Helper_Function::error('日志类型上报错误！');

            //日志类型不存在的时候，添加日志类型
            Type::query()->firstOrCreate([
                'name'=>$report_type,
                'keys'=>implode(',',$body_keys),
            ]);
        }else{
            //存在的时候比对是否存在新添加的字段，如果存在直接添加进来
            $keys_old = [];
            $keys_temp = '';
            $id = 0;
            foreach ($types as $type) {
                if($type['name']==$report_type){
                    $id = $type['id'];
                    $keys_temp = $type['keys'];
                    $keys_old = explode(',',$keys_temp);
                }
            }
            $new_keys = implode(',',array_unique(array_merge($keys_old,$body_keys)));

            //两个字符串合并后不相等表示有新增的字段
            if($new_keys!=$keys_temp){
                Type::query()->where(['id'=>$id])->update(['keys'=>$new_keys]);
            }

        }


        //添加上一个插入时间戳
        $body['_timestamp_'] = time();

        //var_dump($report_type);die;
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
