layui.use(['element'], function(){
	$ = layui.jquery;
  	element = layui.element(); 
  
  //导航的hover效果、二级菜单等功能，需要依赖element模块
  // 侧边栏点击隐藏兄弟元素
	$('.layui-nav-item').click(function(event) {
		$(this).siblings().removeClass('layui-nav-itemed');
	});

	$('.layui-tab-title li').eq(0).find('i').remove();

	height = $('.layui-layout-admin .site-demo').height();
	$('.layui-layout-admin .site-demo').height(height-100);


  	//监听导航点击
  	element.on('nav(side)', function(elem){
    	title = elem.find('cite').text();
    	url = elem.find('a').attr('_href');
    	id = elem.find('cite').text();
    	
    	//console.log($('.layui-tab-title li#'+id).length == 0);

    	if($('.layui-tab-title li#'+id).length){	//如果不存在该选项卡 则创建 否则切换    		
    		var index = $(".layui-tab-title li").index($('.layui-tab-title li#'+id));
    		element.tabDelete('x-tab', id);  
    	}

    	res = element.tabAdd('x-tab', {
	        title: title//用于演示
	        ,content: '<iframe frameborder="0" src="'+url+'" class="x-iframe"></iframe>',
	        id:id
	    });

	    element.tabChange('x-tab', $('.layui-tab-title li').length-1);

	    var length = $('.layui-tab-title li').length;
	    $('.layui-tab-title li').eq(length - 1).attr('id',id);	
    	
	    element.tabChange('x-tab', id);	    
  	});

	
});

