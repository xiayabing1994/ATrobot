<?php
namespace app\api\controller;

use think\Controller;

class Response extends Controller{

    private $_request;
	public function __construct(\think\Request $request){
      header('Access-Control-Allow-Origin:*');
         $this->_request=$request;
	}
    public function add(){
      $params=$this->_request->param();
      if(empty($params['keyword']) || empty($params['response']) || empty($params['userid'])){
        return json(['code'=>104,'msg'=>'参数错误']);
      }
      if(db('response')->where(['keyword'=>$params['keyword'],'userid'=>$params['userid']])->find()){
        return json(['code'=>102,'msg'=>'关键词已存在']);
      }
      if(db('response')->insert(['keyword'=>$params['keyword'],'response'=>$params['response'],'userid'=>$params['userid']])){
       return json(['code'=>100,'msg'=>'添加成功']);
      }
      return json(['code'=>103,'msg'=>'添加失败']);
    }
}