/*------------------------------------------------------------------------
 * Copyright (c) 2014 hailiang All rights reserved. by Jackal
 ------------------------------------------------------------------------*/

$(function () {

    $(".indexbg").click(function () {
        $(this).addClass("dismiss");
        //$(this).removeClass("show");
        document.querySelector(".indexbg").addEventListener("webkitAnimationEnd", function () {
            $(this).removeClass("show");
            $(".page").addClass("show");
        })
    });

    /* $(".finish").click(function () {
     checkSubmitAll();
     });*/

    /*$(".share").click(function () {
     location.href = "support.html";
     });*/


    /* $(".agree").click(function () {
     location.href = "agree.html";
     });*/
    /*$(".disagree").click(function () {
     location.href = "disagree.html";
     });*/

    /*	$(".loginbtn").click(function(){
     location.href = "status.html";
     });*/

    /* $(".retry").click(function () {
     location.href = "index.html";
     });*/

    /* $(".goodguy").click(function () {
     location.href = "goodguy.html";
     });

     $(".badguy").click(function () {
     location.href = "badguy.html";
     });
     */
    $(".sharetipmask").click(function () {
        $(this).removeClass("show");
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



var _namespace_micro = {}
_namespace_micro.winxinShare = function (shareData) {
    function shareFriend() {
        WeixinJSBridge.invoke('sendAppMessage', {
            "img_url": shareData.imgUrl,
            "img_width": "640",
            "img_height": "640",
            "link": shareData.link,
            "title": shareData.title,
            "desc": shareData.desc
        }, function (res) {
            _report('send_msg', res.err_msg);
        })
    }

    function shareTimeline() {
        WeixinJSBridge.invoke('shareTimeline', {
            "img_url": shareData.imgUrl,
            "img_width": "640",
            "img_height": "640",
            "link": shareData.link,
            "title": shareData.title,
            "desc": shareData.desc
        }, function (res) {
            _report('timeline', res.err_msg);
        });
    }

    function shareWeibo() {
        WeixinJSBridge.invoke('shareWeibo', {
            "content": window.shareData.title,
            "url": window.shareData.link
        }, function (res) {
            _report('weibo', res.err_msg);
        });
    }

    // 当微信内置浏览器完成内部初始化后会触发WeixinJSBridgeReady事件。
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {

        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            shareFriend();
        });

        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            shareTimeline();
        });

        // 分享到微博
        WeixinJSBridge.on('menu:share:weibo', function (argv) {
            shareWeibo();
        });

    }, false)
}




