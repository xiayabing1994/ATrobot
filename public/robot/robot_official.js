var bind_host = "";
var is_barrage = 0; //消息发送类型
var is_top = 0, is_new = 0, is_ad = 0; //话题，新闻，广告
var imgarr,lvimgarr,presentimgarr,getimgarr,rankarr;
var dengchang_img = "",quanzhan_img="",songli_img="";
var selfMsgs = []; //自定义消息回复
var guide = {}; //话题等信息
var times_topic = 60; //1分钟话题计时
var counts_topic = 5,num_topic = 0; //规定时间内的信息条数，不够5条发送一个话题
var times_advert = 120; //2分钟广告计时
var times_news = 180; //3分钟一次新人引导
var heart_monitor = 600;  //监听主播心跳
var speakNum = 0;  //直播间发言数量
var isPause = false;  //停止话题引导、新人引导等
var comeNum = 0; //直播间进场人数
var on_idiomGame = false;
var open_monitor_seconds = 600;
var open_monitor_times= 10;
var reapeat_presents=[];  //重复发送礼物数组


//获取本场粉丝榜
var bcList = $(".liveroom_zmcBoxFans_content_nei .liveroom_zmcBoxFans_list_d3_text a");
bcList = bcList.map(function(index, a){
	return a.innerText;
})

//获取周贡献榜前六名
$(".liveroom_zmcBoxFans_nav ul li:eq(1)").click();
var zcList = $(".liveroom_zmcBoxFans_content_nei .liveroom_zmcBoxFans_list_d3_text a");
zcList = zcList.map(function(index, a){
	return a.innerText;
})

var serial_robot = localStorage.getItem("serial_robot");
var zhuboName = $(".liveRoom_main2_infor_nickname").text();  //主播
var selfusername = $('.liveRoom_land_nickname').text(); //场控机器人


if(serial_robot){
   if(confirm('是否启动机器人')) checkCode(serial_robot)
}else{
	serial_robot = prompt("首次使用机器人，请先输入注册码");
    if(serial_robot != null && serial_robot != ""){
    	 checkCode(serial_robot)
  	}
}

function getMsg(arr){
	var len = arr.length;
    var random = parseInt(Math.random()*len);
  	return arr[random];
}
function getInfo(){
	$.ajax({
      url:"https://robot.xlove99.top/api/official/getofficialconfig",
      type:"GET",
      success:function(res){
          //console.log(JSON.stringify(res))
          imgarr = res.imgarr;
          //lvimgarr = res.lvimgarr;
          presentimgarr = res.presentimgarr;          
          getimgarr = res.getimgarr;
       	  rankarr = res.ranksarr; //粉丝榜回复
        	
          /*times_topic = res.imgarr.topic_monitor_seconds;
          counts_topic = res.imgarr.topic_monitor_times;
          times_advert = res.imgarr.ad_send_seconds;
          times_news = res.imgarr.new_send_seconds; 
          heart_monitor = res.imgarr.heart_monitor_seconds;
          fgTimes = res.imgarr.repeat_judge_seconds;
          cy_game_seconds = res.imgarr.cy_game_seconds;
          cy_gameend_seconds = res.imgarr.cy_gameend_seconds;
          open_monitor_seconds = res.imgarr.open_monitor_seconds;
          open_monitor_times = res.imgarr.open_monitor_times;*/
          
          
          //console.log(getimgarr);
          listenli();
          topic();
          fnNew();
          advert();
      }
	})
}
function topic(){
  	if(is_top != 0){
        var times_top = times_topic;
        var setIn_topic = setInterval(function(){
            if(times_top > 0){
                times_top--;
            }else{
                if(num_topic <= counts_topic){
                    if(guide.topic && guide.topic.length > 0){
                        var msg  = getMsg(guide.topic);
                        sendMsg(msg);
                    }
                }
                num_topic = 0;
                times_top = times_topic;
            }
        },1000)
    }
}
function advert(){
  	if(is_ad != 0){
        var times_ad =  times_advert;
        var setIn_advert = setInterval(function(){
             if(times_ad > 0){
                times_ad--;
             }else{
                if(guide.ad && guide.ad.length > 0){
                    var msg = getMsg(guide.ad);
                    sendMsg(msg);
                }
                times_ad = times_advert;
             }
        },1000)
    }
}
function fnNew(){
  	if(is_new != 0){
        var times_new =  times_news;
        var setIn_new = setInterval(function(){
          if(times_new > 0){
            times_new--;
          }else{
            if(guide.new && guide.new.length > 0){
              var msg = getMsg(guide.new);
              sendMsg(msg);
            }
            times_new = times_news;
          }
        },1000)
    }
}


//向直播间发送消息
function sendMsg(msg){
  	var msg = msg;
  	if(is_barrage == 1){
    	msg = msg.substr(0,30)
    }
  	console.log(msg);
  	if(isPause){
    	return false;
    }
  	$('#messageEmoji').val(msg);
   	if(is_barrage == 0){
         $(".liveroom_gift_send").click();
    }else{
          $('.liveroom_gift_barrage').click();
    }
}

