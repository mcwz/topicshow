var messageObj={
	// cmd_login:function(username,password){
	// 	var data='{"username":"'+username+'","password":"'+password+'"}';
	// 	var serverCallback='loginAction.afterLogin';
	// 	var message=messageCreateTool.create('user/login',data,serverCallback,'');
	// 	WSCO.sendMessage(message,null);
	// },

	// testroute:function(route){
	// 	var serverCallback='loginAction.afterLogin';
	// 	var message=messageCreateTool.create(route,'{}',serverCallback,'');
	// 	WSCO.sendMessage(message,null);
	// },


	// checkLogin:function()
	// {//temp
	// 	var data='{}';
	// 	var serverCallback='loginAction.afterLogin';
	// 	var message=messageCreateTool.create('a/b',data,serverCallback,'');
	// 	WSCO.sendMessage(message,null);
	// },

	sendTopicMessage:function(topic,subtopic,message)
	{
		WSCO.sendMessage(messageCreateTool.createTopicMessage(topic,message,subtopic),null);
	}


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
	createTopicMessage:function(topic,message,subtopic)
	{
		var messageBody='{"topic":"'+topic+'","subtopic":"'+subtopic+'","message":"'+message+'"}';
		return '{"route":"message/talk","data":'+messageBody+',"serverCallback":"reciveMessage"}';
	}
}




