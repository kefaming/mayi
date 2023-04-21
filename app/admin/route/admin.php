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

// 管理员管理
Route::group('index', function () {
    Route::rule('', 'Index/index', 'get');						# 信息列表
    Route::rule('data', 'Index/ajaxData', 'post');				# 获取数据
    Route::rule('create', 'Index/create', 'get');				# 添加信息
    Route::rule('save' ,'Index/save', 'post');					# 保存信息
    Route::rule('read/<id>', 'Index/read', 'get');				# 读取信息
    Route::rule('edit/<id>', 'Index/edit', 'get');				# 修改信息
    Route::rule('update/<id>', 'Index/update', 'put');			# 更新信息
    Route::rule('delete', 'Index/delete', 'delete');		# 删除信息
    Route::rule('status', 'Index/setStatus', 'post');		# 删除信息
    // Route::rule('adminlist', 'Index/adminList', 'post');       # 用户信息
    Route::rule('downloadxls', 'Index/downloadxls', 'get');       # 用户信息
    Route::rule('createall','Index/createAll','get');              # 批量导入
    Route::rule('saveall','Index/saveAll','post');                  # 批量保存
    Route::rule('srcteacher','Index/srcAdmin','post');        # 查询教师
    Route::rule('resetpassword/<id>', 'Index/resetPassword', 'post');       # 重置密码
    Route::rule('srcusername','Index/srcUsername','post');        # 查询用户名是否已经存在
    Route::rule('srcphone','Index/srcPhone','post');        # 查询电话是否已经存在
});


// 权限管理
Route::group('authrule', function () {
    Route::rule('', 'AuthRule/index', 'get');						# 信息列表
    Route::rule('data', 'AuthRule/ajaxData', 'post');				# 获取数据
    Route::rule('create', 'AuthRule/create', 'get');				# 添加信息
    Route::rule('save', 'AuthRule/save', 'post');					# 保存信息
    Route::rule('edit/<id>', 'AuthRule/edit', 'get');				# 修改信息
    Route::rule('update/<id>', 'AuthRule/update', 'put');			# 更新信息
    Route::rule('delete', 'AuthRule/delete', 'delete');		# 删除信息
    Route::rule('status', 'AuthRule/setStatus', 'post');		# 删除信息
    Route::rule('menu/<user_id>', 'AuthRule/menu', 'get');        # 删除信息
});


// 角色管理
Route::group('authgroup', function () {
    Route::rule('','AuthGroup/index','get');						# 信息列表
    Route::rule('data','AuthGroup/ajaxData','post');				# 获取数据
    Route::rule('create','AuthGroup/create','get');				# 添加信息
    Route::rule('save','AuthGroup/save','post');					# 保存信息
    Route::rule('edit/<id>','AuthGroup/edit','get');				# 修改信息
    Route::rule('update/<id>','AuthGroup/update','put');			# 更新信息
    Route::rule('delete','AuthGroup/delete','delete');		# 删除信息
    Route::rule('status','AuthGroup/setStatus','post');		# 删除信息
});


// 角色用户对应表
Route::group('authgroupaccess', function () {
    Route::rule('index/<group_id>','AuthGroupAccess/index','get');                        # 信息列表
    Route::rule('data','AuthGroupAccess/ajaxData','post');                # 获取数据
    Route::rule('create/<group_id>','AuthGroupAccess/create','get');             # 添加信息
    Route::rule('save','AuthGroupAccess/save','post');                    # 保存信息
    Route::rule('delete','AuthGroupAccess/delete','delete');     # 删除信息
    Route::rule('all','AuthGroupAccess/saveAll','post');                # 全部设置成此权限
});


// 角色用户对应表
Route::group('tongbu', function () {
    Route::rule('index','Tongbu/index','get');                        
    Route::rule('teacher','Tongbu/teacher','post');                        
    Route::rule('bzr','Tongbu/bzr','post');
    Route::rule('chengji','Tongbu/chengji','post');
    Route::rule('dwry','Tongbu/dwry','post');
    Route::rule('file','Tongbu/file','post');
    Route::rule('jsry','Tongbu/jsry','post');
    Route::rule('kaoshi','Tongbu/kaoshi','post');
    Route::rule('ktcy','Tongbu/ktcy','post');
    Route::rule('tjbj','Tongbu/tjbj','post');
    Route::rule('bcks','Tongbu/kaoshiMoren','post');
});


// 管理员信息
Route::group('info', function () {
    Route::rule('read/<id>','AdminInfo/readAdmin','get');
    Route::rule('editpassword', 'AdminInfo/editPassword', 'get');       # 修改密码
    Route::rule('updatepassword/<id>', 'AdminInfo/updatePassword', 'put');      # 更新密码
    Route::rule('myinfo', 'AdminInfo/myInfo', 'get');       # 用户信息
    Route::rule('srcry','AdminInfo/srcRy','post');      # 查询荣誉
    Route::rule('srckt','AdminInfo/srcKt','post');      # 查询课题
    Route::rule('srcbzr','AdminInfo/srcBzr','post');      # 查询课题
    Route::rule('srcrk','AdminInfo/srcRenke','post');      # 查询课题
    Route::rule('edit', 'AdminInfo/edit', 'get');              # 修改信息
    Route::rule('update/<id>', 'AdminInfo/update', 'put');          # 更新信息
});
