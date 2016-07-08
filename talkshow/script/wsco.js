/*

message json:

{"type":["cmd","message"],data:data,callback:servercallback}

*/

var WSCO={
	websocket:null,
	host:"192.168.91.128",
	port:"9501",
	log:true,
	canSend:true,
	messageQueue:null,
	autoRefresh:true,
	autoRefreshFlag:null,
	topic:'',



	init:function(topic){
		this.topic=topic;
		var objThis=this;
		this.messageQueue=new Queue();
		this.websocket=new WebSocket("ws://"+this.host+":"+this.port);
		this.websocket.onopen=function(e){
			if(objThis.log){console.info(":::connection open.");}
			objThis.onopen(e);
		};
		this.websocket.onmessage=function(e){
			//if(objThis.log){console.info(":::got a message:");}
			objThis.onmessage(e);
		};
		this.websocket.onerror=function(e)
		{
			if(objThis.log){console.info(":::an error ourrer.");}
			objThis.onerror(e);
		};
		this.websocket.onclose=function(e)
		{
			if(objThis.log){console.info(":::connection close.");}
			objThis.onclose(e);
		};
	},
	sendMessage:function(message,clientCallback)
	{
		if(this.canSend && this.websocket.readyState==WebSocket.OPEN)
		{
			this.websocket.send(message);
			if(this.log){console.log(":::send a message:"+message+".");}


			if(clientCallback!==null && clientCallback!=undefined)
			{
				clientCallback();
			}
		}
		else
		{
			this.messageQueue.EnQueue({"message":message,"clientCallback":clientCallback});
		}
		
	},
	onmessage:function(message)
	{
		message=WSCO_tool.parseJSON(message.data);
		if(message.serverCallback)
		{
			var serverCallback=message.serverCallback;
			WSCO_tool.callback(serverCallback,message);
		}
		if(this.log){console.info(":::got a message:");console.info(message)}
	},
	onopen:function(message)
	{
		//加入讨论主题
		if(this.topic!="")
		{
			var message=messageCreateTool.create('command/jointopic','{"topic":"'+this.topic+'"}','','');
			WSCO.sendMessage(message,null);
		}


		//检查未发送队列，重新发送
		if(this.messageQueue.GetSize()>0)
		{
			while((aMessage=this.messageQueue.DeQueue())!=null)
			{
				WSCO.sendMessage(aMessage.message,aMessage.clientCallback);
			}
		}


		if(this.autoRefresh)
		{
			this.autoRefreshFlag=setInterval(function(){
				if(WSCO.websocket.readyState==WebSocket.OPEN)
				{
					var message=messageCreateTool.create('command/refresh','[]','','');
					WSCO.sendMessage(message,null);
				}
            },1000*5*60);
		}

	},
	onerror:function(message)
	{

	},
	onclose:function(message)
	{
		if(this.autoRefresh)
		{
			this.autoRefreshFlag=null;
		}
	}

};

var WSCO_tool={
	whitespace : "[\\x20\\t\\r\\n\\f]",
	rtrim : new RegExp( "^" + this.whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" + this.whitespace + "+$", "g" ),

	parseJSON:function(data){
		//borrowed from jQuery
		if ( typeof data !== "string" || !data ) {
		return null;
 		}
 		data=this.trim(data);
 		if ( window.JSON && window.JSON.parse ) {
			return window.JSON.parse( data );
		}
		if ( rvalidchars.test( data.replace( rvalidescape, "@" ).replace( rvalidtokens, "]" ).replace( rvalidbraces, "")) ) {return ( new Function( "return " + data ) )();
		if(this.log){console.info(":::Invalid JSON: " + data )};

}
	},
	trim: function( text ) {
		return text == null ?"" :( text + "" ).replace( this.rtrim, "" );
	},
	callback:function(func,messageData){

		if(func.indexOf(".")>0)
		{//是obj.function调用形式
			var callobjstr=func.substring(0,func.indexOf("."));
			var callfuncstr=func.substring(func.indexOf(".")+1,func.length);

			for ( var h in window ) {
	        if ( h == callobjstr ){
		            var o = window[h];
		            if ( typeof o == 'object') {
		                for ( var hi in o )
		                {
		                	if(hi==callfuncstr)
		                	{
		                		var f=o[hi];
		                		if(typeof f=='function')
		                		{
		                			try {
					                    f(messageData);
					                }catch(e){
					                    //alert(e);
					                }
		                		}
		                	}
		                }


		                
		            }
		        }
		    }
		}
		else
		{
			for ( var h in window ) {
		        if ( h == func ){
		            var f = window[h];
		            if ( typeof f == 'function') {
		                try {
		                    f(messageData);
		                }catch(e){
		                    //alert(e);
		                }
		            }
		        }

		    }
		}
		


	}
};






function Queue(){
    //存储元素数组
    var aElement = new Array();
    /*
    * @brief: 元素入队
    * @param: vElement元素列表
    * @return: 返回当前队列元素个数
    * @remark: 1.EnQueue方法参数可以多个
    *    2.参数为空时返回-1
    */
    Queue.prototype.EnQueue = function(vElement){
        if (arguments.length == 0)
            return - 1;
        //元素入队
        for (var i = 0; i < arguments.length; i++){
            aElement.push(arguments[i]);
        }
        return aElement.length;
    }
    /*
    * @brief: 元素出队
    * @return: vElement
    * @remark: 当队列元素为空时,返回null
    */
    Queue.prototype.DeQueue = function(){
        if (aElement.length == 0)
            return null;
        else
            return aElement.shift();
 
    }
    /*
    * @brief: 获取队列元素个数
    * @return: 元素个数
    */
    Queue.prototype.GetSize = function(){
        return aElement.length;
    }
    /*
    * @brief: 返回队头素值
    * @return: vElement
    * @remark: 若队列为空则返回null
    */
    Queue.prototype.GetHead = function(){
        if (aElement.length == 0)
            return null;
        else
            return aElement[0];
    }
    /*
    * @brief: 返回队尾素值
    * @return: vElement
    * @remark: 若队列为空则返回null
    */
    Queue.prototype.GetEnd = function(){
        if (aElement.length == 0)
            return null;
        else
            return aElement[aElement.length - 1];
    }
    /*
    * @brief: 将队列置空
    */
    Queue.prototype.MakeEmpty = function(){
        aElement.length = 0;
    }
    /*
    * @brief: 判断队列是否为空
    * @return: 队列为空返回true,否则返回false
    */
    Queue.prototype.IsEmpty = function(){
        if (aElement.length == 0)
            return true;
        else
            return false;
    }
    /*
    * @brief: 将队列元素转化为字符串
    * @return: 队列元素字符串
    */
    Queue.prototype.toString = function(){
        var sResult = (aElement.reverse()).toString();
        aElement.reverse()
        return sResult;
    }
}