//检验注册码是否过期
function checkCode(c){
    $.ajax({
        url:"https://robot.xlove99.top/api/official/check",
        type:"POST",
        data:{c:c},
        success:function(res){
          //若已过期则返回的不为100
          if(res.code != 100){
              alert(res.msg);                                 //如果不为100，后面所有操作不执行
              return false;
          }
          //var expire_time=res.data.expire_time;
          /*var host_id=$('.liveRoom_main2_infor_room_number').text().substr(3);    //判定绑定的主播id与当前主播是否相同
          if(host_id != res.data.bind_host){
              alert('当前主播未绑定或绑定不符');
              return false;
          }*/
          //bind_host = res.data.bind_host;
          is_barrage = res.data.is_barrage=='1' ? 1 : 0;                          //区别弹幕发送还是普通发送
          is_top = res.data.is_topic;
          is_new = res.data.is_new;
          is_ad = res.data.is_ad;
          selfMsgs = res.msgs;
          guide = res.guide;
          quanzhan_img = res.data.quanzhan_img;
          songli_img = res.data.songli_img;
          dengchang_img = res.data.dengchang_img;
          times_topic = res.data.topic_monitor_seconds;
          counts_topic = res.data.topic_monitor_times;
          times_advert = res.data.ad_send_seconds;
          times_news = res.data.new_send_seconds; 
          repeat_presents = res.data.repeat_thanks_presents; 
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
    //console.log(lastLi)
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
          //console.log(username)
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
          	//通过: 判断有无发言
          	isChat = true;
          	speakNum++;
        }
        //送礼需要排除全站送礼
      	if(li.nodeName === "IMG"){
          	if(li.src === quanzhan_img){
            	isSong = false;
              	break;
           	}else if(li.src === songli_img){
            	isSong = true
            }
        }
      
    }
    
    if(username != selfusername){
       	num_topic ++;
    }
    
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
    /** if(username && isLight){
       	var arr = imgarr['lightup_response'];
       	var msg = getMsg(arr);
      	msg = replaceName(msg,username);
      	sendMsg(msg); 
    	//sendMsg('感谢@'+username+'的点亮');
    }
    
    //判断关注
     if(username && isFollow){
       	var arr = imgarr['focus_response'];
       	var msg = getMsg(arr);
      	msg = replaceName(msg,username);
      	sendMsg(msg); 
    	//sendMsg("感谢@"+username+"的关注");
    }
    */
    
    //送礼物
    if(username && isSong){
        var opimgSrc = lastLi.target.lastChild.src;           //获取送礼图片地址
        var arr = imgarr['present_response'];
      	var msg = getMsg(arr);
        msg = replaceName(msg,username);
      	if(presentimgarr[opimgSrc]){
        	msg = msg.replace("present",presentimgarr[opimgSrc])
            if(repeat_presents.indexOf(presentimgarr[opimgSrc]) >= 0) {
        	    console.log(presentimgarr[opimgSrc]);
              if(presentimgarr[opimgSrc]=='跑车') msg='感谢老板送出跑车,为本队加分@'+username;
              if(presentimgarr[opimgSrc]=='明星主播') msg='滴滴...老板送出明星主播,本队减分@'+username;
              if(presentimgarr[opimgSrc]=='别墅') msg='感谢老板送出别墅,捣蛋环节开始@'+username;
               sendMsg(msg);sendMsg(msg);
            }
        	sendMsg(msg);   
        }
    }
    
    //判断闪亮登场
    if(lastLi.target.lastChild.nodeName === "IMG"){
      	
      	var name = lastLi.addedNodes[lastLi.addedNodes.length-2].innerText;
      	//zcList, bcList
      	var zcLen = zcList.length,
            bcLen = bcList.length;
     	var msg = "";
      	var isFensi = false;

        var imgSrc = lastLi.addedNodes[lastLi.addedNodes.length-1].src;
        if(imgSrc == dengchang_img){
            for(var i = 0; i < bcLen;i++ ){
              if(bcList[i] == username){
                var arr = rankarr.scene[i];
                msg = getMsg(arr);
                isFensi = true;
                break;
              }
            }
            for(var i = 0; i < zcLen;i++ ){
              if(zcList[i] == username){
                var arr = rankarr.week[i];
                msg = getMsg(arr);
                isFensi = true;
                break;
              }
            }
            if(isFensi){
             	 msg = replaceName(msg,username);
              	 sendMsg(msg); 
            } 
        } 
    }

    //判断来了
    if(lastLi.target.lastChild.nodeName === "SPAN" && lastLi.target.lastChild.innerText === "来了"){                      //用户进入直播间	
      	//zcList, bcList
      	var zcLen = zcList.length,
            bcLen = bcList.length;
     	var msg = "";
      	var isFensi = false;
     	for(var i = 0; i < bcLen;i++ ){
        	if(bcList[i] == username){
                 var arr = rankarr.scene[i];
                 msg = getMsg(arr);
              	 isFensi = true;
                 break;
            }
        }
      	for(var i = 0; i < zcLen;i++ ){
        	if(zcList[i] == username){
                 var arr = rankarr.week[i];
                 msg = getMsg(arr);
              	 isFensi = true;
                 break;
            }
        }
      	//var lvimgSrc = lastLi.addedNodes[1].src;
        if(isFensi){
          msg = replaceName(msg,username);
          sendMsg(msg); 
        } 
       	
    }
    
	//自定义回复
    if(username && isChat){
        var userMsg = lastLi.addedNodes[lastLi.addedNodes.length-1].innerText;
      	userMsg = userMsg.trim();
      	if(userMsg.indexOf("@"+selfusername) > -1){
           userMsg = userMsg.replace("@"+selfusername,"");
           for(var i = 0; i < selfMsgs.length; i++){
            if(userMsg.indexOf(selfMsgs[i].keyword) > -1){
              sendMsg(selfMsgs[i].response); 
            }
        }
        }
        
   	}
    
  });
 
  Observer.observe(ul, {
    childList: true,
    subtree: true
  });
}