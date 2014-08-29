/*------------------------------------------------------------------------
 * Copyright (c) 2014 hailiang All rights reserved. by Jackal
------------------------------------------------------------------------*/
var shareData = {
		imgUrl: "http://jackal.sinaapp.com/ds/assets/img/share_ico.jpg",
		timeLineLink: "gq.hailiang.cn/hd",
		tTitle: "快，抓住那小三",
		tContent: ""
	};

$(function(){

	$(".indexbg").click(function(){ 
		$(this).addClass("dismiss");
		//$(this).removeClass("show");
		document.querySelector(".indexbg").addEventListener("webkitAnimationEnd", function(){
			$(this).removeClass("show");
			$(".page").addClass("show");
		})
	});

	$(".finish").click(function(){ 
		checkSubmitAll();
	});
	
	$(".share").click(function(){ 
		location.href = "support.html";
	});


	$(".agree").click(function(){ 
		location.href = "agree.html";
	});
	$(".disagree").click(function(){ 
		location.href = "disagree.html";
	});
	
	$(".loginbtn").click(function(){ 
		location.href = "status.html";
	});
	
	$(".retry").click(function(){ 
		location.href = "index.html";
	});
	
	$(".goodguy").click(function(){ 
		location.href = "goodguy.html";
	});
	
	$(".badguy").click(function(){ 
		location.href = "badguy.html";
	});
	
	$(".sharetipmask").click(function(){ 
		$(this).removeClass("show");
	});

	$(".sending").click(function(){ 
		alert("支持成功！立即开始我的人品测试");
		location.href = "index.html";
	});
	
});


//验证邮箱 
//function checkSubmitEmail(){ 
//    if($("#email").val()==""){ 
//    	$("#confirmMsg").html("<font color='red'>邮箱地址不能为空！</font>"); 
//    	$("#email").focus(); 
//    	return false; 
//	} 
//   if(!$("#email").val().match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){ 
//    	$("#confirmMsg").html("<font color='red'>邮箱格式不正确！请重新输入！</font>"); 
//    	$("#email").focus(); 
//		return false; 
//   } 
//   return true; 
//} 

//验证手机
function checkSubmitAll(){ 
	if($(".say").val()==""){ 
		alert("内容不能为空！")
		$(".say").focus();
		return false; 
	} 
	
	if($(".address").val()==""){ 
		alert("地址不能为空！")
		$(".address").focus();
		return false; 
	} 

	if($(".phone").val()==""){ 
		alert("手机号码不能为空！")
		$(".phone").focus();
		return false; 
	} 

	if(!$(".phone").val().match(/^1[3|4|5|8][0-9]\d{4,8}$/)){ 
		alert("手机号码格式不正确！请重新输入！")
		$(".phone").focus();
		return false; 
   } 
	
	location.href = "confirm.html";

}

//分享到朋友圈
//function weixinShareTimeline(){ 
//alert("11");
//    WeixinJSBridge.invoke('shareTimeline',{ 
//        "img_url":"img/weixingimg.jpg", 
//        //"img_width":"640", 
//        //"img_height":"640", 
//        "link":"http://gq.hailiang.cn/hd", 
//        "desc": desc, 
//        "title":"我发起了测人品，别让我的节操碎了一地"
//    });  
//}


$(function(){

    var shareData = {
        imgUrl: "http://gq.hailiang.cn/hd/img/wexingimg.jpg",
        timeLineLink: "gq.hailiang.cn/hd/support.html",
        tTitle: "人品测试",
        tContent: ""
    };


    // 分享
    function shareFriend() {
        WeixinJSBridge.invoke('sendAppMessage',{
            //"appid":window.shareData.appid,
            "img_url":"http://gq.hailiang.cn/hd/img/wexingimg.jpg",
            "img_width":"640",
            "img_height":"640",
            "link":"gq.hailiang.cn/hd/support.html",
            "desc":'你的朋友分享一个人品测试，快来帮帮他吧',
            "title":'人品测试'
        }, function(res) {
            _report('send_msg', res.err_msg);
        })
    }
    function shareTimeline() {
        WeixinJSBridge.invoke('shareTimeline',{
            "img_url":"http://gq.hailiang.cn/hd/img/wexingimg.jpg",
            "img_width":"640",
            "img_height":"640",
            "link":"gq.hailiang.cn/hd/support.html",
            "desc":'你的朋友分享一个人品测试，快来帮帮他吧',
            "title":'人品测试'
        }, function(res) {
            _report('timeline', res.err_msg);
        });
    }
    function shareWeibo() {
        WeixinJSBridge.invoke('shareWeibo',{
            "content":window.shareData.wContent,
            "url":window.shareData.wLink
        }, function(res) {
            _report('weibo', res.err_msg);
        });
    }
    // 当微信内置浏览器完成内部初始化后会触发WeixinJSBridgeReady事件。
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {

        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function(argv){
            shareFriend();
        });

        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function(argv){
            shareTimeline();
        });

        // 分享到微博
        WeixinJSBridge.on('menu:share:weibo', function(argv){
            shareWeibo();
        });

    }, false)
});