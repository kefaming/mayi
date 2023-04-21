<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 版本号
    # 系统版本
    'webtitle' => '码蚁成绩管理',
    # 系统版本
    'version' => 'v1.3.9 Beta',   
    # 是否开启维护跳转
    'weihu' => Env::get('shangma.weihu', false),  
    # 开始时间
    'shijian' => Env::get('shangma.weihushijian', '2020-1-1 0:00'),  
    # 时长:单位分钟
    'shichang' => Env::get('shangma.weihuchixu', '100'),           
    // 数据库备份
    'backup' =>[
        # 数据库备份路径
        'path' => './data/',
        # 数据库备份卷大小
        'part' => 20971520,
        # 数据库备份文件是否启用压缩 0不压缩 1 压缩
        'compress' => 0,
        # 数据库备份文件压缩级别 1普通 4 一般  9最高
        'level' => 9 
    ],
    'lurufanwei' => Env::get('shangma.lurufanwei', false),   # 是否开启教师只能录入自己任课班级的成绩
    'mimaguoqi' => Env::get('shangma.mimajinyong', '10')    # 创建帐号后，x天不修改初始密码，帐号将被禁用。
];
