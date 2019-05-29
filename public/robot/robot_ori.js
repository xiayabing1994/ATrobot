document.write("<script type='text/javascript' src='./jquery.js'> </script>");
var is_barrage = 0; //消息发送类型
var imgarr,lvimgarr,prensentimgarr,getimgarr;
var selfMsgs = []; //自定义消息回复
var c = localStorage.getItem("serial_robot");
var selfusername=$('.liveRoom_land_nickname').text();
if(c){
   if(confirm('是否启动机器人')) checkCode(c)
}else{
	c = prompt("首次使用机器人，请先输入注册码");
    if(c != null && c != ""){
    	 checkCode(c)
  	}
}

function getInfo(){
	$.ajax({
      url:"https://robot.xlove99.top/api/card/getconfig",
      type:"GET",
      success:function(res){
          //console.log(JSON.stringify(res))
          imgarr = res.imgarr;
          lvimgarr = res.lvimgarr;
          prensentimgarr = res.prensentimgarr;          
          getimgarr = res.getimgarr;
          //console.log(getimgarr);
          listenli();
      }
	})
}

//向直播间发送消息
function sendMsg(msg){
    $('#messageEmoji').val(msg);
  	console.log(msg)
    if(is_barrage == 0){
          $(".liveroom_gift_send").click();
    }else{
          $('.liveroom_gift_barrage').click();
    }
}

//检验注册码是否过期
function checkCode(c){
  	$.ajax({
      url:"https://robot.xlove99.top/api/card/check",
      type:"POST",
      data:{c:c},
      success:function(res){
          //若已过期则返回的不为100
          if(res.code != 100){
             alert(res.msg);                                 //如果不为100，后面所有操作不执行
             return false;
          }
          //var expire_time=res.data.expire_time;
          var host_id=$('.liveRoom_main2_infor_room_number').text().substr(3);    //判定绑定的主播id与当前主播是否相同
          if(host_id != res.data.bind_host){
            alert('当前主播未绑定或绑定不符');
            return false;
          }
          is_barrage = res.data.is_barrage=='1' ? 1 : 0;                          //区别弹幕发送还是普通发送
          selfMsgs = res.msgs;
          localStorage.setItem("serial_robot",c);
          getInfo();
      }
	})
}

//监听消息框消息
function listenli(){
  var ul = document.querySelector(".liveroom_chat_ul");
  var Observer = new MutationObserver(function (mutations, instance) {
    //这里时间听到的事件
    //console.log(mutations);
    var lastLi = mutations[mutations.length-1];//最新一个li
    var username = "";
    var isGet = false; //判断是否获得奖励
    var isLight = false; //判断是否点亮
    var isFollow = false; //判断是否关注
    var isSong = false; //判断送礼
    var isChat = false; //判断发言
    for (var li of lastLi.target.children) {
        if(li.nodeName === "SPAN" && li.innerText == "获得"){
          	isGet = true
        }
        if(li.nodeName === "SPAN" && li.innerText == "点亮了"){
        	isLight = true
        }
        if(li.nodeName === "SPAN" && li.innerText == "关注了"){
          	isFollow = true
        }
      	if(li.nodeName === "SPAN" && li.innerText == ":"){
          	isChat = true
        }
        //送礼需要排除全站送礼
      	if(li.nodeName === "IMG"){
          	if(li.src === imgarr['quanzhan_img']){
            	isSong = false;
              	break;
           	}else if(li.src === imgarr['songli_img']){
            	isSong = true
            }
        }
      
    }
    for (var li of lastLi.target.children) {
        //a也可能是图片地址；   关注第一个为username，第二个为直播名
        if(li.nodeName === "A" && li.innerText){
          username = li.innerText;
          break;
        }
    }
    
    //替换用户名
    function replaceName(msg,username){
      	if(!msg)  return '';
        return msg.replace("username",username);
    }
    
    //判断获奖
    if(username && isGet){
      	var msg = imgarr['huojiang_response'];
      	var lastImg = lastLi.addedNodes[lastLi.addedNodes.length-1].src;
      	if(getimgarr[lastImg]){
         	msg = replaceName(msg,username);
          	msg = msg.replace("present",getimgarr[lastImg]);
          	sendMsg(msg);
        }
    }
    
    //判断点亮
     if(username && isLight){
       	var msg = imgarr['lightup_response'];
      	msg = replaceName(msg,username);
      	sendMsg(msg); 
    	//sendMsg('感谢@'+username+'的点亮');
    }
    
    //判断关注
     if(username && isFollow){
       	var msg = imgarr['focus_response'];
      	msg = replaceName(msg,username);
      	sendMsg(msg); 
    	//sendMsg("感谢@"+username+"的关注");
    }
    
    //判断闪亮登场
    if(lastLi.target.lastChild.nodeName === "IMG"){
        var imgSrc = lastLi.addedNodes[lastLi.addedNodes.length-1].src;
        if(imgSrc==imgarr['dengchang_img'] && username){
          	var lvimgSrc = lastLi.addedNodes[1].src;
          	if(lvimgarr[lvimgSrc]){
               	var msg = lvimgarr[lvimgSrc];
            	msg = replaceName(msg,username)
            	sendMsg(msg);
            }
        } 
    }
    
    //送礼物
    if(username && isSong){
        var opimgSrc = lastLi.target.lastChild.src;           //获取送礼图片地址
        var msg = imgarr['present_reponse'];
        msg = replaceName(msg,username);
      	if(prensentimgarr[opimgSrc]){
        	msg = msg.replace("present",prensentimgarr[opimgSrc])
        	sendMsg(msg);   
        }
    }

    //判断来了
    if(lastLi.target.lastChild.nodeName === "SPAN" && lastLi.target.lastChild.innerText === "来了"){                      //用户进入直播间
    	//根据用户来了图片地址判断用户等级
      	var lvimgSrc = lastLi.addedNodes[1].src;
      	if(lvimgarr[lvimgSrc]){
          	var msg = lvimgarr[lvimgSrc];
            msg = replaceName(msg,username);
            sendMsg(msg); 
       	}
    }
    
    //自定义消息回复
    if(username && isChat && selfusername != username){
   		var msg = lastLi.addedNodes[lastLi.addedNodes.length-1].innerText;
      	for(var i = 0; i < selfMsgs.length; i++){
        	if(msg.indexOf(selfMsgs[i].keyword) > -1){
            	   sendMsg(selfMsgs[i].response); 
            }
        }
   	}
    
    /*mutations.forEach(function (mutation) {
      	console.log(mutation);
    });*/
    
  });
 
  Observer.observe(ul, {
    childList: true,
    subtree: true
  });
}