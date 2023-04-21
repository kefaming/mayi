<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

// 立项管理
Route::group('lixiang', function () {
	    Route::rule('','Lixiang/index','get');						# 信息列表
	    Route::rule('data','Lixiang/ajaxdata','post');				# 获取数据
	    Route::rule('create','Lixiang/create','get');				# 添加信息
	    Route::rule('save','Lixiang/save','post');					# 保存信息
	    Route::rule('edit/<id>','Lixiang/edit','get');				# 修改信息
	    Route::rule('update/<id>','Lixiang/update','put');			# 更新信息
	    Route::rule('delete','Lixiang/delete','delete');		# 删除信息
	    Route::rule('status','Lixiang/setStatus','post');		# 删除信息
	    Route::rule('list/<lixiang_id>','Lixiang/list','get');		# 课题列表
	    Route::rule('download/<lixiang_id>','Lixiang/outXlsx','get');					# 批量保存
	});

// 课题信息管理
Route::group('info', function () {
	    Route::rule('','KetiInfo/index','get');						# 信息列表
	    Route::rule('data','KetiInfo/ajaxdata','post');				# 获取数据
	    Route::rule('create/<lixiang_id>','KetiInfo/create','get');				# 添加信息
	    Route::rule('save','KetiInfo/save','post');					# 保存信息
	    Route::rule('edit/<id>','KetiInfo/edit','get');				# 修改信息
	    Route::rule('update/<id>','KetiInfo/update','put');			# 更新信息
	    Route::rule('delete','KetiInfo/delete','delete');		# 删除信息
	    Route::rule('status','KetiInfo/setStatus','post');		# 删除信息
	    Route::rule('createall/<lixiang_id>','KetiInfo/createAll','get');				# 批量导入
	    Route::rule('saveall/<lixiang_id>','KetiInfo/saveAll','post');					# 批量保存
	    Route::rule('jieti/<id>','KetiInfo/jieTi','get');					# 批量保存
	    Route::rule('jietiupdate/<id>','KetiInfo/jtUpdate','put');					# 批量保存
	    Route::rule('deljt','KetiInfo/deleteJieti','delete');		# 删除结题信息
	    Route::rule('srckt','KetiInfo/srcInfo','post');				# 获取数据
	    Route::rule('srccy','KetiInfo/srcCy','post');				# 获取数据
	    Route::rule('read/<id>','KetiInfo/read','get');              # 读取信息
	});

// 结题管理
Route::group('jieti', function () {
	    Route::rule('','Jieti/index','get');						# 信息列表
	    Route::rule('data','Jieti/ajaxdata','post');				# 获取数据
	    Route::rule('create','Jieti/create','get');				# 添加信息
	    Route::rule('save','Jieti/save','post');					# 保存信息
	    Route::rule('edit/<id>','Jieti/edit','get');				# 修改信息
	    Route::rule('update/<id>','Jieti/update','put');			# 更新信息
	    Route::rule('delete','Jieti/delete','delete');		# 删除信息
	    Route::rule('status','Jieti/setStatus','post');		# 删除信息
	    Route::rule('list/<jieti_id>','Jieti/list','get');		# 课题列表
	    Route::rule('add/<jieti_id>/<info_id?>','Jieti/jieTi','get');					# 批量保存
	    Route::rule('addsave','Jieti/jtUpdate','put');					# 批量保存
	    Route::rule('deleteinfo','Jieti/infoDelete','delete');					# 批量保存
	    Route::rule('download/<jieti_id>','Jieti/outXlsx','get');					# 批量保存
	});


// 结题管理
Route::group('tongji', function () {
	    Route::rule('','Tongji/index','get');						# 信息列表
	    Route::rule('qgjlx','Tongji/quGejiLixiang','post');				# 获取数据
	    Route::rule('qgdwlx','Tongji/quGeDanweiLixiang','post');				# 获取数据
	    Route::rule('qgjjt','Tongji/quGejiJieti','post');				# 获取数据
	    Route::rule('qgdwjt','Tongji/quGeDanweiJieti','post');				# 获取数据
	});


