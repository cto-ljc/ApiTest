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
	window.$liu = u;
})(window);