/*------------------------------------------------------------------------
 * Copyright (c) 2014 hailiang All rights reserved. by Jackal
 ------------------------------------------------------------------------*/
var _namespace_micro = {}



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

    _namespace_micro.loadMask();

    // 关闭弹出层
    $(".mask").click(function () {
        $(this).removeClass("show");
        _namespace_micro.closeMaskCall();
    });

});

// 加载弹出层
_namespace_micro.loadMask = function () {
    $("body").append('<div class="mask"><div class="dailog"><h2>人品大挑战</h2><span></span><a href="###"></a></div></div>');
}

//关闭弹出层回调
_namespace_micro.closeMaskCall = function () {
    return false;
}


function alertView(wrongmessage) {
    $(".mask").addClass("show");
    $(".dailog span").text(wrongmessage);
}


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




