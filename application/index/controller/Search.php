<?php

namespace app\index\controller;
use think\Db;

class Search{

    private $_request=null;
    public function __construct(\think\Request $request){
        $this->_request=$request;
    }
    public function weixin(){
        $wxid=$this->_request->param('wxid');
        if(empty($wxid)) return 0;
        $where=['wxid'=>$wxid];
        if(Db::table('sea_weixin')->where($where)->find()){
            return 1;
        }else{
            Db::table('sea_weixin')->insert(['wxid'=>$wxid,'addtime'=>time()]);
            return 0;
        }

    }

    public function group(){
        $groupid=$this->_request->param('groupid');
        if(empty($groupid)) return 0;
        $where=['groupid'=>$groupid];
        if(Db::table('sea_group')->where($where)->find()){
            return 1;
        }else{
            Db::table('sea_group')->insert(['groupid'=>$groupid,'addtime'=>time()]);
            return 0;
        }
    }
}