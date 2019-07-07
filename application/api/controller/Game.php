<?php
namespace app\api\controller;
use think\Controller;
use think\Cache;
use think\Request;
class Game extends Controller
{
    protected $request=null;
    public function __construct(Request $request = null)
    {
        $this->request = is_null($request) ? Request::instance() : $request;
        header('Access-Control-Allow-Origin:*');
    }

    /**
     * @成语接龙游戏
     */
    public function cy()
    {
        $model = model('Cy');
        $act = $this->request->param('act');
        if ($act == 'start') {
            $start_cy = $model->orderRaw('rand()')->find();
            $str= '我先开始了:' . $start_cy['idiom'];
            Cache::set('end', $start_cy['end']);
            Cache::set('cy', $start_cy['idiom']);
            Cache::set('times', 0);
            return $str;
        } elseif ($act == 'end') {
            if(!Cache::get('end')) return '';
            $str = "本轮结束:总次数:" . Cache::get('times') . "成功次数:" . Cache::get('right') . "失败:" . Cache::get('wrong') . '下次加油啦';
            Cache::clear();
            return $str;
        } elseif ($cy = $this->request->param('cy')) {
            if(!Cache::get('end')) return '';
            Cache::inc('times');
            $cyinfo = $model::where('idiom', $cy)->find();
            if (!$cyinfo) {
                Cache::inc('wrong');
                return '好像不是成语哦,再想一下啦,我的成语:' . Cache::get('cy');
            }
            if ($cyinfo['start'] == Cache::get('end')) {
                $linkcy = $model->where('start', $cyinfo['end'])->orderRaw('rand()')->find();
                Cache::set('end', $linkcy['end']);
                Cache::set('cy', $linkcy['idiom']);
                Cache::inc('right');
                echo '你的成语正确,该我啦.' . $linkcy['idiom'];

            } else {
                if(!Cache::get('end')) return '';
                Cache::inc('wrong');
                return '成语对呢,可是接不上啊,我的成语:' . Cache::get('cy');
            }
        }
    }
    private function getTip($type){
        $arr=[
            'start'=>[
                '我先开始了:word',
                'word,请从这个词开始吧',
                'word,我就不信你们能接上',
            ],
            'end'=>[
                '本轮结束了,祝大家下次玩儿的开心',
                '游戏结束了,可是人家还没玩儿开心呢,多想再来一局啊'
            ],
            'wrong'=>[
                '成语错了,叫上语文老师一起玩儿哦',
                '错了错了，不要灰心，玩的多就好了'
            ],
            'not'=>[
                'word?你也太淘气了吧,那个假成语就想忽悠我,哼',
                '小瞧我,我一秒就看出来这个word不是个成语'
            ]


        ];
    }
}