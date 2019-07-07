<?php
namespace app\api\controller;
use app\common\controller\Api;
use think\Hook;
use \Yansongda\Pay\Pay;
use fast\Random;
use EasyWeChat\Foundation\Application;
use think\Controller;
use think\Cache;
use think\Db;
class Test extends Controller{
    private $user;
    public function tm()
    {
        $model=model('Demo');
        $data = $model::get(18);
        //dump($data->toArray());               //转数组
        //1.获取器(数据表字段和非数据表字段)
        //dump($data->append(['user_text'])->toArray());               //state字段
        //dump($data->user_text->toArray(0));
        //2.修改器
        //dump(model('Demo')->save(['name'=>'h11','pass'=>'abc','user'=>'dsad']));
        //3.只读属性测试
        //dump($data->save(['name'=>'h11','state'=>4]));
        //4.时间戳测试
        //dump($data->save(['name'=>'h11','age'=>rand(1,100)]));               //update_time
        //dump(model('Demo')->save(['name'=>'dwqe','age'=>rand(1,100)]));      //create_time
        //5.软删除
        //dump(model('Demo')::destroy(6));                                     //delete_time 软删除
        //dump($data->delete());                                               //软删除
        //dump(model('Demo')::destroy(6,true));   //真实删除
        //dump($data->delete(true));              //真是删除
        //6.类型转换
        //dump($model->save(['name'=>1321,'age'=>'12mjs13','user'=>'abc,45']));
        //7.自动完成
        //dump($model->save(['name'=>'xiaogang','age'=>rand(1,100),'user'=>'sad','pass'=>123456]));
        //8.前置事件
        //dump($model->save(['name'=>'xiaoming','age'=>rand(1,100),'user'=>'sad','pass'=>123456]));
        //9.过滤非数据表字段
        //dump($model->save(['name'=>1,'haha'=>1]));                                                  //未过滤报错
        //dump($model->allowField(true)->save(['name'=>1,'haha'=>1]));                                //过滤不报错
        //dump($model->allowField(['age','email'])->save(['name'=>'djs','age'=>rand(1,100)]));        //允许指定字段
        //10.批量更新 强制更新
        //$list = [['id'=>4, 'name'=>'thinkphp','age'=>rand(1,100)],['id'=>5, 'name'=>'onethink','age'=>rand(1,100)]];
        //dump($model->isUpdate()->saveAll($list));
        //11.闭包更新
        //$queryFunc=function($query){$query->where('age','<',50);};
        //dump($model->save(['age' => rand(50,100)],$queryFunc));
        dump($model->getLastSql());


    }
    public function tq(){
        $model=model('Demo');
        $umodel=model('User');
        //1.闭包查询
        $id=10;
        $queryFunc=function($query) use ($id) {
            $query->where('id','>',$id)
                ->whereor('id',8);
        };
        // dump($model->where($queryFunc)->select());
        //2.with查询
        $data=$model::with('Userinfo')->find(25);
        // 获取用户关联的phone模型
        // dump($data);
        dump($data->userinfo->toArray());

    }
    public function index(){
        $pay = \Yansongda\Pay\Pay::alipay(\addons\epay\library\Service::getConfig('alipay'));

//构建订单信息
        $order = [
            'out_trade_no' => date("YmdHis"),//你的订单号
            'total_amount' => 1,//单位元
            'subject'      => 'FastAdmin企业支付插件测试订单',
        ];

//跳转或输出
        return $pay->scan($order);
// return $pay->wap($order)->send();
    }
    public function ext(){
        hook('testhook',['name'=>122]);
    }


    public function cy(){
        header('Access-Control-Allow-Origin:*');
        $model=model('Cy');
        $act=$this->request->param('act');
        if($act=='start'){
            $start_cy=$model->orderRaw('rand()')->find();
            echo '我先开始了:'.$start_cy['idiom'];
            Cache::set('end',$start_cy['end']);
            Cache::set('cy',$start_cy['idiom']);
            Cache::set('times',0);
        }elseif($act=='end'){
            $str="本轮结束:总次数:".Cache::get('times')."成功次数:".Cache::get('right')."失败:".Cache::get('wrong').'下次加油啦';
            Cache::clear();
            return $str;
        }elseif($cy=$this->request->param('cy')){
            Cache::inc('times');
            $cyinfo=$model::where('idiom',$cy)->find();
            if(!$cyinfo){
                Cache::inc('wrong');
                return '好像不是成语哦,再想一下啦,我的成语:'.Cache::get('cy');
            }
            if($cyinfo['start']==Cache::get('end')){
                $linkcy=$model->where('start',$cyinfo['end'])->orderRaw('rand()')->find();
                Cache::set('end',$linkcy['end']);
                Cache::set('cy',$linkcy['idiom']);
                Cache::inc('right');
                echo '你的成语正确,该我啦.'.$linkcy['idiom'];

            }else{
                Cache::inc('wrong');
                return '成语对呢,可是接不上啊,我的成语:'.Cache::get('cy');
            }
        }
    }

}
