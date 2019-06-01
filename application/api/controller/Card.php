<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
class Card extends Controller{

    private $_request;
    private $guideModel=null;
	public function __construct(\think\Request $request){
      header('Access-Control-Allow-Origin:*');
         $this->_request=$request;
        $this->guideModel=new \app\admin\model\Guide;
	}
    public function check(){

    	$card=$this->_request->param('c');
        if(empty($card)) return json(['code'=>104,'msg'=>'请申请注册码后再使用']);
    	$cards=db('card')->where('card_no',$card)->find();
        if(empty($cards)) return  json(['code'=>103,'msg'=>'请使用正确的注册码']);
        if(!$cards['is_active']){
            $data=[
                'active_time'=>time(),
                'is_active'=>1,
                'times'=>1,
            ];
            if($cards['last_day']>0) $data['expire_time']=time()+$cards['last_day']*86400;
            if(db('card')->where('card_no',$card)->update($data)){
            }
//            $cards['expire_time']=time()+$cards['last_day']*86400;
        }
        if($cards['expire_time']<time()){
            return json(['code'=>102,'msg'=>'注册码已过期','expire'=>$cards['expire_time']]);
        }
        db('card')->where('card_no',$card)->update(['times'=>$cards['times']+1]);
        $msgs=db('response')->where('userid',$cards['userid'])->select();
        $returndata=[
            'bind_host'=>$cards['bind_host'],
            'expire_time'=>$cards['expire_time'],
            'is_barrage'=>$cards['is_barrage'],
        ];
    	return json(['code'=>100,'msg'=>'校验成功','data'=>$returndata,'msgs'=>$msgs,'guide'=>$this->getGuide()]);
    }

    public function getConfig(){
        $imgarr=[];
        $robot_config=db('config')->where('group', 'in','crontab,robot')->select();
        foreach($robot_config as $rv){
            $imgarr[$rv['name']]=$rv['value'];
        }
        $imgarr['test_response']=explode(';',str_replace('；',';',$imgarr['test_response']));
    	$lvimgarr=[];
    	$imgs=db('lvimg')->where('is_open',1)->select();
        foreach($imgs as $v){
           $lvimgarr[$v['lv_img']]=$v['lv_response'];
        }
        $prensentimgarr=[];
        $getimgarr=[];
        $presents=db('present')->where('is_effect','是')->select();
        foreach($presents as $v){
            if($v['is_thanks']=='是' && $v['is_whole']=='否') $prensentimgarr[$v['present_url']]=$v['present_name'];
            if($v['is_notice']=='是') $getimgarr[trim($v['present_url'])]=$v['present_name'];
        }
        return json(['imgarr'=>$imgarr,'lvimgarr'=>$lvimgarr,'prensentimgarr'=>$prensentimgarr,'getimgarr'=>$getimgarr]);
	}
    public function barrageChange(){
         $card_no=$this->_request->param('card_no');
         if(empty($card_no)) return json(['code'=>104,'msg'=>'参数错误']);
         $where=['card_no'=>$card_no];
         $cardinfo=db('card')->where($where)->find();
         $is_barrage= $cardinfo['is_barrage']==1 ? 0 : 1;
         if(db('card')->where($where)->update(['is_barrage'=>$is_barrage])){
            return  json(['code'=>100,'msg'=>'修改成功']);
         }else{
            return json(['code'=>103,'msg'=>'修改失败']);
         }
    }
    public function bindHost(){
         $card_no=$this->_request->param('card_no');
         $bind_host=$this->_request->param('bind_host');
         if(empty($card_no) || empty($bind_host)) return json(['code'=>104,'msg'=>'参数错误']);
         if(db('card')->where('card_no',$card_no)->update(['bind_host'=>$bind_host])){
            return  json(['code'=>100,'msg'=>'绑定成功']);
         }else{
            return json(['code'=>103,'msg'=>'绑定失败']);
         }
    }
    public function monitor(){
	    $params=$this->_request->param();
	    if(!$params['card_no'] || !$params['bind_host']){
	        return json(['code'=>104,'msg'=>'参数错误']);
        }
	    Db::startTrans();
	    try{
	        Db::name('monitor')->insert(['card_no'=>$params['card_no'],'bind_host'=>$params['bind_host'],'createtime'=>time()]);
	        Db::name('card')->where(['card_no'=>$params['card_no'],'bind_host'=>$params['bind_host']])->update(['is_using'=>1]);
	        Db::commit();
            return json(['code'=>100,'msg'=>'监听成功']);

        }catch(\Exceptions $e){
	        Db::rollback();
            return json(['code'=>103,'msg'=>'监听失败']);
        }

    }
    private function getGuide(){
        $res=[];
        $guides=$this->guideModel->where('status',1)->select();
        foreach($guides as $k=>$v){
            $res[$v['g_type']][]=$v['g_content'];
        }
        return $res;
    }

}