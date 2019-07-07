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
    //if(($cards['expire_time'])<time()){
     //   return json(['code'=>102,'msg'=>'注册码已过期','expire'=>$cards['expire_time']]);
   // }
    db('card')->where('card_no',$card)->update(['times'=>$cards['times']+1]);
    if(!$conf=db('user_setting')->where('userid',$cards['userid'])->find()){
        db('user_setting')->insert(['userid'=>$cards['userid']]);
    }
    $conf=db('user_setting')->where('userid',$cards['userid'])->find();
    unset($conf['id']);
    $conf['is_ad']=load_config('robot')['is_ad'];
    $msgs=db('response')->where('userid',$cards['userid'])->select();
    $returndata=[
        'bind_host'=>$cards['bind_host'],
        'expire_time'=>$cards['expire_time'],
//            'is_barrage'=>$cards['is_barrage'],
    ];
    return json(['code'=>100,'msg'=>'校验成功','data'=>array_merge($returndata,$conf),'msgs'=>$msgs,'guide'=>$this->getGuide()]);
}
    public function checkFree(){

        $card=$this->_request->param('c');
        $cards=db('card')->where('card_no',$card)->find();
        if(!empty($cards)){
            db('card')->where('card_no',$card)->update(['times'=>$cards['times']+1]);
            $msgs=db('response')->where('userid',$cards['userid'])->select();
        }
    }
    public function refresh(){
	    $userid=$this->_request->param('userid');
        $conf=db('user_setting')->where('userid',$userid)->find();
        unset($conf['id']);
        $conf['is_ad']=load_config('robot')['is_ad'];
        $msgs=db('response')->where('userid',$userid)->select();
        return json(['code'=>100,'msg'=>'刷新成功','data'=>$conf,'msgs'=>$msgs,'guide'=>$this->getGuide()]);
    }

    public function getConfig(){
        $imgarr=[];
        $robot_config=db('config')->where('group', 'in','crontab,robot')->select();
        foreach($robot_config as $rv){
            $imgarr[$rv['name']]=$rv['value'];
        }
        $imgarr['present_reponse']=explode(';',str_replace('；',';',$imgarr['present_reponse']))[0];
        $imgarr['huojiang_response']=explode(';',str_replace('；',';',$imgarr['huojiang_response']))[0];
        $imgarr['lightup_response']=explode(';',str_replace('；',';',$imgarr['lightup_response']))[0];
        $imgarr['focus_response']=explode(';',str_replace('；',';',$imgarr['focus_response']))[0];
    	$lvimgarr=[];
    	$imgs=db('lvimg')->where('is_open',1)->select();
        foreach($imgs as $v){
           $lvimgarr[$v['lv_img']]=explode(';',str_replace('；',';',$v['lv_response']))[0];
        }
        $prensentimgarr=[];
        $getimgarr=[];
        $presents=db('present')->where('is_effect','是')->select();
        foreach($presents as $v){
            if($v['is_thanks']=='是' && $v['is_whole']=='否') $prensentimgarr[$v['present_url']]=$v['present_name'];
            if($v['is_notice']=='是') $getimgarr[trim($v['present_url'])]=$v['present_name'];
        }
        $rankthanks=db('rank_thanks')->select();
        foreach($rankthanks as $k=>$v){
            $rankarr[$v['type']][]=$v['content'];
        }
        return json(['imgarr'=>$imgarr,'lvimgarr'=>$lvimgarr,'prensentimgarr'=>$prensentimgarr,'getimgarr'=>$getimgarr,'rankarr'=>$rankarr]);
	}
    public function getConfig2(){
        $imgarr=[];
        $robot_config=db('config')->where('group', 'in','crontab,robot')->select();
        foreach($robot_config as $rv){
            $imgarr[$rv['name']]=$rv['value'];
        }
        $thanks=db('thanks')->select();
        foreach($thanks as $thank){
            if($thank['type']=='lightup') $imgarr['lightup_response'][]=$thank['content'];                                        if($thank['type']=='focus') $imgarr['focus_response'][]=$thank['content'];
            if($thank['type']=='huojiang') $imgarr['huojiang_response'][]=$thank['content'];
            if($thank['type']=='present') $imgarr['present_response'][]=$thank['content'];
        }

        $lvimgarr=[];
        $imgs=db('lvimg')->where('is_open',1)->select();
        foreach($imgs as $v){
            $lvimgarr[$v['lv_img']]=explode(';',str_replace('；',';',$v['lv_response']));
        }
        $prensentimgarr=[];
        $getimgarr=[];
        $presents=db('present')->where('is_effect','是')->select();
        $rankthanks=db('rank_thanks')->select();
        foreach($rankthanks as $k=>$v){
            $rankarr[$v['type']][]=$v['content'];
            $ranksarr[$v['type']][$v['rank']][]=$v['content'];
        }
        foreach($presents as $v){
            if($v['is_thanks']=='是' && $v['is_whole']=='否') $prensentimgarr[$v['present_url']]=$v['present_name'];
            if($v['is_notice']=='是') $getimgarr[trim($v['present_url'])]=$v['present_name'];
        }
        return json(['imgarr'=>$imgarr,'rankarr'=>$rankarr,'ranksarr'=>$ranksarr,'lvimgarr'=>$lvimgarr,'presentimgarr'=>$prensentimgarr,'getimgarr'=>$getimgarr]);
    }
    public function getConfigPro(){
        $imgarr=[];
        $robot_config=db('config')->where('group', 'in','crontab,robot')->select();
//        foreach($robot_config as $rv){
//            $imgarr[$rv['name']]=$rv['value'];
//        }
        $imgarr=[];
        $thanks=db('thanks')->select();
        foreach($thanks as $v){
            $imgarr[$v['type'].'_response'][]=$v['content'];
        }
//        $imgarr['present_reponse']=explode(';',str_replace('；',';',$imgarr['present_reponse']));
//        $imgarr['huojiang_response']=explode(';',str_replace('；',';',$imgarr['huojiang_response']));
//        $imgarr['lightup_response']=explode(';',str_replace('；',';',$imgarr['lightup_response']));
//        $imgarr['focus_response']=explode(';',str_replace('；',';',$imgarr['focus_response']));
        $lvimgarr=[];
        $imgs=db('lvimg')->where('is_open',1)->select();
        foreach($imgs as $v){
            $lvimgarr[$v['lv_img']]=explode(';',str_replace('；',';',$v['lv_response']));
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
    public function getConfigFree(){
        $card=$this->_request->param('c');
        $cards=db('card')->where('card_no',$card)->find();
        $msgs=[];
        if(!empty($cards)){
            db('card')->where('card_no',$card)->update(['times'=>$cards['times']+1]);
            $msgs=db('response')->where('userid',$cards['userid'])->field('keyword,response')->select();
        }
        $imgarr=[];
        $robot_config=load_config('robot');
        $imgarr=[];
        $thanks=db('thanks_free')->select();
        foreach($thanks as $v){
            $imgarr[$v['type'].'_response'][]=$v['content'];
        }
        $imgarr['songli_img']=$robot_config['songli_img'];
        $imgarr['quanzhan_img']=$robot_config['quanzhan_img'];
        $imgarr['dengchang_img']=$robot_config['dengchang_img'];
        $imgarr['custom_msgs']=$msgs;
        $lvimgarr=[];
        $imgs=db('lvimg')->where('is_open',1)->select();
        foreach($imgs as $v){
            $lvimgarr[$v['lv_img']]=explode(';',str_replace('；',';',$v['lv_response']));
        }

        $presentimgarr=[];
        $getimgarr=[];
        $presents=db('present')->where('is_effect','是')->select();
        foreach($presents as $v){
            if($v['is_thanks']=='是' && $v['is_whole']=='否') $presentimgarr[$v['present_url']]=$v['present_name'];
            if($v['is_notice']=='是') $getimgarr[trim($v['present_url'])]=$v['present_name'];
        }
        return json(['imgarr'=>$imgarr,'lvimgarr'=>$lvimgarr,'presentimgarr'=>$presentimgarr,'getimgarr'=>$getimgarr]);
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