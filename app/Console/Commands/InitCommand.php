<?php

namespace App\Console\Commands;

use App\Helper\Helper_Function;
use App\Models\Type;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:run {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化脚本';

    //命令对应的处理方法
    protected $typesAndFunction = [
        'init'=>'dealInit',
        'delete'=>'deleteIndex',
    ];

    public function dealInit(){
        $client = Helper_Function::getEsClient();
        $response = $client->indices()->getMapping();
        $index = [
            'index' => Helper_Function::$index,
        ];
        $client->indices()->create($index);
        var_dump($response);
    }

    public function deleteIndex(){
        $client = Helper_Function::getEsClient();
        $index = [
            'index' => Helper_Function::$index,
        ];
        $client->indices()->delete($index);
    }

    //事务处理主体
    public function main(){
        $command = $this->argument('action');
        var_export($command);
        if(!in_array($command,$this->typesAndFunction)){
            echo '处理命令输入错误！';die;
        }
        $this->$command();
    }


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
	    parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->startFlag();
        //处理具体的业务
        $this->main();
        $this->endFlag();
    }

    public function startFlag(){
        Log::info($this->signature.'任务开始执行-------------------------------------------------!');
    }

    public function endFlag(){
        Log::info($this->signature.'任务执行完毕-------------------------------------------------!');
        exit('执行完成');
    }

}