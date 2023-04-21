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

// 考试管理
Route::group('index', function () {
    Route::rule('','Index/index','get');						# 信息列表
    Route::rule('data','Index/ajaxdata','post');				# 获取数据
    Route::rule('create','Index/create','get');				# 添加信息
    Route::rule('save','Index/save','post');					# 保存信息
    // Route::rule('read/<id>','Index/read','get');				# 读取信息
    Route::rule('edit/<id>','Index/edit','get');				# 修改信息
    Route::rule('update/<id>','Index/update','put');			# 更新信息
    Route::rule('delete','Index/delete','delete');		# 删除信息
    Route::rule('status','Index/setStatus','post');		# 删除信息
    Route::rule('luru','Index/luru','post');     # 考试编辑状态
    Route::rule('setp1','Index/createSetp1','get');              # 添加信息
    Route::rule('setp2/<kaoshi_id>','Index/createSetp2','get');              # 添加信息
    Route::rule('setp3/<kaoshi_id>','Index/createSetp3','get');              # 添加信息
    Route::rule('setp4/<kaoshi_id>','Index/createSetp4','get');              # 添加信息
    Route::rule('tongji/<kaoshi_id>','Index/tongji','get');              # 添加信息
    Route::rule('chengji/<kaoshi_id>','Index/chengji','get');              # 添加信息
    Route::rule('editsetp1/<id>','Index/editSetp1','get');            # 更新信息
    Route::rule('updatesetp1/<id>','Index/updateSetp1','put');            # 更新信息
    Route::rule('srceditkaoshi','Index/srcEditKaoshi','post');                # 获取数据
});


// 考试设置
Route::group('kaoshiset', function () {
    Route::rule('index/<kaoshi_id>','KaoshiSet/index','get');						# 信息列表
    Route::rule('data','KaoshiSet/ajaxdata','post');				# 获取数据
    Route::rule('create/<kaoshi_id>','KaoshiSet/create','get');		# 添加信息
    Route::rule('save','KaoshiSet/save','post');					# 保存信息
    Route::rule('edit/<id>','KaoshiSet/edit','get');				# 修改信息
    Route::rule('update/<id>','KaoshiSet/update','put');			# 更新信息
    Route::rule('delete','KaoshiSet/delete','delete');		# 删除信息
    Route::rule('status','KaoshiSet/setStatus','post');		# 修改状态
});


// 考试设置
Route::group('tjlog', function () {
    Route::rule('index/<kaoshi_id>','TongjiLog/index','get');
    Route::rule('data','TongjiLog/ajaxData','post');
});


// 统计项目设置
Route::group('tjxm', function () {
    Route::rule('index','TongjiXiangmu/index','get');
    Route::rule('create','TongjiXiangmu/create','get');             # 添加信息
    Route::rule('save','TongjiXiangmu/save','post');                    # 保存信息
    Route::rule('edit/<id>','TongjiXiangmu/edit','get');                # 修改信息
    Route::rule('update/<id>','TongjiXiangmu/update','put');            # 更新信息
    Route::rule('data','TongjiXiangmu/ajaxData','post');
    Route::rule('tongji','TongjiXiangmu/setTongji','post');     # 是否参与统计
    Route::rule('status','TongjiXiangmu/setStatus','post');     # 修改状态
    Route::rule('delete','TongjiXiangmu/delete','delete');     # 删除信息
});


// 考试设置
Route::group('lrfg', function () {
    Route::rule('index/<kaoshi_id>','LuruFengong/index','get');                       # 信息列表
    Route::rule('data','LuruFengong/ajaxdata','post');                # 获取数据
    Route::rule('create/<kaoshi_id>','LuruFengong/create','get');     # 添加信息
    Route::rule('save','LuruFengong/save','post');                    # 保存信息
    Route::rule('delete','LuruFengong/delete','delete');     # 删除信息
    Route::rule('class','LuruFengong/class','post');    # 参与学科
    Route::rule('subject','LuruFengong/subject','post');    # 参与学科
});


// 教师管理
Route::group('kscy', function () {
    Route::rule('school','KsCanyu/school','post');    # 参与学科
    Route::rule('grade','KsCanyu/grade','post');    # 参与学科
    Route::rule('class','KsCanyu/class','post');    # 参与学科
    // Route::rule('tjclass','KsCanyu/tjClass','post');    # 参与学科
    Route::rule('subject','KsCanyu/subject','post');    # 参与学科
});







