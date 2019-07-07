<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
class Official extends Controller
{

    private $_request;
    private $_guide = null;
    private $_response=null;
    private $_card=null;
    public function __construct(\think\Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        $this->_request = $request;
        $this->_guide = new \app\admin\model\GuideOfficial;
        $this->_response = new \app\admin\model\ResponseOfficial;
        $this->_card = new \app\admin\model\CardOfficial;
    }
    public function check(){

        $card=$this->_request->param('c');
        if(empty($card)) return json(['code'=>104,'msg'=>'注册码不能为空']);
        $cards=$this->_card->where('card_no',$card)->find();
        if(empty($cards)) return  json(['code'=>103,'msg'=>'注册码错误']);
        $conf=load_config('official');
        $genConf=load_config('robot');
        unset($genConf['is_ad']);
        $conf['repeat_thanks_presents']=explode(',',str_replace('，',',',$conf['repeat_thanks_presents']));
        $guide=$this->_guide->where('status',1)->select();
        foreach($guide as $k=>$v){
            $guides[$v['g_type']][]=$v['g_content'];
        }
        $msgs=$this->_response->select();
        return json(['code'=>100,'msg'=>'校验成功','data'=>array_merge($this->changeOfficial($conf),$genConf),'msgs'=>$msgs,'guide'=>$guides]);
    }
    public function getOfficialConfig(){
        $imgarr=[];        //感谢回复内容数组
        $thanks=db('thanks_official')->select();
        foreach($thanks as $thank){
            if($thank['type']=='lightup') $imgarr['lightup_response'][]=$thank['content'];                                        if($thank['type']=='focus') $imgarr['focus_response'][]=$thank['content'];
            if($thank['type']=='huojiang') $imgarr['huojiang_response'][]=$thank['content'];
            if($thank['type']=='present') $imgarr['present_response'][]=$thank['content'];
        }
        $presentimgarr=[];   //礼物数组
        $getimgarr=[];       //获奖感谢礼物数组
        $presents=db('present')->where('is_effect','是')->select();
        $rankthanks=db('rank_thanks_official')->select();
        foreach($rankthanks as $k=>$v){
            $ranksarr[$v['type']][$v['rank']][]=$v['content'];
        }
        foreach($presents as $v){
            if($v['is_thanks']=='是' && $v['is_whole']=='否') $presentimgarr[$v['present_url']]=$v['present_name'];
            if($v['is_notice']=='是') $getimgarr[trim($v['present_url'])]=$v['present_name'];
        }
        return json(['imgarr'=>$imgarr,'ranksarr'=>$ranksarr,'presentimgarr'=>$presentimgarr,'getimgarr'=>$getimgarr]);
    }
    private function changeOfficial($arr){
       foreach($arr as $k=>$v) {
           $kk=str_replace('_official','',$k);
           $res[$kk]=$v;
       }
       return $res;
    }

}