<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:73:"/www/wwwroot/robot/public/../application/index/view/user/usersetting.html";i:1559380131;s:61:"/www/wwwroot/robot/application/index/view/layout/default.html";i:1559025855;s:58:"/www/wwwroot/robot/application/index/view/common/meta.html";i:1558950519;s:61:"/www/wwwroot/robot/application/index/view/common/sidenav.html";i:1559380599;s:60:"/www/wwwroot/robot/application/index/view/common/script.html";i:1557482264;}*/ ?>
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
    <div class="row">
        <div class="col-md-3">
            <div class="sidenav">
    <?php echo hook('user_sidenav_before'); ?>
    <ul class="list-group">
        <li class="list-group-heading"><?php echo __('User center'); ?></li>
        <li class="list-group-item <?php echo $config['actionname']=='index'?'active':''; ?>"> <a href="<?php echo url('user/index'); ?>"><i class="fa fa-user-circle fa-fw"></i> <?php echo __('User center'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='usersetting'?'active':''; ?>"> <a href="<?php echo url('user/usersetting'); ?>"><i class="fa fa-certificate fa-fw"></i> <?php echo __('个人设置'); ?></a> </li>
        <!--        <li class="list-group-item <?php echo $config['actionname']=='profile'?'active':''; ?>"> <a href="<?php echo url('user/profile'); ?>"><i class="fa fa-user-o fa-fw"></i> <?php echo __('Profile'); ?></a> </li>-->
        <li class="list-group-item <?php echo $config['actionname']=='usercard'?'active':''; ?>"> <a href="<?php echo url('user/usercard'); ?>"><i class="fa fa-free-code-camp fa-fw"></i> <?php echo __('我的注册码'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='response'?'active':''; ?>"> <a href="<?php echo url('user/response'); ?>"><i class="fa fa-commenting fa-fw"></i> <?php echo __('自定义消息管理'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='changepwd'?'active':''; ?>"> <a href="<?php echo url('user/changepwd'); ?>"><i class="fa fa-key fa-fw"></i> <?php echo __('Change password'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='logout'?'active':''; ?>"> <a href="<?php echo url('user/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo __('Sign out'); ?></a> </li>

    </ul>
    <?php echo hook('user_sidenav_after'); ?>
</div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2 class="page-header"><?php echo __('机器人设置'); ?></h2>
                    <p>弹幕开关:
                        <a href="javascript:changeSetting('is_barrage');" data-toggle="tooltip"  class="btn-change" data-original-title="点击切换">
                        <i class="fa fa-toggle-on <?php echo $setting['is_barrage']==1?'text-success' : 'fa-flip-horizontal text-gray'; ?> fa-2x">
                        </i>
                        </a>
                    </p>
                    <p>话题开关:
                        <a href="javascript:changeSetting('is_top');" data-toggle="tooltip"  class="btn-change" data-original-title="点击切换">
                        <i class="fa fa-toggle-on <?php echo $setting['is_top']==1?'text-success' : 'fa-flip-horizontal text-gray'; ?> fa-2x">
                        </i>
                    </a></p>
                    <p>pk推送开关:
                        <a href="javascript:changeSetting('is_pk');" data-toggle="tooltip"  class="btn-change" data-original-title="点击切换">
                        <i class="fa fa-toggle-on <?php echo $setting['is_pk']==1?'text-success' : 'fa-flip-horizontal text-gray'; ?> fa-2x">
                        </i>
                    </a></p>
                    <p>新人引导开关:<a href="javascript:changeSetting('is_new');" data-toggle="tooltip"  class="btn-change" data-original-title="点击切换">
                        <i class="fa fa-toggle-on <?php echo $setting['is_new']==1?'text-success' : 'fa-flip-horizontal text-gray'; ?> fa-2x">
                        </i>
                    </a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function changeSetting(sname){
        $.post('/api/setting/change',{sname:sname},function(data){location.reload()})
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