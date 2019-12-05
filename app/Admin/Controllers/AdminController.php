<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Title';

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
//        'index'  => 'Index',
//        'show'   => 'Show',
//        'edit'   => 'Edit',
//        'create' => 'Create',
    ];

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        try{
            return $content = $content
                ->title($this->title())
                ->description($this->description['index'] ?? trans('admin.list'))
                ->body($this->grid());
        }catch (\Exception $e){
            //抓到异常直接显示到界面上去
            return $content
                ->withError('Error', $e->getMessage());
        }

    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        try{
            return $content
                ->title($this->title())
                ->description($this->description['show'] ?? trans('admin.show'))
                ->body($this->detail($id));
        }catch (\Exception $e){
            //抓到异常直接显示到界面上去
            return $content
                ->withError('Error', $e->getMessage());
        }

    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        try{
            return $content
                ->title($this->title())
                ->description($this->description['edit'] ?? trans('admin.edit'))
                ->body($this->form()->edit($id));
        }catch (\Exception $e){
            //抓到异常直接显示到界面上去
            return $content
                ->withError('Error', $e->getMessage());
        }


    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        try{
            return $content
                ->title($this->title())
                ->description($this->description['create'] ?? trans('admin.create'))
                ->body($this->form());
        }catch (\Exception $e){
            //抓到异常直接显示到界面上去
            return $content
                ->withError('Error', $e->getMessage());
        }

    }
}
