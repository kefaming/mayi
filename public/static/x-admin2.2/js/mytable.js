/**
  扩展cjgl模块
  **/

layui.define(['table', 'form'], function(exports) {
	//提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
	var table = layui.table,
		form = layui.form;

	var obj = {
		// 删除单条记录
		del: function(obj, url) {
			layer.confirm('确认要删除吗？', function(index) {
				$.ajax({
					url: url,
					type: 'DELETE',
					data: {
						id: obj.data.id
					},
					success: function(result) {
						if (result.val == 1) {
							obj.del();
							layer.msg(result.msg);
						} else {
							layer.msg(result.msg, function() {});
						}
					},
					error: function(result) {
						layer.msg('数据扔半道啦。', function() {});
					},
				});
			});
		},


		// 删除全部
		delAll: function(obj, url, tableid) {
			//判断是否选择数据
			if (obj.data.length == 0) {
				parent.layer.msg('请先选择要删除的数据行！');
				return false;
			}
			layer.confirm('确定要删除吗？', function() {
				// 捉一下被选中的数据
				var ids = "";
				$(obj.data).each(function(i, el) {
					ids = ids + "," + el.id;
				});
				ids = ids.substr(1, ids.length);

				// 到服务器去删除数据。
				$.ajax({
					url: url,
					type: 'DELETE',
					data: {
						id: ids
					},
					complete: function(result) {

					},
					success: function(result) {
						if (result.val == 1) {
							table.reload(tableid, {});
						}
						layer.msg(result.msg);
					},
					error: function(result) {
						layer.msg('数据扔半道，回不来啦。');
					},
				});
			});
		},


		// 开关操作
		onoff: function(data) {
			var obj = {};
			obj.msg = $(data.elem).attr('lay-text').split('|');
			obj.url = $(data.elem).attr('myurl');
			obj.val = {
				'id': data.elem.id,
				'value': Number(data.elem.checked),
				'ziduan': $(data.elem).attr('lay-filter')
			};
			title = obj.msg[1 - obj.val.value];
			layer.confirm('确认要修改成【' + title + '】吗？', function() {
				$.ajax({
					url: obj.url,
					type: 'POST',
					data: obj.val,
					success: function(result) {
						if (result.val == 1) {
							layer.msg('已修改成：' + title);
						} else {
							if (obj.val.value == 1) {
								data.elem.checked = false;

							} else {
								data.elem.checked = true;
							}
							form.render();
							layer.msg(result.msg);
						}
					},
					error: function(result) {
						layer.msg('数据扔半道啦。', function() {});
					}
				});
			}, function() {
				if (obj.val.value == 1) {
					data.elem.checked = false;

				} else {
					data.elem.checked = true;
				}
				form.render();
			});
		},


		// 提交表单数据
		saveForm: function(url, type, formData) {
			$.ajax({
				url: url,
				type: type,
				data: formData,
				dataType: 'json',
				success: function(result) {
					if (result.val == 1) {
						// layer.msg(result.msg);
						//先得到当前iframe层的索引
						var index = parent.layer.getFrameIndex(window.name);
						//再执行关闭
						if (parent.layui.table) {
							parent.layui.table.reload('mytable');
						}
						// 关闭表单并跳出提示
						parent.layer.close(index);
						parent.layer.msg(result.msg);
					} else {
						layer.msg(result.msg);
					}
				},
				error: function(xhr, status, error) {
					layer.msg('数据处理错误', {
						icon: 2,
						time: 2000 //2秒关闭（如果不配置，默认是3秒）
					});
				}
			});
		},


		// 提交批量上传数据
		saveFormMore: function(url, type, formData) {
			$.ajax({
				title: '导入结果',
				url: url,
				type: type,
				data: formData,
				dataType: 'json',
				success: function(result) {
					if (result.val == 1) {
						data = result.data;
						var err = '';
						for (var i = 0; i < data.err.length; i++) {
							err = err + data.err[i] + '<br>'
						}
						parent.layer.open({
						  type: 1, 
						  offset: 'auto',
						  area: ['500px', '300px'],
						  content: data.success + '<br>' + err //这里content是一个普通的String
						});
					} else {
						layer.msg(result.msg);
					}
				},
				error: function(xhr, status, error) {
					layer.msg('数据处理错误', {
						icon: 2,
						time: 2000 //2秒关闭（如果不配置，默认是3秒）
					});
				}
			});
		},


		// 恢复单条记录
		redel: function(obj, url) {
			layer.confirm('确认要恢复删除吗？', function(index) {
				$.ajax({
					url: url,
					type: 'DELETE',
					data: {
						id: obj.data.id
					},
					success: function(result) {
						if (result.val == 1) {
							obj.del();
							layer.msg(result.msg);
						} else {
							layer.msg(result.msg, function() {});
						}
					},
					error: function(result) {
						layer.msg('数据扔半道啦。', function() {});
					},
				});
			});
		},

		// 重置密码
		resetpassword: function(xingming, url) {
			layer.confirm('确认要重置' + xingming + '的密码为“123456”吗？', function(index) {
				$.ajax({
					url: url,
					type: 'POST',
					success: function(result) {
						layer.msg(result.msg);
					},
					error: function(result) {
						layer.msg('数据扔半道啦。', function() {});
					},
				});
			});
		},

		// // 表格重载
		// reLoadTable: function(formname, tableID, mydata = {}) {
		// 	var formval = this.getSearchVal(formname);
		// 	var wheredata = $.extend(formval, mydata);
		// 	table.reload(tableID, {
		// 		where: formval,
		// 		done: function() {
		// 			for (x in formval) {
		// 				delete this.where[x];
		// 			}
		// 		},
		// 		page: {
		// 			curr: 1
		// 		}
		// 	});
		// },
	};
	//输出test接口
	exports('mytable', obj);
});
