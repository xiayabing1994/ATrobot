<?php
namespace  app\api\controller;
use think\Db;
use think\Controller;
class Setting extends Controller{
    private $_request=null;
    public function __construct(\think\Request $request){
        $this->_request=$request;
    }

    public function change(){
       $sname=$this->_request->param('sname');
       if(Db::name('user_setting')->query("UPDATE `fa_user_setting` set {$sname}=1-{$sname} where userid=".cookie('uid'))){
           return json(['code'=>100]);
       }
    }
}