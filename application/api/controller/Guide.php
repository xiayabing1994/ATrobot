<?php
namespace app\api\controller;

use think\Controller;

class Guide extends Controller{

    private $_request;
    private $model=null;
    public function __construct(\think\Request $request){
        header('Access-Control-Allow-Origin:*');
        $this->_request=$request;
        $this->model=new \app\admin\model\Guide;
    }
    public function allguides(){
        $res=[];
        $guides=$this->model->where('status',1)->select();
        foreach($guides as $k=>$v){
            $res[$v['g_cate']][]=$v['g_content'];
        }
        return json($res);
    }
}