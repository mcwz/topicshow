<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>websocket</title>
</head>
<body>
<div style="font-size: 12px;line-height: 22px;" id="main">
	<?php
	if(isset($_SESSION['count']))
	$_SESSION['count']=$_SESSION['count']+1;
else
	$_SESSION['count']=1;
echo $_SESSION['count'];
?>
	<div class="sendmessage"><input type="text" class="message" /><button class="send">发送</button></div>
	<div class="logbox"></div>
</div>
	<script type="text/javascript" src="js/wsco.js"></script>
	<script type="text/javascript" src="js/message.js"></script>
	<script type="text/javascript">
		WSCO.init();
		var sendmessage=document.querySelector(".send");
		sendmessage.onclick=function(e){
			var username=document.querySelector(".message").value;
			var password="password";
			//messageObj.cmd_login(username,password);
			messageObj.testroute(username);
		};


		var loginAction={
			afterLogin:function(data){
				console.info("server after login.");
				console.info(data);
			}
		};


		var callbackObj={
			callback:function()
			{
				console.info("calling back");
			},
			serverCallback1:function()
			{
				console.info("server call back");
			}
		};


	</script>
</body>
</html>