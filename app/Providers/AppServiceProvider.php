<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        DB::listen(function ($query){
            $sql = $query->sql;

            foreach ($query->bindings as $value){
                try{
                    $value = is_numeric($value) ? $value :"'{$value}'";
                    $sql = preg_replace('/\?/',$value,$sql,1);
                }catch (\Exception $e){
                    //日期等其他对象的时候回报错
                    Log::info('抓取sql报错：'.$e->getMessage());
                }
            }
            Log::info($query->time.':'.$sql);
        });
    }
}
