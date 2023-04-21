/**
  扩展cjgl模块
  **/

layui.define(['table'], function(exports) {
	//提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
	var table = layui.table
	form = layui.form;

	var obj = {
		// 删除备份
		del: function(url) {
			$.post(
				url,
				function(data) {
					layer.msg(data.msg);
					if (data.val === 1) {
						table.render(options); //获取数据并渲染
					}
				}, "json");
		},


		// 提交备份请求
		post: function() {
			layer.msg("正在发送备份请求...");
			$.post(
				'/system/backup/export',
				'',
				function(data) {
					if (data.code == 1) {
						layer.msg("开始备份，请不要关闭或刷新本页面！");
						layui.backUp.beifen(data.data.tab);
					} else {
						layer.msg(data.msg);
					}
				}, "json");
			return false;
		},


		// 备份
		beifen: function(tab, status) {
			$.ajax({
				url: "/system/backup/export",
				data: tab,
				async: false,
				type: "GET",
				dataType: "json",
				timeout: 0,
				success: function(data) {
					if (data.code == 1) {
						if (!$.isPlainObject(data.data.tab)) {
							layer.msg("备份完成");
							return;
						}
						layui.backUp.beifen(data.data.tab, tab.id != data.data.tab.id);
					} else {
						userTable = table.render(options); //获取数据并渲染
						layer.msg("备份完成");
					}
				}
			});
		},


		// 恢复数据
		back: function(url, a, time) {
			myStatus = $(a).parents("td").prev().children('div');
			console.log(myStatus);
			$(myStatus).text('等待还原');
			$.ajax({
				url: url,
				type: 'POST',
				async: true,
				data: {
					time: time,
				},
				success: function(data) {
					layui.backUp.success(data, url, time);
				},
				error: function(result) {
					layer.msg('数据扔半道，回不来啦。', function() {});
				}
			});

			layer.msg('正在还原数据库，请不要关闭或刷新本页面！');
			return false;
		},


		// 恢复成绩
		success: function(data, url, time) {
			if (data.code == 1) {
				$(myStatus).text(data.msg);
				if (data.data.part) {
					$.ajax({
						url: url,
						type: 'POST',
						async: true,
						data: {
							time: time,
							part: data.data.part,
							start: data.data.start,
						},
						success: function(data) {
							layui.backUp.success(data, url);
						},
						error: function(result) {
							layer.msg('数据扔半道，回不来啦。', function() {});
						}
					});
				} else {
					layer.msg(data.msg);
				}
			} else {
				layer.msg(data.msg);
			}
		},


	};
	//输出test接口
	exports('backUp', obj);
});
