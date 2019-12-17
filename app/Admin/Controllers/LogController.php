<?php

namespace App\Admin\Controllers;

use App\Helper\Helper_Function;
use App\Models\Log;
use App\Models\Type;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '日志';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    protected function grid()
    {
        $obj = new Grid(new Log);

        $log_type = request('log_type');

        $types = Type::getTypes();
        if(empty($types)){
            throw new \Exception('日志类型为空，请先添加日志类型！');
        }

        if(empty($log_type)){
            //类型为空修改请求数据,便于在模型中接收处理
            $log_type = $types[0]['name'];
            request()->merge(['log_type'=>$log_type]);
        }

        //var_dump($log_type);die;
        $keys = $types[0]['keys'];
        $params = [];
        foreach ($types as $type) {
            if($log_type==$type['name']){
                $keys = $type['keys'];
            }
            $params[$type['name']] = $type['name'];
        }
        $keys = explode(',',$keys);
        $keys_copy = [];
        foreach ($keys as $key) {
            $obj->column($key, __($key))->style('text-align: center;')->limit(20)->copyable();
            $keys_copy[$key] = $key;
        }

        $obj->column('_timestamp_','时间')->display(function ($val){
            return date('Y-m-d H:i:s',$val);
        });
        $obj->column('ip','ip地址');

        $obj->filter(function($filter)use($params,$keys_copy){
            $filter->column(1/3,function ($filter)use($params){
                $filter->equal('log_type','日志类型')->select($params)->load("fields","/log_admin/getLogDetail");
            });

            $filter->column(1/3,function ($filter)use($params,$keys_copy){
                $filter->equal("fields","搜索字段")->select($keys_copy);
            });

            $filter->column(1/3,function ($filter)use($params){
                $filter->like("searchKey","搜索值");
            });

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();
        });

        return $obj;
    }

    public function getLogDetail(){
        $type = request('q');
        $data = Type::query()->select('keys')->where(['name'=>$type])->first();

        if(empty($data)){
            return [];
        }
        $data = explode(',',$data->toArray()['keys']);
        $ret = [];
        foreach ($data as $datum) {
            $ret[] = ['id'=>$datum,'text'=>$datum];
        }
        return $ret;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $obj = new Show(Type::findOrFail($id));

        $obj->field('id', __('Id'));
        $obj->field('name', __('name'));
        $obj->field('keys', __('keys'));

        return $obj;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $obj = new Form(new Type);

        $obj->text('name', __('name'));
        $obj->text('keys', __('keys'));


        return $obj;
    }
}
