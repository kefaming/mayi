/**
  扩展cjgl模块
  **/

layui.extend({
	// 根路径下的具体路径（xmSelect/xmSelect.js）
	xmSelect: "xm-select"
}).define(['table', 'form', 'xmSelect', 'upload'], function(exports) {
	//提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
	var table = layui.table,
		form = layui.form,
		upload = layui.upload,
		xmSelect = layui.xmSelect;

	var obj = {
		// 创建类别的Select
		categorySelect: function(myid, pid, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/tools/teach/children',
				type: 'POST',
				data: {
					p_id: pid
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


		// 创建类别的Select
		categoryRadio: function(myid, pid, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/tools/teach/children',
				type: 'POST',
				data: {
					p_id: pid
				},
				success: function(result) {
					if (hasNull == true) {
						str = '<input lay-filter="' + myid + '" type="radio" name="' + myid + '" value="" title="无">';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (value != '' && value == el.id) {
							temp = '<input lay-filter="' + myid + '" type="radio" name="' + myid + '" value="' + el.id + '" title="' + el.title + '" checked>';

						} else {
							temp = '<input lay-filter="' + myid + '" type="radio" name="' + myid + '" value="' + el.id + '" title="' + el.title + '">';
						}
						str = str + temp;
					});
					$('#' + myid).append(str);
					form.render('radio');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 选中checkbox
		checkboxCheckedAuth: function(data) {
			pid = $(data.elem).attr('pid');
			cid = $(data.elem).attr('cid');
			check = data.elem.checked;
			editCid(cid, check);
			editPid(pid, check);

			function editCid(cid, check) {
				$("input[pid='" + cid + "']").each(function(i, el) {
					this.checked = check;
					cid = $(this).attr('cid');
					editCid(cid, check);
				});
			}

			function editPid(pid, check) {
				if (check == true) {
					$("input[cid='" + pid + "']").each(function(i, el) {
						this.checked = check;
						pid = $(this).attr('pid');
						editPid(pid, check);
					});
				} else {
					cnt = $("input[pid='" + pid + "']:checked").length;
					if (cnt == 0) {
						$("input[cid='" + pid + "']").each(function(i, el) {
							this.checked = check;
							pid = $(this).attr('pid');
							editPid(pid, check);
						});
					}
				}
			}

			form.render('checkbox');

		},


		// 选中checkbox
		checkboxChecked: function(data) {
			check = data.elem.checked;
			editCid(data.elem, check);
			editPid(data.elem, check);

			function editCid(elem, check) {
				cid = $(elem).attr("cid");
				if (typeof(cid) != "undefined") {
					myCid = $(elem).siblings("input[pid='" + cid + "']");
					layui.each(myCid, function(i, el) {
						this.checked = check;
						// editCid(el);
					})
				}
				return false;
			}

			function editPid(elem, check) {
				pid = $(elem).attr("pid");
				if (typeof(pid) != "undefined") {
					cid = $(elem).attr("cid");
					myPid = $(elem).siblings("input[cid='" + pid + "']");
					if (check == true) {
						layui.each(myPid, function(i, el) {
							this.checked = check;
							// editPid(el, check);
						})
					} else {
						myTongji = $(elem).siblings("input[pid='" + pid + "']:checked");
						if (myTongji.length == 0) {
							layui.each(myPid, function(i, el) {
								this.checked = check;
							})
						}
					}
				}
				return false;
			}

			form.render('checkbox');
		},


		// 创建单位的Select
		schoolSelect: function(myid, low = '班级', high = '其它级', value, kaoshi = '', hasNull = true) {
       $('#' + myid).children().remove();
       $.ajax({
				url: '/system/school/srcschool',
				type: 'POST',
				data: {
					low: low,
					high: high,
					kaoshi: kaoshi
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

		// 查询老师
		searchTeacher: function(id, radio = false, name = '') {
			if (name == '') {
				name = id;
			}
			x = xmSelect.render({
				el: '#' + id,
				name: name,
				autoRow: true,
				toolbar: { show: true },
				filterable: true,
				remoteSearch: true,
				// tips: '请选择教师',
				theme: {
					color: '#1cbbb4',
				},
				prop: {
					name: 'xingming',
					value: 'id',
				},
				// height: '25px',
				size: 'mini',
				radio: radio,
				showCount: 8,
				repeat: false,
				model: {
					label: {
						type: 'text',
						//使用字符串拼接的方式
						text: {
							//左边拼接的字符
							left: '【',
							//右边拼接的字符
							right: '】',
							//中间的分隔符
							separator: '，',
						},
					}
				},
				remoteMethod: function(val, cb, show) {
					//这里如果val为空, 则不触发搜索
					if (!val) {
						return cb([]);
					}

					$.ajax({
						url: '/admin/index/srcteacher',
						type: 'POST',
						data: {
              school_id:$($('#' + id)[0]).attr('school_id'),
              searchval: val,
            },
						success: function(result) {
							if (result.data.length > 0) {
								cb(result.data);
							} else {
								return cb([]);
							}
						},
						error: function(result) {
							layer.msg('数据扔半道啦。', function() {});
						},
					});
				},
			})
			return x;
		},


		// 查询学生
		searchStudent: function(id, radio = false, name = '') {
			if (name == '') {
				name = id;
			}
			var banji_id = '';
			x = xmSelect.render({
				el: '#' + id,
				name: name,
				autoRow: true,
				toolbar: { show: true },
				filterable: true,
				remoteSearch: true,
				// tips: '请选择教师',
				theme: {
					color: '#1cbbb4',
				},
				prop: {
					name: 'xingming',
					value: 'id',
				},
				// height: '25px',
				size: 'mini',
				radio: radio,
				showCount: 8,
				repeat: false,
				model: {
					label: {
						type: 'text',
						//使用字符串拼接的方式
						text: {
							//左边拼接的字符
							left: '【',
							//右边拼接的字符
							right: '】',
							//中间的分隔符
							separator: '，',
						},
					}
				},
				remoteMethod: function(val, cb, show) {

					//这里如果val为空, 则不触发搜索
					if (!val) {
						return cb([]);
					}

					$.ajax({
						url: '/student/index/srcstudent',
						type: 'POST',
						data: {
							searchval: val,
							banji_id: $($('#' + id)[0]).attr('banji_id'),
						},
						success: function(result) {
							if (result.data.length > 0) {
								cb(result.data);
							} else {
								return cb([]);
							}
						},
						error: function(result) {
							layer.msg('数据扔半道啦。', function() {});
						},
					});
				},
			})
			return x;
		},


		// 载入搜索框中已经保存的教师
		loadTeacher: function(obj, url, data) {
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(result) {
					if (result.data.length > 0) {
						data = result.data;
					} else {
						data = [];
					}
					obj.update({
						data:data
					})
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 查询老师
		searchKeti: function(id, name = '') {
			if (name == '') {
				name = id;
			}
			x = xmSelect.render({
				el: '#' + id,
				name: name,
				autoRow: true,
				toolbar: { show: true },
				filterable: true,
				remoteSearch: true,
				// tips: '请选择教师',
				theme: {
					color: '#1cbbb4',
				},
				prop: {
					name: 'srctitle',
					value: 'id',
				},
				// height: '25px',
				size: 'mini',
				radio: true,
				showCount: 8,
				repeat: false,
				model: {
					label: {
						type: 'text',
						//使用字符串拼接的方式
						text: {
							//左边拼接的字符
							left: '【',
							//右边拼接的字符
							right: '】',
							//中间的分隔符
							separator: '，',
						},
					}
				},
				remoteMethod: function(val, cb, show) {
					//这里如果val为空, 则不触发搜索
					if (!val) {
						return cb([]);
					}

					$.ajax({
						url: '/keti/info/srckt',
						type: 'POST',
						data: {
			              searchval: val,
			            },
						success: function(result) {
							if (result.data.length > 0) {
								cb(result.data);
							} else {
								return cb([]);
							}
						},
						error: function(result) {
							layer.msg('数据扔半道啦。', function() {});
						},
					});
				},
				on: function(data){
					//arr:  当前多选已选中的数据
					var arr = data.arr;
					if(arr.length == 1)
					{
						$('#title').val(arr[0].title);
						$('#id').val(arr[0].id);
					}
				},
			})
			return x;
		},


		// 上传图片
		uploadPic: function(uploadId, url, category, serurl, backId) {
			upload.render({
				elem: '#' + uploadId, //绑定元素
				url: url, //上传接口
				done: function(res) {
					if (res.val == 1) {
						$('#' + backId).val(res.url);
						document.getElementById("img").src = "/uploads/" + res.url;
					}
					layer.msg(res.msg);
				},
				data: {
					text: category,
					serurl: serurl
				},
				acceptMime: '.jpg,.jpeg,.png',
				exts: 'jpg|jpeg|png',
				auto: true,
				error: function() {
					//请求异常回调
				}
			});
		},


		// 上传图片
		uploadPicMore: function(uploadId, url, category, serurl) {
			upload.render({
				elem: '#' + uploadId, //绑定元素
				url: url, //改成您自己的上传接口
				async: false,
				done: function(res) {
					layer.msg('上传成功');
					layui.$('#uploadDemoView').removeClass('layui-hide').find('img').attr('src', res.files.file);
				},
				data: {
					text: category,
					serurl: serurl
				},
				multiple: true,
				number: 100,
				acceptMime: '.jpg,.jpeg,.png',
				exts: 'jpg|jpeg|png',
				auto: true,
				error: function() {
					//请求异常回调
					layer.msg('上传错误');
				},
			})
		},


		// 上传电子表格
		uploadXls: function(uploadId, category, serurl, backId) {
			upload.render({
				elem: '#' + uploadId, //绑定元素
				url: '/tools/file/upload', //上传接口
				done: function(res) {
					if (res.val == 1) {
						$('#' + backId).val(res.url);
					}
					layer.msg(res.msg);
				},
				data: {
					category_id: category,
					serurl: serurl
				},
				acceptMime: '.xls,.xlsx',
				exts: 'xls|xlsx',
				auto: true,
				error: function() {
					//请求异常回调
				}
			});
		},


		// 上传文件
		uploadFileMore: function(uploadId, category, serurl, backId) {
			upload.render({
				elem: '#' + uploadId, //绑定元素
				url: '/tools/file/upload', //改成您自己的上传接口
				async: false,
				done: function(res) {
					layer.msg('上传成功');
					// layui.$('#uploadDemoView').removeClass('layui-hide').find('img').attr('src', res.files.file);
					if (res.val == 1) {
						$('#' + backId).val(res.url);
					}
					layer.msg(res.msg);
				},
				data: {
					category_id: category,
					serurl: 'chengji'

				},
				multiple: true,
				acceptMime: '.xls,.xlsx',
				exts: 'xls|xlsx',
				auto: true,
				error: function() {
					//请求异常回调
					layer.msg('上传错误');
				},
			})
		},


		// 放大、缩小图片
		imgMax: function(obj) {
			var max;
			max = $(obj).attr('max');
			if (max == 0) {
				$(obj).css("max-width", "800px");
				$(obj).css("max-height", "1000px");
				$(obj).attr('max', 1);
			} else {
				$(obj).css("max-width", "500px");
				$(obj).css("max-height", "300px");
				$(obj).attr('max', 0);
			}
		},


		// 创建类别的Select
		subjectSelect: function(myid, value = '', kaoshi = '', hasNull = true) {
			$('#' + myid).children().remove();
      		$.ajax({
				url: '/teach/subject/data',
				type: 'POST',
				data: {
					status: 1,
					kaoshi: kaoshi,
					limit:100,
					field:'paixu'
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


		// 创建学科的CheckBox
		subjectCheckbox: function(myid, value = '', kaoshi = '', hasAll = true) {
			$('#' + myid).children().remove();
      		$.ajax({
				url: '/teach/subject/data',
				type: 'POST',
				data: {
					status: 1,
					kaoshi: kaoshi,
					limit: 100
				},
				success: function(result) {
					if (hasAll == true) {
						str = '<input type="checkbox" title="全选" lay-skin="primary" value="" cid="1" lay-filter="mycheackbox">';
					} else {
						str = '';
					}
					temp = "";
					$(result.data).each(function(i, el) {
						if (Array.isArray(value) && $.inArray(el.id.toString(), value)>=0) {
							temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + el.title + '" value="' + el.id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox" checked>';
						} else {
							temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + el.title + '" value="' + el.id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox">';
						}
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


		// 创建班级checkbox
		banjiCheckbox: function(myid, data, value = '', hasAll = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/teach/banji/mybanji',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasAll == true) {
						str = '<input type="checkbox" title="全选" lay-skin="primary" value="" cid="1" lay-filter="mycheackbox">';
					} else {
						str = '';
					}
					
					var data = result.data;
					for (var key in data) {
						temp = "";
						if (value != '' && value == data[key].id) {
							temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + data[key].banTitle + '" value="' + data[key].id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox" checked>';
						} else {
							temp = '<input type="checkbox" name="' + myid + '[]' + '" title="' + data[key].banTitle + '" value="' + data[key].id + '" lay-skin="primary" pid="1" lay-filter="mycheackbox">';
						}
						str = str + temp;
					}

					$('#' + myid).append(str);
					form.render('checkbox');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 创建班级checkbox
		banjiSelect: function(myid, data, value = '', hasAll = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/teach/banji/mybanji',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasAll == true) {
						str = '<option></option>';
					} else {
						str = '';
					}

					var data = result.data; 
					for (var key in data) {
						temp = "";
						if (value != '' && value == data[key].id) {
							temp = '<option value="' + data[key].id + '" paixu="'+ data[key].paixu +'" selected>' + data[key].banTitle + '</option>';
						} else {
							temp = '<option value="' + data[key].id + '" paixu="'+ data[key].paixu +'">' + data[key].banTitle + '</option>';
						}
						str = str + temp;
					}

					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 创建年级的Select
		nanjiSelect: function(myid, data, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/teach/banji/njlist',
				type: 'POST',
				data: data,
				success: function(result) {
					if (hasNull == true) {
						str = '<option value=""></option>';
					} else {
						str = '';
					}
					temp = "";
					layui.each(result.data, function(x, val) {
						if (value != '' && value == x) {
							temp = '<option value="' + x + '" selected>' + val + '</option>';
						} else {
							temp = '<option value="' + x + '">' + val + '</option>';
						}
						str = str + temp;
					})
					$('#' + myid).append(str);
					form.render('select');
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		// 创建学期的Select
		xueqiSelect: function(myid, value = '', hasNull = true) {
			$('#' + myid).children().remove();
      $.ajax({
				url: '/teach/xueqi/srcxueqi',
				type: 'POST',
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

		/**
		 * 使用indexOf判断元素是否存在于数组中
		 * @param {Object} arr 数组
		 * @param {Object} value 元素值
		 */
		 // 判断一个数是否在数组中
		isInArray: function (arr,value){
		    if(arr.indexOf&&typeof(arr.indexOf)=='function'){
		        var index = arr.indexOf(value);
		        if(index >= 0){
		            return true;
		        }
		    }
		    return false;
		},


	};
	//输出test接口
	exports('createInput', obj);
});
