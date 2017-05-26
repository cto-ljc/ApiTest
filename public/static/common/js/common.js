//ajax请求
function post(data,url,callback){
	url = url == undefined  ?  location.href : url;
	//console.log(data);
	
	$.ajax({
		type:"post",
		url:url,
		data:data,
		dataType:'json',
		success: function (data) {			
			if(callback){

				callback(data);				
				return;
			}			
            var msg = data.msg;
            if (data.success) {	
            	var callback_type = data.data.callback_type;
            	layer.open({
	               	title: "温馨提示",
	               	icon: 1,
	               	content: msg,
	               	yes: function(index, layero){
	               		_callback(callback_type);
					    layer.close(index); //如果设定了yes回调，需进行手工关闭						    
				  	}	                   
	            }); 	 
            	
                   	                 
            } else {
               	layer.open({
	               title: "温馨提示",
	               icon: 2,
	               content: msg	                   
	            });               
            }
        },
       	error: function (error) { 
       		console.log(error);
       		layer.open({
               title: "温馨提示",
               icon: 2,
               content: "提交信息出现错误！请稍后重试"	                   
            }); 
       	}
        
	})
	return false;
}

function _callback(type){
	switch(type){
	case 0:
		//console.log('不操作');		
		break;
	case 1:
		//console.log('刷新页面');
		location.reload();		 
		break;
	case 2:		
		parent.location.href = document.referrer;			  
		break;
	default:
		//console.log('未定义，刷新本页');
		location.reload();		 
		break;
	}
}


