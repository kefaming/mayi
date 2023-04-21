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


// 成绩管理
Route::group('index', function () {
    Route::rule('list/<kaoshi_id>','Index/index','get');				# 成绩列表
    Route::rule('data','Index/ajaxData','post');			# 成绩获取
    Route::rule('readcjadd/<kaohao>','Index/readAdd','get');				# 成绩录入列表
    Route::rule('dataadd','Index/ajaxaddinfo','post');				# 成绩录入列表数据
    Route::rule('delete','Index/delete','delete');				# 成绩删除
    Route::rule('deletecjs/<kaoshi_id>','Index/deletecjs','get');				# 成绩删除
    Route::rule('deletecjmore','Index/deletecjmore','post');				# 成绩删除
	Route::rule('dwchengji/<kaoshi_id>','Index/dwChengji','get');				# 成绩删除
	Route::rule('dwxlsx','Index/dwchengjixlsx','post');				# 成绩删除
	Route::rule('dwchengjitiao/<kaoshi_id>','Index/dwChengjitiao','get');				# 成绩删除
	Route::rule('dwcjtiaoxlsx','Index/dwchengjitiaoxlsx','post');				# 成绩删除
    Route::rule('status','Index/setStatus','post');     # 删除信息
});


// 成绩统计
Route::group('tongji', function () {
	
    // Route::rule('editdfl/<kaoshi_id>','Tongji/editDefenLv','get');           # 扫码界面
    Route::rule('editdfl','Tongji/updateDefenLv','post');           # 重新计算得分率
    Route::rule('biaozhunfen','Tongji/biaoZhunFen','post');           # 计算标准分
});


// 班级统计
Route::group('bjtj', function () {
    Route::rule('biaoge/<kaoshi_id>','Bjtongji/biaoge','get');			# 班级表格
    Route::rule('data','Bjtongji/ajaxData','post');			# 扫码界面
    Route::rule('dwbiaoge/<kaoshi_id>','Bjtongji/dwBiaoge','get');		# 扫码保存
    Route::rule('dwxlsx','Bjtongji/dwBanjixls','post');		# 扫码保存
    Route::rule('tongji','Bjtongji/tongji','post');		# 统计各班级成绩
    Route::rule('myavg','Bjtongji/myAvg','post');			# 扫码界面
    Route::rule('myxiangti','Bjtongji/myXiangti','post');			# 扫码界面
    Route::rule('myfenshuduan','Bjtongji/myFenshuduan','post');          # 扫码界面
    Route::rule('bjorder','Bjtongji/bjOrder','post');		# 统计各班级成绩
    Route::rule('fenshuduan/<kaoshi_id>','Bjtongji/fenshuduan','get');          # 班级表格
    Route::rule('renke/<kaoshi_id>','Bjtongji/renke','get');          # 班级表格
    Route::rule('renkedata','Bjtongji/ajaxDataRenke','post');          # 班级表格
    Route::rule('renkeedit/<id>','Bjtongji/renkeEdit','get');          # 更新任课教师
    Route::rule('renkeupdate/<id>','Bjtongji/renkeUpdate','put');          # 班级表格
    Route::rule('renkeeditteacher/<kaoshi_id>','Bjtongji/renkeEditTeacher','get');          # 设置任课教师
    Route::rule('renkeupdateteaher','Bjtongji/renkeUpdateTeacher','put');          # 更新任课教师
    Route::rule('subject','Bjtongji/srcSubject','post');            # 成绩获取
});


// 学校年级统计
Route::group('njtj', function () {
    Route::rule('biaoge/<kaoshi_id>','Njtongji/biaoge','get');			# 班级表格
    Route::rule('data','Njtongji/ajaxData','post');			# 扫码界面
    Route::rule('dwbiaoge/<kaoshi_id>','Njtongji/dwBiaoge','get');		# 扫码保存
    Route::rule('dwxlsx','Njtongji/dwNianjixlsx','post');		# 扫码保存
    Route::rule('tongji','Njtongji/tongji','post');		# 统计各年级成绩
    Route::rule('myavg','Njtongji/myAvg','post');			# 扫码界面
    Route::rule('myxiangti','Njtongji/myXiangti','post');			# 扫码界面
    Route::rule('njorder','Njtongji/njOrder','post');		# 统计各班级成绩
});


// 全年级统计
Route::group('schtj', function () {
    Route::rule('biaoge/<kaoshi_id>','Schtongji/biaoge','get');			# 班级表格
    Route::rule('data','Schtongji/ajaxData','post');			# 扫码界面
    Route::rule('tongji','Schtongji/tongji','post');		# 统计各年级成绩
    Route::rule('schorder','Schtongji/schOrder','post');		# 统计各班级成绩
});
