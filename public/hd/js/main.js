/*------------------------------------------------------------------------
 * Copyright (c) 2014 hailiang All rights reserved. by Jackal
 ------------------------------------------------------------------------*/

var _namespace_microLetter = {}


$(function () {


    $(".indexbg").click(function () {
        $(this).addClass("dismiss");
        //$(this).removeClass("show");
        document.querySelector(".indexbg").addEventListener("webkitAnimationEnd", function () {
            $(this).removeClass("show");
            $(".page").addClass("show");
        })
    });

    $(".mask").click(function () {
        $(this).removeClass("show");
    });

    $(".finish").click(function () {
        checkSubmitAll();
    });

    $(".share").click(function () {
        location.href = "support.html";
    });


    $(".agree").click(function () {
        location.href = "agree.html";
    });
    $(".disagree").click(function () {
        location.href = "disagree.html";
    });

    $(".loginbtn").click(function () {
        location.href = "status.html";

    });

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

    $(".sending").click(function () {
        alert("支持成功！立即开始我的人品测试");
        location.href = "index.html";
    });

    _namespace_microLetter.loadMask();

    $(".mask").click(function () {
        $(this).removeClass("show");
    });

});

// 加载弹出层
_namespace_microLetter.loadMask = function () {
    $("body").append('<div class="mask"><div class="dailog"><h2>人品大挑战</h2><span></span><a href="###"></a></div></div>');
}


function alertView(wrongmessage) {
    $(".mask").addClass("show");
    $(".dailog span").text(wrongmessage);
}


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

    location.href = "confirm.html";

}

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
