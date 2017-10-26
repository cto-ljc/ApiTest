(function(window){
	var u = {};
	u.debug = true;

	u.log = function(obj){
		if(u.debug){
			console.log(obj);
		}	
	};

	u.btn_loading = function(obj){
		u.log('btn_loading');
		var html = $(obj).html();
		localStorage.setItem(obj, html);
		var load = '<i class="layui-icon">&#xe63d;</i> ';
		$(obj).html(load + html);				
		
	}

	u.btn_loaded = function(obj){
		u.log('btn_loaded');
		var html = localStorage.getItem(obj);
		$(obj).html(html);	
		localStorage.removeItem(obj);  		
	}

	u.post = function(obj,url,data,callback){		
		if($(obj).attr('disabled') == 'true'){
			return false;
		}

	    url = (url == undefined)  ?  location.href : url;
		//console.log(data);
		
		var obj_html = $(obj).html();
		var load = '';
		$.ajax({
			type:"post",
			url:url,
			data:data,
			dataType:'json',
			success: function (data) {
				if(callback){
					callback(data);				
					return false;
				}
	            var msg = data.msg;
	            if (data.success) {	         
	            	layer.msg(msg);                    
	            } else {
	               	layer.msg(msg);           
	            }
	        },
	       	error:function(ret){
	            layer.alert('网络错误',{title:''});	            
	        },
	        beforeSend: function(xhr){               
	            var html = '<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i>'+obj_html;
	            $(obj).html(html);
	            load = layer.load();
	            $(obj).attr('disabled','true');                     
	        },
	        complete: function(){  
	            $(obj).html(obj_html);
	            layer.close(load);
	            $(obj).removeAttr('disabled');                
	        },
	        
		})
		return false;
	}

	u.open = function(url,title,area){
		if(area){
			var open_width = area['width'];
			var open_height = area['height'];
		}else{
			var open_width = 800;
			var open_height = 600;
		}
		
		title = title == undefined ? '' : title;
		layer.open({
	        type: 2,
	        area: [open_width+'px', open_height +'px'],
	        fix: false, //不固定	        
	        shadeClose: true,
	        shade:0.4,
	        title: title,
	        content: url        
	    });     
	}
	window.$intop = u;
})(window);