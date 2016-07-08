var messageObj={
	cmd_login:function(username,password){
		var data='{"username":"'+username+'","password":"'+password+'"}';
		var serverCallback='loginAction.afterLogin';
		var message=messageCreateTool.create('user/login',data,serverCallback,'');
		WSCO.sendMessage(message,null);
	},

	testroute:function(route){
		var serverCallback='loginAction.afterLogin';
		var message=messageCreateTool.create(route,'{}',serverCallback,'');
		WSCO.sendMessage(message,null);
	},






}

var messageCreateTool={
	create:function(route,data,serverCallback,addon)
	{
		if(serverCallback)
		{
			serverCallback=',"serverCallback":"'+serverCallback+'"';
		}
		else
		{
			serverCallback='';
		}
		if(addon && addon!='')
		{
			addon=','+addon;
		}
		else
		{
			addon='';
		}
		return '{"route":"'+route+'","data":'+data+serverCallback+addon+'}';
	},
}