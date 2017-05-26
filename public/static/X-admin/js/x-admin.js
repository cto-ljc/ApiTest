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
    	
    	// alert(url);

    	if($('.layui-tab-title li#'+id).length){	//如果不存在该选项卡 则创建 否则切换    		
    		var index = $(".layui-tab-title li").index($('.layui-tab-title li#'+id));
    		element.tabDelete('x-tab', index);    		
    	}

    	res = element.tabAdd('x-tab', {
	        title: title//用于演示
	        ,content: '<iframe frameborder="0" src="'+url+'" class="x-iframe"></iframe>'
	    });

	    element.tabChange('x-tab', $('.layui-tab-title li').length-1);

	    var length = $('.layui-tab-title li').length;
	    $('.layui-tab-title li').eq(length - 1).attr('id',id);	
    	

	    //$('.layui-tab-title li').eq(0).find('i').remove();
	    //切换到第2项（注意序号是从0开始计算）
    	
    	//$('.x-admin-title').append("<li class='layui-this'>"+title+"<i class='layui-icon layui-unselect layui-tab-close'>ဆ</i></li>")
  	});

	// element.on('tab', function(data){
	// 	console.log(this); //当前Tab标题所在的原始DOM元素
	// 	console.log(data.index); //得到当前Tab的所在下标
	// 	console.log(data.elem); //得到当前的Tab大容器
	// });

	
});

