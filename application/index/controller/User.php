<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\Db;

/**
 * 会员中心
 */
class User extends Frontend
{
    protected $layout = 'default';
    protected $noNeedLogin = ['login', 'register', 'third'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;

        if (!Config::get('fastadmin.usercenter')) {
            $this->error(__('User center already closed'));
        }

        //监听注册登录注销的事件
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('sms_send',function($sms){
            $mobile=$sms['mobile'];
            $content=$msg = rawurlencode(mb_convert_encoding("您的验证码为:".$sms['code'], "gb2312", "utf-8"));
            $gateway="http://sdk2.028lk.com/sdk2/BatchSend2.aspx?CorpID=ZZJS004155&Pwd=zm0513@&Mobile={$mobile}&Content={$msg}&Cell=&SendTime=";
            echo $gateway;
            if(file_get_contents($gateway)>0) return true;
            return false;
        });
        Hook::add('sms_check',function($sms){
            return true;
        });
    }

    /**
     * 空的请求
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        $data = Hook::listen("user_request_empty", $name);
        foreach ($data as $index => $datum) {
            $this->view->assign($datum);
        }
        return $this->view->fetch('user/' . $name);
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->view->assign('title', __('User center'));
        return $this->view->fetch();
    }

    /**
     * 注册会员
     */
    public function register()
    {
        $url = $this->request->request('url', '', 'trim');
        if ($this->auth->id) {
            $this->success(__('You\'ve logged in, do not login again'), $url ? $url : url('user/index'));
        }
        if ($this->request->isPost()) {
            $username = $this->request->post('mobile');
            $password = $this->request->post('password');
            $email = $this->request->post('email');
            $mobile = $this->request->post('mobile', '');
            $captcha = $this->request->post('captcha');
            $token = $this->request->post('__token__');            
           $bindhost = $this->request->post('bindhost');           
           $p_code = $this->request->post('p_code');

          $sms=new \app\common\library\Sms();
            $code = $this->request->post('code', '');
            if(!$sms->check($mobile,$code,'register')){
                $this->error('短信验证码错误', null, ['token' => $this->request->token()]);
            }
            $rule = [
                'mobile'    => 'regex:/^1\d{10}$/',
//                'captcha'   => 'require|captcha',
                '__token__' => 'token',
            ];

            $msg = [
//                'captcha.require'  => 'Captcha can not be empty',
//                'captcha.captcha'  => 'Captcha is incorrect',
                'mobile'           => 'Mobile is incorrect',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                'mobile'    => $mobile,
//                'captcha'   => $captcha,
                '__token__' => $token,
            ];
            $validate = new Validate($rule, $msg);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
            }
            if ($this->auth->register($username, $password, $email, $mobile)) {
                db('card')->insert([
                  'card_no'=>mkCardNo(),
                  'userid'=>Cookie::get('uid'),
                  'bind_host'=>$bindhost,
                  'addtime'=>time(),
                  'expire_time'=>1560960000,
                  'is_active'=>0,
                  'comment'=>'注册生成'
                ]);
                if($p_code=='6666' || $p_code=='8888') db('user')->where('id',Cookie::get('uid'))->update(['p_code'=>$p_code]);
                $this->success(__('Sign up successful'), $url ? $url : url('user/index'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        //判断来源
        $referer = $this->request->server('HTTP_REFERER');
        if (!$url && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
            && !preg_match("/(user\/login|user\/register|user\/logout)/i", $referer)) {
            $url = $referer;
        }
        $this->view->assign('url', $url);
        $this->view->assign('title', __('Register'));
        return $this->view->fetch();
    }

    /**
     * 会员登录
     */
    public function login()
    {
        $url = $this->request->request('url', '', 'trim');
        if ($this->auth->id) {
            $this->success(__('You\'ve logged in, do not login again'), $url ? $url : url('user/index'));
        }
        if ($this->request->isPost()) {
            $account = $this->request->post('account');
            $password = $this->request->post('password');
            $keeplogin = (int)$this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $rule = [
                'account'   => 'require|length:3,50',
                'password'  => 'require|length:6,30',
                '__token__' => 'token',
            ];

            $msg = [
                'account.require'  => 'Account can not be empty',
                'account.length'   => 'Account must be 3 to 50 characters',
                'password.require' => 'Password can not be empty',
                'password.length'  => 'Password must be 6 to 30 characters',
            ];
            $data = [
                'account'   => $account,
                'password'  => $password,
                '__token__' => $token,
            ];
            $validate = new Validate($rule, $msg);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                return false;
            }
            if ($this->auth->login($account, $password)) {
                $this->success(__('Logged in successful'), $url ? $url : url('user/index'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        //判断来源
        $referer = $this->request->server('HTTP_REFERER');
        if (!$url && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
            && !preg_match("/(user\/login|user\/register|user\/logout)/i", $referer)) {
            $url = $referer;
        }
        $this->view->assign('url', $url);
        $this->view->assign('title', __('Login'));
        return $this->view->fetch();
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        //注销本站
        $this->auth->logout();
        $this->success(__('Logout successful'), url('user/index'));
    }

    /**
     * 个人信息
     */
    public function profile()
    {
        $this->view->assign('title', __('Profile'));
        return $this->view->fetch();
    }

    /**
     * 修改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost()) {
            $oldpassword = $this->request->post("oldpassword");
            $newpassword = $this->request->post("newpassword");
            $renewpassword = $this->request->post("renewpassword");
            $token = $this->request->post('__token__');
            $rule = [
                'oldpassword'   => 'require|length:6,30',
                'newpassword'   => 'require|length:6,30',
                'renewpassword' => 'require|length:6,30|confirm:newpassword',
                '__token__'     => 'token',
            ];

            $msg = [
            ];
            $data = [
                'oldpassword'   => $oldpassword,
                'newpassword'   => $newpassword,
                'renewpassword' => $renewpassword,
                '__token__'     => $token,
            ];
            $field = [
                'oldpassword'   => __('Old password'),
                'newpassword'   => __('New password'),
                'renewpassword' => __('Renew password')
            ];
            $validate = new Validate($rule, $msg, $field);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                return false;
            }

            $ret = $this->auth->changepwd($newpassword, $oldpassword);
            if ($ret) {
                $this->success(__('Reset password successful'), url('user/login'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        $this->view->assign('title', __('Change password'));
        return $this->view->fetch();
    }
    public function  usercard(){
        $cards=db('card')->where('userid',Cookie::get('uid'))->select();
      foreach($cards as $k=>$v){
        $cards[$k]['c_url']="javascript:$.getScript('https://robot.xlove99.top/robot/robot.js?c=".$v['card_no']."')";
      }
        $this->assign('usercards',$cards);
        return $this->view->fetch();
    }
   public function  response(){
        $responses=db('response')->where('userid',Cookie::get('uid'))->paginate(10);
       $page = $responses->render();
        $this->assign('responses',$responses);
        $this->assign('page',$page);
        return $this->view->fetch();
   } 
  public function responseadd(){
    if($this->request->post()){
      $keyword=$this->request->post('keyword');
       $response=$this->request->post('response');
       if(db('response')->where(['keyword'=>$keyword,'userid'=>cookie('uid')])->find()){
           $this->error('关键词已存在');
       }
       if(db('response')->insert(['addtime'=>time(),'keyword'=>$keyword,'response'=>$response,'userid'=>cookie('uid')])){
         $this->success('添加成功');
       }else{
         $this->error('添加失败');
       }
    }
    return $this->view->fetch();
  }
    public function responseedit(){
    if($this->request->post('keyword')){
       $keyword=$this->request->post('keyword');
       $response=$this->request->post('response');
       $id=$this->request->post('id');
       if(db('response')->where(['userid'=>cookie('uid'),'id'=>$id])->update(['keyword'=>$keyword,'response'=>$response])){
         $this->success('修改成功');
       }else{
         $this->error('修改失败');
       }
    }
    $this->view->assign('row',db('response')->where('id',$this->request->param('ids'))->find());
    return $this->view->fetch();
  }
  public function responsedel(){
      $id=$this->request->param('ids');
      if(db('response')->where('id',$id)->delete()){
        $this->success('删除成功');
      }else{
        $this->error('删除失败');
      }
  }
  public function cardbind(){
    if($id=$this->request->post('id')){
      $bind_host=$this->request->post('bind_host');
      if(db('card')->where('id',$id)->update(['bind_host'=>$bind_host])){
        $this->success('绑定成功',url('user/usercard'));
      }else{
        $this->error('绑定失败');
      }
    }
    $id=$this->request->param('ids');
    $this->view->assign('row',db('card')->where('id',$this->request->param('ids'))->find());
    return $this->view->fetch();
  }
  public function usersetting(){
        if(!Db::name('user_setting')->where('userid',cookie('uid'))->find()){
            Db::name('user_setting')->insert(['userid'=>cookie('uid')]);
        }
        $setting=Db::name('user_setting')->where('userid',cookie('uid'))->find();
        $this->assign('setting',$setting);
        return $this->view->fetch();
  }
}
