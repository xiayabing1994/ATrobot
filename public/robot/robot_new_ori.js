var is_barrage = 0; //消息发送类型
var imgarr,lvimgarr,prensentimgarr,getimgarr;
var selfMsgs = []; //自定义消息回复
var times_topic = 120; //2分钟计时
var num_topic = 0; //规定时间内的信息条数，30条为例
if(confirm('是否启动机器人')){
  if(confirm('是否开启弹幕')) is_barrage=1;
  var selfnickname=$('.liveRoom_land_nickname').text();
  console.log(selfnickname);
  getInfo();
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
          console.log(getimgarr);
          listenli();
      }
	})
}

//向直播间发送消息
function sendMsg(msg){
    $('#messageEmoji').val(msg);
  	console.log(msg)
    /*if(is_barrage == 0){
          $(".liveroom_gift_send").click();
    }else{
          $('.liveroom_gift_barrage').click();
    }*/
}
var timer = setInterval(function(){
  	if(times_topic > 0){
      	times_topic--;
    }else{
      	if(num_topic == 30){
        	sendMsg("这是一条话题内容！！！");
        }
      	num_topic = 0;
    	times_topic = 120;
    }
},1000)
//监听消息框消息
function listenli(){
  var ul = document.querySelector(".liveroom_chat_ul");
  var Observer = new MutationObserver(function (mutations, instance) {
    //这里时间听到的事件
    //console.log(mutations);
    num_topic ++;
    console.log(num_topic);
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
    //console.log(username);
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

    /*mutations.forEach(function (mutation) {
      	console.log(mutation);
    });*/
    
  });
 
  Observer.observe(ul, {
    childList: true,
    subtree: true
  });
}