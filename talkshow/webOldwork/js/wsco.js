/*

message json:

{"type":["cmd","message"],data:data,callback:servercallback}

*/

var WSCO={
	websocket:null,
	connectioned:false,
	host:"192.168.91.128",
	port:"9501",
	log:true,
	canSend:true,



	init:function(){
		var objThis=this;
		this.websocket=new WebSocket("ws://"+this.host+":"+this.port);
		this.websocket.onopen=function(e){
			if(objThis.log){console.info(":::connection open.");}
			objThis.onopen(e);
			objThis.connectioned=true;
		};
		this.websocket.onmessage=function(e){
			if(objThis.log){console.info(":::got a message:");}
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
		if(this.canSend)
		{
			//message='{"type":"message","data":"'+message+'","serverCallback":"'+serverCallback+'"}';
			this.websocket.send(message);
			if(this.log){console.log(":::send a message:"+message+".");}
			if(clientCallback!==null)
			{
				clientCallback();
			}
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

	},
	onerror:function(message)
	{

	},
	onclose:function(message)
	{

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