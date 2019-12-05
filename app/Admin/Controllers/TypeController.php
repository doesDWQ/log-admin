<?php

namespace App\Admin\Controllers;

use App\Models\Type;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '日志类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $obj = new Grid(new Type());

        $obj->column('id', __('Id'));
        $obj->column('name', __('name'));
        $obj->column('keys', __('keys'));
        $obj->column('created_at', __('Created at'));
        $obj->column('updated_at', __('Updated at'));

        return $obj;
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
