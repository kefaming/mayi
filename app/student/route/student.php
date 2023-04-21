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

// 学生管理
Route::group('index', function () {
    Route::rule('index','Index/index','get');						# 信息列表
    Route::rule('data','Index/ajaxdata','post');				# 获取数据
    Route::rule('create','Index/create','get');				# 添加信息
    Route::rule('save','Index/save','post');					# 保存信息
    Route::rule('edit/<id>','Index/edit','get');				# 修改信息
    Route::rule('update/<id>','Index/update','put');			# 更新信息
    Route::rule('read/<id>','Index/read','get');              # 读取信息
    Route::rule('delete','Index/delete','delete');		# 删除信息
    Route::rule('status','Index/setStatus','post');		# 删除信息
    Route::rule('createall','Index/createAll','get');				# 批量导入
    Route::rule('saveall','Index/saveAll','post');				# 批量保存
	Route::rule('deletes','Index/deletes','get');					# 批量保存
	Route::rule('deleteall','Index/deleteXlsx','post');				# 批量保存
	Route::rule('download','Index/download','get');				# 批量保存
	Route::rule('srcstudent','Index/srcStudent','post');		# 获取学生成绩
	Route::rule('redel','Index/reDel','delete');		# 恢复删除的学生
	Route::rule('kaoshi','Index/setKaoshi','post');		# 删除信息
	Route::rule('bylist','Index/byList','get');                       # 信息列表
    Route::rule('databy','Index/ajaxdataBy','post');              # 获取数据
    Route::rule('dellist','Index/delList','get');                     # 信息列表
    Route::rule('datadel','Index/ajaxdataDel','post');                # 获取数据
    Route::rule('resetpassword/<id>','Index/resetpassword','post');       # 重置教师密码
    Route::rule('srcsfzh','Index/srcSfzh','post');        # 获取学生成绩
    Route::rule('onekaohao/<id>','Index/updateOneKaohao','put');        # 获取学生成绩
});


// 学生成绩列表
Route::group('chengji', function () {
    Route::rule('index/<id>','Chengji/index','get');              # 读取信息
    Route::rule('data','Chengji/ajaxData','post');       # 获取学生成绩列表
    Route::rule('chengjitubiao','Chengji/ajaxOneStudentChengjiTuBiao','post');       # 获取学生成绩列表
    Route::rule('chengjileida','Chengji/ajaxOneStudentChengjiLeiDa','post');       # 获取学生成绩列表
    Route::rule('chengjiyibiao','Chengji/ajaxOneStudentChengjiYiBiao','post');       # 获取学生成绩列表
});

