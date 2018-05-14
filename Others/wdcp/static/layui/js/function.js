var tab;
layui.config({
	base : '/static/layui/lay/modules/',
	version : new Date().getTime()
}).use([ 'element', 'tab' ], function() {
	var element = layui.element, $ = layui.jquery, layer = layui.layer;
	// tab设置
	tab = layui.tab({
		elem : '.admin-nav-card' // 设置选项卡容器
		,contextMenu : true
		,autoRefresh : true
		,maxWidth :true
	});
	$(document).ready(function() {
		//高亮菜单链接
		$("#admin-side a").click(function(){
			if ($(this).attr("href") != "javascript:;") {
				$("#admin-side a").parent().removeClass("layui-this");
				$(this).parent().addClass("layui-this");
			}
		});
		//将所有链接转入tab
		$("a").click(function() {
			if ($(this).attr("href") != "javascript:;"&&$(this).attr("data-target")!="notab") {
				top.tab.tabAdd({
					href : $(this).attr("href"),
					title : $(this).attr("data-title")?$(this).attr("data-title"):$(this).text()
				});
				return false;
			}
		});
		var $content = $('.admin-nav-card .layui-tab-content');
		$content.height($(document).height() - 125);
		$content.find('iframe').each(function() {
			$(this).height($content.height());
		});
		$(window).on('resize', function() {
			$content.height($(document).height() - 125);
			$content.find('iframe').each(function() {
				$(this).height($content.height());
			});
			// 清除因收起菜单造成的自适应问题
			var docWidth = $(document.body).width();
			if (docWidth == 750) {
				$('#admin-body').attr("style", "");
				$('#admin-footer').attr("style", "");
				$('#admin-side').attr("style", "");
			}
		});
		$('.admin-side-toggle').on('click', function() {
			var sideLeft = $('#admin-side').position().left;
			if (sideLeft === 0) {
				$('#admin-body').css({
					left : '0'
				});
				$('#admin-footer').css({
					left : '0'
				});
				$('#admin-side').css({
					left : '-260px'
				});
			} else {
				$('#admin-body').css({
					left : '200px'
				});
				$('#admin-footer').css({
					left : '200px'
				});
				$('#admin-side').css({
					left : '0'
				});
			}
		});
		$(".site-tree-mobile").click(function() {
			$("body").addClass("site-mobile");
		});
		$(".site-mobile-shade").click(function() {
			$("body").removeClass("site-mobile");
		});
		var tipId;
		$(".tooltip").mouseenter(function() {
			var tips = $(this).attr("data-tips");
			tipId=layer.tips(tips, $(this) , {tips:1,time:0});
		});
		$(".tooltip").mouseleave(function() {
			layer.close(tipId);
		});
	});
});
function act_confirm(msg,url,data,option){
	if(url == ""){
		url = window.location.href;
	}

	layer.confirm(msg, { icon: 3, title: '系统提示' }, function () {
		var layid;
		if(option == "loading"){
			layid = layer.msg('创建中……', {
				icon: 16
				,shade: 0.01
				,time:0
			});
		}
		$.ajax({
			url:url,
			data:data,
			type: 'get',  
			success : function(data){
				layer.close(layid);
				var timer=1000;
				if(data.errCode == 0){
					if(typeof(option)=='number'){
						timer=option;
					}
					layer.msg(data.msg, {icon : 1 , time : timer},function(){
						if(typeof(option) != 'undefined'){
							switch(option){
							case 'reload' : window.location.reload(); break;
							case 'loading' : window.location.reload(); break;
							default : 
								if(typeof(option)=='number'){
									window.location.reload();
								}else{
									window.location.href = option;
								}
							}
						}
						
					});
				}else if(data.errCode == 1){
					layer.msg(data.msg, {icon: 2 , time : 1000});
				}else if(data.errCode == 2){
					layer.msg(data.msg, {icon: 2 , time : 1000},function(){
						top.location.href = "/login";
					});
				}else{
					layer.msg("数据错误", {icon: 2 , time : 1000});
				}
			},
			error : function(data){
				layer.close(layid);
				layer.alert("网络错误",{icon:2 , title : "系统提示"});
			}
		});

    });
}

