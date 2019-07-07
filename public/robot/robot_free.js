var imgarr,lvimgarr,presentimgarr,getimgarr;
var selfMsgs = []; //自定义消息回复
var times_advert = 120; //2分钟广告计时;
var serial_robot = localStorage.getItem("serial_robot");
var selfusername = $('.liveRoom_land_nickname').text();
if(serial_robot){
   if(confirm('是否启动机器人')) getInfo(serial_robot);
}else{
	serial_robot = prompt("请输入注册码免费体验关键词回复,若没有可点击确定跳过");
	getInfo(serial_robot);
}

function getInfo(c){
	$.ajax({
      url:"https://robot.xlove99.top/api/card/getconfigfree",
      type:"POST",
      data:{
      	c:c
      },
      success:function(res){
          //console.log(JSON.stringify(res))
          imgarr = res.imgarr;
          lvimgarr = res.lvimgarr;
          presentimgarr = res.presentimgarr;          
          getimgarr = res.getimgarr;
       	  if(res.custom_msgs){
             selfMsgs = res.custom_msgs;
          }
          //times_advert = res.imgarr.ad_send_seconds;
          listenli();
      }
	})
}
//获取信息
function getMsg(arr){
	var len = arr.length;
    var random = parseInt(Math.random()*len);
  	return arr[random];
}
//向直播间发送消息
function sendMsg(msg){
    $('#messageEmoji').val(msg);
  	console.log(msg);
   	/*$(".liveroom_gift_send").click();*/
}
//直播间广告
function advert(){
  	//广告区分免费版和付费版
  	var times_ad =  times_advert;
  	var setIn_advert = setInterval(function(){
   		if(times_ad > 0){
      	times_ad--;
    }else{
      	if(imgarr.ad_response && imgarr.ad_response.length > 0){
       		var msg = getMsg(imgarr.ad_response);
        	sendMsg(msg);
      	}
     	 times_ad = times_advert;
    	}
  	},1000)
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
        //a也可能是图片地址；   关注第一个为username，第二个为直播名
        if(li.nodeName === "A" && li.innerText){
          username = li.innerText;
          break;
        }
    }
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
      	if(li.nodeName === "SPAN" && li.innerText == ":" && username != selfusername){
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
    
    //console.log(username);
    //替换用户名
    function replaceName(msg,username){
      	if(!msg)  return '';
        return msg.replace("username",username);
    }
    
    //判断获奖
    if(username && isGet){
      	var arr = imgarr['huojiang_response'];
      	var msg = getMsg(arr);
      	var lastImg = lastLi.addedNodes[lastLi.addedNodes.length-1].src;
      	if(getimgarr[lastImg]){
         	msg = replaceName(msg,username);
          	msg = msg.replace("present",getimgarr[lastImg]);
          	sendMsg(msg);
        }
    }
    
    //判断点亮
     if(username && isLight){
       	var arr = imgarr['lightup_response'];
       	var msg = getMsg(arr);
      	msg = replaceName(msg,username);
      	sendMsg(msg); 
    }
    
    //判断关注
     if(username && isFollow){
       	var arr = imgarr['focus_response'];
       	var msg = getMsg(arr);
      	msg = replaceName(msg,username);
      	sendMsg(msg); 
    }
    
    //判断闪亮登场
    if(lastLi.target.lastChild.nodeName === "IMG"){
        var imgSrc = lastLi.addedNodes[lastLi.addedNodes.length-1].src;
      	var name = lastLi.addedNodes[lastLi.addedNodes.length-2].innerText;
        if(imgSrc==imgarr['dengchang_img']){
          	var lvimgSrc = lastLi.addedNodes[1].src;
          	if(lvimgarr[lvimgSrc]){
               	var arr = lvimgarr[lvimgSrc];
              	var msg = getMsg(arr);
            	msg = replaceName(msg,name);
            	sendMsg(msg);
            }
        } 
    }
    
    //送礼物
    if(username && isSong){
        var opimgSrc = lastLi.target.lastChild.src;           //获取送礼图片地址
        var arr = imgarr['present_response'];
      	var msg = getMsg(arr);
        msg = replaceName(msg,username);
      	if(presentimgarr[opimgSrc]){
        	msg = msg.replace("present",presentimgarr[opimgSrc])
        	sendMsg(msg);   
        }
    }

    //判断来了
    if(lastLi.target.lastChild.nodeName === "SPAN" && lastLi.target.lastChild.innerText === "来了"){                      //用户进入直播间
    	//根据用户来了图片地址判断用户等级
      	var lvimgSrc = lastLi.addedNodes[1].src;
      	if(lvimgarr[lvimgSrc]){
          	var arr = lvimgarr[lvimgSrc];
          	var msg = getMsg(arr);
            msg = replaceName(msg,username);
            sendMsg(msg); 
       	}
    }
    
    //自定义消息回复
    if(username && isChat && selfMsgs.length > 0){
   		var msg = lastLi.addedNodes[lastLi.addedNodes.length-1].innerText;
      	for(var i = 0; i < selfMsgs.length; i++){
        	if(msg.indexOf(selfMsgs[i].keyword) > -1){
            	   sendMsg(selfMsgs[i].response); 
            }
        }
   	}
    
  });
 
  Observer.observe(ul, {
    childList: true,
    subtree: true
  });
}