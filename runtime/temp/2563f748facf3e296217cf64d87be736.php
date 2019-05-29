<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:70:"/www/wwwroot/robot/public/../application/index/view/user/usercard.html";i:1559027411;s:61:"/www/wwwroot/robot/application/index/view/layout/default.html";i:1559025855;s:58:"/www/wwwroot/robot/application/index/view/common/meta.html";i:1558950519;s:61:"/www/wwwroot/robot/application/index/view/common/sidenav.html";i:1558425414;s:60:"/www/wwwroot/robot/application/index/view/common/script.html";i:1557482264;}*/ ?>
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
            <style type="text/css">
    
    .mark{
        font-size:18px;
        font-weight:bold;
    }
</style>
<div id="content-container" class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="sidenav">
    <?php echo hook('user_sidenav_before'); ?>
    <ul class="list-group">
        <li class="list-group-heading"><?php echo __('User center'); ?></li>
        <li class="list-group-item <?php echo $config['actionname']=='index'?'active':''; ?>"> <a href="<?php echo url('user/index'); ?>"><i class="fa fa-user-circle fa-fw"></i> <?php echo __('User center'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='profile'?'active':''; ?>"> <a href="<?php echo url('user/profile'); ?>"><i class="fa fa-user-o fa-fw"></i> <?php echo __('Profile'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='changepwd'?'active':''; ?>"> <a href="<?php echo url('user/changepwd'); ?>"><i class="fa fa-key fa-fw"></i> <?php echo __('Change password'); ?></a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='usercard'?'active':''; ?>"> <a href="<?php echo url('user/usercard'); ?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo __('我的注册码'); ?></a> </li>
                <li class="list-group-item <?php echo $config['actionname']=='response'?'active':''; ?>"> <a href="<?php echo url('user/response'); ?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo __('自定义消息管理'); ?></a> </li>
              <li class="list-group-item <?php echo $config['actionname']=='logout'?'active':''; ?>"> <a href="<?php echo url('user/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo __('Sign out'); ?></a> </li>

    </ul>
    <?php echo hook('user_sidenav_after'); ?>
</div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2 class="page-header"><?php echo __('我的注册码'); ?></h2>
                    <p> <a href="javascript:$.getScript('https://robot.xlove99.top/robot/robot.js');" onclick="alert('请把按钮拖动到书签栏');return false;" style='cursor:move' class="btn  btn-success btn-add "><?php echo $site['site_title']; ?></a></p>
                    <p style="color:darkolivegreen;font-size:16px">请将绿色 “AT场控”按钮<span class='mark'>拖拽</span>至网址栏下方的标签栏区域。<span class='mark'>进入主播房间网页</span>后，点击启动AT场控。<br>
                        注：第一次使用，请复制下方注册码</p>
                    <table width='100%'>
                    	<tr>
                    		<th style='width:22px'>注册码</th>
                            <th>到期时间</th>
                            <th>弹幕开关</th>
                            <th>绑定主播id</th>
                    		<th>操作</th>
                    	</tr>
                    	<?php if(is_array($usercards) || $usercards instanceof \think\Collection || $usercards instanceof \think\Paginator): $i = 0; $__LIST__ = $usercards;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$card): $mod = ($i % 2 );++$i;?>
                    	<tr>
                    		<td width='12%'><?php echo $card['card_no']; ?></td>
                            <td><?php echo date('Y-m-d H:i',$card['expire_time']); ?></td>
                          <td><button onclick="barrageChange('<?php echo $card['card_no']; ?>')" > <?php echo $card['is_barrage']==1?'开' : '关'; ?> </button> </td>
                          <td><?php echo $card['bind_host']; ?></td>
                    		<td><?php echo $card['bind_host']!=''?'': "<a href='cardbind/ids/$card[id]' class='btn btn-xs btn-success btn-editone ' title='绑定主播'><i class='fa fa-pencil'></i></a>"; ?>
                                
                          <a onclick="alert(<?php echo $card['c_url']; ?>)" class='btn btn-xs btn-success btn-editone ' title='生成书签'><i class='fa fa-circle'></i></a>
                          </td>
                    	</tr>
                    	<?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
      
    function barrageChange(no){
      $.post('/api/card/barragechange',{card_no:no},function(){window.location.reload();})
    }
  function puturl(url){
    $('#puturl').text(url);
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