<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="public/js/jquery/jquery-1.9.1.min.js"></script>
    <title>Document</title>
</head>
<body>
<form action="http://api.microletter.com/index.php/login">
    <input type="text" name="username"/>
    <input type="text" name="password"/>
    <input type="button" name="btnSubmit" value="登录"/>
</form>
</body>
<script>
    $('input[name=btnSubmit]').click(function () {
        var postData = $('form').serializeArray();
        var url = $('form').attr('action');
        $.post(url, postData, function (respond) {
            console.log(respond);
        }, 'json')
        return false;
    })
</script>
</html>