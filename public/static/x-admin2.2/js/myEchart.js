/**
  扩展cjgl模块
  **/

layui.extend({
	echarts: 'echarts/echarts'
}).define(['echarts'], function(exports) {
	//提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
	var echarts = layui.echarts;

	var obj = {

		/**
		 * 异步获取图表中的数据
		 *
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param val 图表标题
		 * @return array 返回类型
		 */
		tiaoxing: function(id, url, val, title) {
			var myTiaoxing = echarts.init(document.getElementById(id), 'infographic');
			$.post(url, val).done(function(data) {
				myTiaoxing.setOption({
					title: {
						text: title,
						left: 'center',
					},
					legend: {
						top: '10%',
						data:data.legend,
					},
					grid: {
						left: '10%',
						top: '20%',
						right: '10%',
						bottom: '15%'
					},
					tooltip: {
						show: true,
						axisPointer: {
							type: 'cross'
						}
					},
					toolbox: {
						id: id,
						show: true,
						orient: 'horizontal',
						showTitle: true,
						feature: {
							restore: { //重置
								show: true
							},
							saveAsImage: { //保存文件
								show: true
							},
						},
					},
					dataset: {
						source: data.data
					},
		           xAxis: data.xAxis,
		           yAxis: data.yAxis,
					dataZoom: [{
							type: 'inside',
							start: 0,
							end: 100
						},
						{
							show: true,
							height: 20,
							type: 'slider',
							top: '90%',
							xAxisIndex: [0],
							start: 0,
							end: 100
						}
					],
					series: data.series,
				});
			});
		},


		/**
		 * 异步获取箱线图表中的数据
		 *
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param title 图表标题
		 * @return array 返回类型
		 */
		xiangti: function(id, url, val, title) {
			$.post(url, val).done(function(data) {
				axisData = data.axisData;
				category = data.category;
				boxData = data.boxData;
				myseries = Array();

				for (var i in category) {
					myseries.push({
						'name': category[i],
						'type': 'boxplot',
						'data': boxData[i],
						'tooltip': { formatter: layui.myEchart.formatter },
					});
				}

				// 设置初始值
				option = {
					title: {
						text: title,
						left: 'center',
					},
					legend: {
						top: '10%',
						// data:
					},
					tooltip: {
						trigger: 'item',
						axisPointer: {
							type: 'shadow'
						}
					},
					toolbox: {
						id: id,
						show: true,
						orient: 'horizontal',
						showTitle: true,
						feature: {
							restore: { //重置
								show: true
							},
							saveAsImage: { //保存文件
								show: true
							},
						},
					},
					grid: {
						left: '10%',
						top: '20%',
						right: '10%',
						bottom: '15%'
					},
					xAxis: {
						type: 'category',
						data: axisData,
						boundaryGap: true,
						nameGap: 30,
						splitArea: {
							show: true
						},
						axisLabel: {
							// formatter: 'expr {value}'
						},
						splitLine: {
							show: false
						}
					},
					yAxis: {
						type: 'value',
						name: '得分',
						// min: -400,
						// max: 600,
						splitArea: {
							show: false
						}
					},
					dataZoom: [{
							type: 'inside',
							start: 0,
							end: 100
						},
						{
							show: true,
							height: 20,
							type: 'slider',
							top: '90%',
							xAxisIndex: [0],
							start: 0,
							end: 100
						}
					],
					series: myseries,
				};

				var myXiangti = echarts.init(document.getElementById(id), 'infographic');
				myXiangti.setOption(option);
			});
		},


		/**
		 * 异步获取箱线图表中的数据
		 *
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param title 图表标题
		 * @return array 返回类型
		 */
		fenshuduan: function(id, url, val, title) {
			// var colors = ['#5793f3', '#d14a61', '#675bba'];
			$.post(url, val).done(function(data) {

				// 设置初始值
				option = {
					tooltip: {
						show: true,
						trigger: 'axis',
					},
					legend: {
						top: '10%',
						// data:data.legend
					},
					title: {
						text: title,
						left: 'center',
					},
					grid: {
						left: '10%',
						top: '20%',
						right: '10%',
						bottom: '15%'
					},
					toolbox: {
						id: id,
						show: true,
						orient: 'horizontal',
						showTitle: true,
						feature: {
							restore: { //重置
								show: true
							},
							saveAsImage: { //保存文件
								show: true
							},
						},
					},
					xAxis: {
						type: 'category',
						boundaryGap: true,
						nameGap: 30,
						splitArea: {
							show: true
						},
						splitLine: {
							show: false
						},
						data: data.xAxis
					},
					yAxis: {
						type: 'value',
						name: '得分',
						splitArea: {
							show: false
						}
					},
					dataZoom: [{
							type: 'inside',
							start: 0,
							end: 100
						},
						{
							show: true,
							height: 20,
							type: 'slider',
							top: '90%',
							xAxisIndex: [0],
							start: 0,
							end: 100
						}
					],
					series: {
						data: data.series,
						type: 'line',
						// smooth: true
					},
				};

				var myFenshuduan = echarts.init(document.getElementById(id), 'infographic');
				myFenshuduan.setOption(option);
			});
		},


		/**
		 * 异步获取图表中的数据
		 *
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param val 图表标题
		 * @return array 返回类型
		 */
		zhexian: function(id, url, val, title) {
			var myZhexian = echarts.init(document.getElementById(id));
			$.post(url, val).done(function(data) {
				myZhexian.setOption({
					title: {
						text: title
					},
					tooltip: {
						trigger: 'axis'
					},
					legend: {
						data: data.legend
					},
					grid: {
						left: '3%',
						right: '4%',
						bottom: '3%',
						containLabel: true
					},
					toolbox: {
						feature: {
							saveAsImage: {}
						}
					},
					xAxis: {
						type: 'category',
						boundaryGap: false,
						data: data.xAxis,
					},
					yAxis: {
						type: 'value'
					},
					series: data.series,
				});
			});
		},


		/**
		 * 异步获取图表中的数据
		 *
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param val 图表标题
		 * @return array 返回类型
		 */
		leida: function(url, val, title) {
			var myLeiDa = echarts.init(document.getElementById('leida'));
			$.post(url, val).done(function(data) {
				myLeiDa.setOption({
					title: {
						text: title,
					},
					tooltip: {},
					legend: {
						data: data.legend,
						top: 40,
						left: 0,
						orient: 'vertical',
					},
					grid: {
						left: '10%',
						right: '10%',
						top: '30%',
						bottom: '10%',
						height: 'auto',
					},
					toolbox: {
						id: 'leida',
						show: true,
						orient: 'horizontal',
						showTitle: true,
						feature: {
							saveAsImage: { //保存文件
								show: true
							},
						},
					},
					radar: {
						// shape: 'circle',
						name: {
							textStyle: {
								color: '#fff',
								backgroundColor: '#999',
								borderRadius: 3,
								padding: [3, 5]
							}
						},
						indicator: data.indicator
					},
					series: data.series
				})
			});
		},


		/**
		 * 异步获取图表中的数据
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param val 图表标题
		 * @return array 返回类型
		 */
		yibiao: function(url, val, title) {
			var myYiBiao = echarts.init(document.getElementById('yibiao'));
			$.post(url, val).done(function(data) {
				myYiBiao.setOption({
					title: {
						text: title,
					},
					tooltip: {},
					toolbox: {
						id: 'yibiao',
						show: true,
						orient: 'horizontal',
						showTitle: true,
						feature: {
							saveAsImage: { //保存文件
								show: true
							},
						},
					},
					series: {
						name: data.series.name,
						type: data.series.type,
						data: data.series.data,
						detail: { formatter: data.series.data[0].value + '%' }
					}
				})
			});
		},


		/**
		 * 异步获取图表中的数据
		 * @access public
		 * @param id 要添加数据的div
		 * @param url 异步地址
		 * @param val 异步时携带的参数
		 * @param val 图表标题
		 * @return array 返回类型
		 */
		pubu: function(id, url, val, title) {
			var myPubu = echarts.init(document.getElementById(id));
			$.post(url, val).done(function(data) {
				myPubu.setOption({
					title: {
						text: title,
						subtext: '',
					},
					tooltip: {
						trigger: 'axis',
				        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
				            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
				        },
				        formatter: function (params) {
				            var tar = params[1];
				            return tar.name + '<br/>' + tar.seriesName + ' : ' + tar.value;
				        }
					},
					grid: {
				        left: '3%',
				        right: '4%',
				        bottom: '3%',
				        containLabel: true
				    },
				    xAxis: {
				        type: 'category',
				        splitLine: {show: false},
				        // data: ['总费用', '房租', '水电费', '交通费', '伙食费', '日用品数']
				        data:data.xAxis
				    },
				    yAxis: {
				        type: 'value'
				    },
					series: [
				        {
				            name: '辅助',
				            type: 'bar',
				            stack: '总量',
				            itemStyle: {
				                barBorderColor: 'rgba(0,0,0,0)',
				                color: 'rgba(0,0,0,0)'
				            },
				            emphasis: {
				                itemStyle: {
				                    barBorderColor: 'rgba(0,0,0,0)',
				                    color: 'rgba(0,0,0,0)'
				                }
				            },
				            data: data.fuzhu
				        },
				        {
				            name: data.dataname,
				            type: 'bar',
				            stack: '总量',
				            label: {
				                show: true,
				                position: 'inside'
				            },
				            data: data.data
				        }
				    ]
				})
			});
		},


		// 获取学生成绩报告
		baogao:function(kaohao_id) {
			$.ajax({
				url: '/tools/onestudentchengji/baogao',
				type: 'POST',
				data: {
					'kaohao_id': kaohao_id
				},
				success: function(result) {
					if (result.val == 1) {
						$('#baogao').text(result.baogao);
					} else {
						layer.msg(result.msg);
					}
				},
				error: function(result) {
					layer.msg('数据扔半道啦。', function() {});
				},
			});
		},


		formatter: function(param) {
			return [
				param.name,
				'最低分: ' + param.data[1],
				'25%: ' + param.data[2],
				'50%: ' + param.data[3],
				'75%: ' + param.data[4],
				'最高: ' + param.data[5]
			].join('<br/>');
		},

	};
	//输出test接口
	exports('myEchart', obj);
});
