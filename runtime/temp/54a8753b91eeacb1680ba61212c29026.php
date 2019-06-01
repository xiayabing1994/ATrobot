<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:70:"/www/wwwroot/robot/public/../application/index/view/user/register.html";i:1559114909;s:61:"/www/wwwroot/robot/application/index/view/layout/default.html";i:1559025855;s:58:"/www/wwwroot/robot/application/index/view/common/meta.html";i:1558950519;s:60:"/www/wwwroot/robot/application/index/view/common/script.html";i:1557482264;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?> – <?php echo $site['site_title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<?php if(isset($site['keywords'])): ?>
<meta name="keywords" content="<?php echo $site['keywords']; ?>">
<?php endif; if(isset($site['description'])): ?>
<meta name="description" content="<?php echo $site['description']; ?>">
<?php endif; ?>
<meta name="author" content="FastAdmin">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />

<link href="/assets/css/frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config: <?php echo json_encode($config); ?>
    };
</script>
        <link href="/assets/css/user.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="header-navbar">
                <span style="font-size:28px;color:red"><?php echo $site['contact']; ?></span>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo url('user/index'); ?>" target="_blank"><?php echo __('Home'); ?></a></li>
                        <li class="dropdown">
                            <?php if($user): ?>
                            <a href="<?php echo url('user/index'); ?>" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 10px;height: 50px;">
                                <span class="avatar-img"><img src="<?php echo cdnurl($user['avatar']); ?>" alt=""></span>
                            </a>
                            <?php else: ?>
                            <a href="<?php echo url('user/index'); ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('User center'); ?> <b class="caret"></b></a>
                            <?php endif; ?>
                            <ul class="dropdown-menu">
                                <?php if($user): ?>
                                <li><a href="<?php echo url('user/index'); ?>"><i class="fa fa-user-circle fa-fw"></i><?php echo __('User center'); ?></a></li>
                                <li><a href="<?php echo url('user/profile'); ?>"><i class="fa fa-user-o fa-fw"></i><?php echo __('Profile'); ?></a></li>
                                <li><a href="<?php echo url('user/changepwd'); ?>"><i class="fa fa-key fa-fw"></i><?php echo __('Change password'); ?></a></li>
                                <li><a href="<?php echo url('user/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i><?php echo __('Sign out'); ?></a></li>
                                <?php else: ?>
                                <li><a href="<?php echo url('user/login'); ?>"><i class="fa fa-sign-in fa-fw"></i> <?php echo __('Sign in'); ?></a></li>
                                <li><a href="<?php echo url('user/register'); ?>"><i class="fa fa-user-o fa-fw"></i> <?php echo __('Sign up'); ?></a></li>
                                <?php endif; ?>

                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content">
            <div id="content-container" class="container">
    <div class="user-section login-section">
        <div class="logon-tab clearfix"> <a href="<?php echo url('user/login'); ?>?url=<?php echo urlencode($url); ?>"><?php echo __('Sign in'); ?></a> <a class="active"><?php echo __('Sign up'); ?></a> </div>
        <div class="login-main"> 
            <form name="form1" id="register-form" class="form-vertical" method="POST" action="">
                <input type="hidden" name="invite_user_id" value="0" />
                <input type="hidden" name="url" value="<?php echo $url; ?>" />
                <?php echo token(); ?>
                 <p>新注册用户可免费体验至6月20日，注册后请到 >>我的注册码页面查看使用 </p>

                <div class="form-group">
                    <label class="control-label"><?php echo __('Mobile'); ?></label>
                    <div class="controls">
                        <input type="text" id="mobile" name="mobile" data-rule="required;mobile" class="form-control input-lg" placeholder="<?php echo __('Mobile'); ?>">
                        <p class="help-block"></p>
                    </div>
                </div>
              <div class="form-group">
                    <label class="control-label"><?php echo __('短信验证码'); ?></label>
                    <div class="controls">
                        <input type="text" id="code" name="code" data-rule="required" class="form-control input-lg" placeholder="<?php echo __('请输入短信验证码'); ?>">
                         <input type="button" style="height:32px;width:120px;" value="点击发送验证码" onclick="sendCode(this)" />

                        <p class="help-block" id="code-help"></p>
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo __('Password'); ?></label>
                    <div class="controls">
                        <input type="password" id="password" name="password" data-rule="required;password" class="form-control input-lg" placeholder="<?php echo __('Password must be 6 to 30 characters'); ?>">
                        <p class="help-block"></p>
                    </div>
                </div>
                              <div class="form-group">
                    <label class="control-label"><?php echo __('绑定主播id'); ?></label>
                    <div class="controls">
                        <input type="text" id="bindhost" name="bindhost" data-rule="required;" class="form-control input-lg" placeholder="<?php echo __('请输入要绑定的红人直播主播id'); ?>">
                        <p class="help-block"></p>
                    </div>
                </div>
<!--                <div class="form-group">-->
<!--                    <label class="control-label"><?php echo __('Captcha'); ?></label>-->
<!--                    <div class="controls">-->
<!--                        <div class="input-group input-group-lg">-->
<!--                            <input type="text" name="captcha" class="form-control" placeholder="<?php echo __('Captcha'); ?>" data-rule="required;length(4)" style="border-radius: 0;" />-->
<!--                            <span class="input-group-addon" style="padding:0;border:none;">-->
<!--                                <img src="<?php echo captcha_src(); ?>" width="140" height="42" onclick="this.src = '<?php echo captcha_src(); ?>?r=' + Math.random();"/>-->
<!--                            </span>-->
<!--                        </div>-->
<!--                        <p class="help-block"></p>-->
<!--                    </div>-->
<!--                </div>-->
                              <div class="form-group">
                    <label class="control-label"><?php echo __('邀请码'); ?></label>
                    <div class="controls">
                        <input type="text" id="p_code" name="p_code" data-rule="" class="form-control input-lg" placeholder="<?php echo __('请输入邀请码(若没有,可不填)'); ?>">
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><?php echo __('Sign up'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
 var clock = '';
 var nums = 60;
 var btn;
 function sendCode(thisBtn)
 { 
 btn = thisBtn;
 $.post('/api/sms/send',{mobile:$('#mobile').val()},function(data){
   console.log(data);
    $('#code-help').text(data.msg);
 });
 btn.disabled = true; //将按钮置为不可点击
 btn.value = nums+'秒后可重新获取';
 clock = setInterval(doLoop, 1000); //一秒执行一次
 }
 function doLoop()
 {
 nums--;
 if(nums > 0){
  btn.value = nums+'秒后可重新获取';
 }else{
  clearInterval(clock); //清除js定时器
  btn.disabled = false;
  btn.value = '点击发送验证码';
  nums = 60; //重置时间
 }
 }
</script>
        </main>

        <footer class="footer" style="clear:both">
            <!-- FastAdmin是开源程序，建议在您的网站底部保留一个FastAdmin的链接 -->
            <p class="copyright">Copyright&nbsp;©&nbsp;2017-2019 Powered by <a href="" target="_blank">xxx</a> All Rights Reserved <?php echo $site['name']; ?> <?php echo __('Copyrights'); ?> <a href="http://www.miibeian.gov.cn" target="_blank"><?php echo $site['beian']; ?></a></p>
        </footer>

        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>

    </body>

</html>