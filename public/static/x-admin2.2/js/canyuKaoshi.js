/**
  扩展cjgl模块
  **/

layui.define(['table', 'form', 'upload'], function(exports) {
	//提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
	var table = layui.table,
		form = layui.form,
		upload = layui.upload;

	var obj = {
		gradeSelect: function(myid, kaoshi_id, value = '', hasNull = true) {
      $('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/kscy/grade',
				type: 'POST',
				data: {
					kaoshi_id: kaoshi_id
				},
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<option value="' + el.ruxuenian + '" selected>' + el.nianjiname + '</option>';
						} else {
							temp = '<option value="' + el.ruxuenian + '">' + el.nianjiname + '</option>';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 参与本次考试学科
		subjectSelect: function(myid, data, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/kscy/subject',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<option value="' + el.id + '" selected>' + el.title + '</option>';
						} else {
							temp = '<option value="' + el.id + '">' + el.title + '</option>';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 已经参与本次考试学科
		subjectCheckbox: function(myid, data, hasAll = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/kscy/subject',
				type: 'POST',
				data: data,
				success: function(result) {

					if (hasAll == true) {
						str = '<input type="checkbox" title="全选" lay-skin="primary" value="" cid="1" lay-filter="mycheackbox">';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + el.title + '" value="' + el.id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox">';
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('checkbox');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 参与本次考试班级
		banjiSelect: function(myid, data, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/kscy/class',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<option value="' + el.id + '" selected>' + el.banTitle + '</option>';
						} else {
							temp = '<option value="' + el.id + '">' + el.banTitle + '</option>';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 已经参与本次考试班级
		banjiCheckbox: function(myid, data, hasAll = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/kscy/class',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasAll == true) {
						str = '<input type="checkbox" title="全选" lay-skin="primary" value="" cid="1" lay-filter="mycheackbox">';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + el.banTitle + '" value="' + el.id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox">';
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('checkbox');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 参与本次考试学校
		schoolSelect: function(myid, kaoshi_id, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/kscy/school',
				type: 'POST',
				data: {
					kaoshi_id: kaoshi_id
				},
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<option value="' + el.id + '" selected>' + el.title + '</option>';
						} else {
							temp = '<option value="' + el.id + '">' + el.title + '</option>';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 获取可以编辑的考试
		kaoshiSelect: function(myid, hasNull = true) {
       $('#' + myid).children().remove();
       $.ajax({
				url: '/kaoshi/index/srceditkaoshi',
				type: 'POST',
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						temp = '<option value="' + el.id + '">' + el.title + '</option>';
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 可以录入本次考试班级
		lrBanjiCheckbox: function(myid, data, hasAll = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/lrfg/class',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasAll == true) {
						str = '<input type="checkbox" title="全选" lay-skin="primary" value="" cid="1" lay-filter="mycheackbox">';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + el.banTitle + '" value="' + el.id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox">';
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('checkbox');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 参与本次考试学科
		lrSubjectSelect: function(myid, data, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/lrfg/subject',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<option value="' + el.id + '" selected>' + el.title + '</option>';
						} else {
							temp = '<option value="' + el.id + '">' + el.title + '</option>';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 参与本次考试学科
		lrSubjectCheckbox: function(myid, data, hasAll = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/kaoshi/lrfg/subject',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasAll == true) {
						str = '<input type="checkbox" title="全选" lay-skin="primary" value="" cid="1" lay-filter="mycheackbox">';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + el.title + '" value="' + el.id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox">';
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('checkbox');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 班级统计结果学科
		btjSubjectSelect: function(myid, data, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/chengji/bjtj/subject',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<option value="' + el.id + '" selected>' + el.title + '</option>';
						} else {
							temp = '<option value="' + el.id + '">' + el.title + '</option>';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},

	};
	//输出test接口
	exports('canyuKaoshi', obj);
});
