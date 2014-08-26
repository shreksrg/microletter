/*------------------------------------------------------------------------
 * Copyright (c) 2014 hailiang All rights reserved. by Jackal
 ------------------------------------------------------------------------*/
var shareData = {
    imgUrl: "http://jackal.sinaapp.com/ds/assets/img/share_ico.jpg",
    timeLineLink: "gq.hailiang.cn/hd",
    tTitle: "快，抓住那小三",
    tContent: ""
};

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

    $(".share").click(function () {
        location.href = "support.html";
    });


    $(".agree").click(function () {
        location.href = "agree.html";
    });
    $(".disagree").click(function () {
        location.href = "disagree.html";
    });

    /*	$(".loginbtn").click(function(){
     location.href = "status.html";
     });*/

    $(".retry").click(function () {
        location.href = "index.html";
    });

    $(".goodguy").click(function () {
        location.href = "goodguy.html";
    });

    $(".badguy").click(function () {
        location.href = "badguy.html";
    });

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
function checkSubmitAll() {
    if ($(".say").val() == "") {
        alert("内容不能为空！")
        $(".say").focus();
        return false;
    }

    if ($(".address").val() == "") {
        alert("地址不能为空！")
        $(".address").focus();
        return false;
    }

    if ($(".phone").val() == "") {
        alert("手机号码不能为空！")
        $(".phone").focus();
        return false;
    }

    if (!$(".phone").val().match(/^1[3|4|5|8][0-9]\d{4,8}$/)) {
        alert("手机号码格式不正确！请重新输入！")
        $(".phone").focus();
        return false;
    }

    return true;
    // location.href = "confirm.html";

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


