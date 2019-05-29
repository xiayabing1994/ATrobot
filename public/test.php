<?php	


function checkProxy(){
		if(!empty($_SERVER['HTTP_VIA'])){
    		if(!isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			 //Anonymous Proxies    普通匿名代理服务器  //代理IP地址为 $_SERVER['REMOTE_ADDR']
              return ['ip'=>$_SERVER['REMOTE_ADDR'],'proxy'=>'anonymous ','proxy_ip'=>$_SERVER['REMOTE_ADDR']];
            }else{
	        //Transparent Proxies 透明代理服务器 //代理IP地址为 $_SERVER['REMOTE_ADDR'] //真实ip地址为 $_SERVER['HTTP_X_FORWARDED_FOR']
	          return ['ip'=>$_SERVER['HTTP_X_FORWARDED_FOR'],'proxy'=>'ransparent ','proxy_ip'=>$_SERVER['REMOTE_ADDR']];
	        }
		}else{       //无代理或高级代理
            return ['ip'=>$_SERVER['REMOTE_ADDR'],'proxy'=>false,'proxy_ip'=>null];
        }
	}
echo '<pre>';
var_dump($_SERVER);var_dump(checkProxy());