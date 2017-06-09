//添加body里面的tab
function tabAdd(elem){
    var element = layui.element();

    title = elem.find('cite').text();
    url = elem.find('a').attr('_href');
    id = elem.find('cite').text();
    
    console.log(url);

    if($('.layui-tab-title li#'+id).length){    //如果不存在该选项卡 则创建 否则切换            
        var index = $(".layui-tab-title li").index($('.layui-tab-title li#'+id));
        element.tabDelete('frame-tab', id);  
    }

    res = element.tabAdd('frame-tab', {
        title: title
        ,content: '<iframe frameborder="0" src="'+url+'" class="x-iframe"></iframe>',
        id:id
    });

    element.tabChange('frame-tab', $('.layui-tab-title li').length-1);

    var length = $('.layui-tab-title li').length;
    $('.layui-tab-title li').eq(length - 1).attr('id',id);  
    
    element.tabChange('frame-tab', id);   
}

//初始化侧边栏
function init_side(){
    var side = location.hash.replace(/^#/, '');
    side = side.replace("#","");
    side = side.split("/"); 
    var item_id = side[0];
    var child_id = side[1];
    if(!(item_id && child_id)){
        return;
    }    

    $('.layui-side .layui-nav li').removeClass('layui-nav-itemed');
    $('.layui-side .layui-nav li[item-id="'+item_id+'"]').addClass('layui-nav-itemed');
    $('.layui-side .layui-nav li dd').removeClass('layui-this');
    $('.layui-side .layui-nav li[item-id="'+item_id+'"] dd[child-id="'+child_id+'"]').addClass('layui-this');
    $('.layui-side .layui-nav li[item-id="'+item_id+'"] dd[child-id="'+child_id+'"] a').click();
    
    var elem = $('.layui-side .layui-nav li[item-id="'+item_id+'"] dd[child-id="'+child_id+'"]');
    tabAdd(elem);
}

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


