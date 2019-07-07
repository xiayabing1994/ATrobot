<?php
namespace app\api\controller;
use think\Db;
use think\Controller;

class Crontab{
    public function monitorCard(){
        $res=Db::name('card')->where('card_no','not in',function($query){
            $query->name('monitor')->where('createtime','>',time()-600)->field('card_no');
        })->update(['is_using'=>0]);
        dump($res);
    }
}