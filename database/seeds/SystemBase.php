<?php

use think\migration\Seeder;

class SystemBase extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // 设置数据
        $rows[] = [
            'create_time'   =>  time(),
            'update_time'   =>  time(),
            'sys_title'      =>  '码蚁成绩管理系统',
            'keywords'      =>  '码蚁成绩,成绩统计,成绩管理,成绩分析,成绩查询',
            'description'   =>  '前端采用X-admin，后端采用Thinkphp。寻找最方便的录入成绩方法，提供最丰富的统计项目。',
            'thinks'        =>  'ThinkPHP，X-admin，百度Echarts',
            'danwei'        =>  '大连长岛经济区',
            'gradelist'     =>  '一年级|二年级|三年级|四年级|五年级|六年级',
            'classmax'      =>  5,
            'classalias'    =>  0,
            'studefen' =>  1,
            'create_time' => time(),
            'update_time' => time()
        ];

        $serRows = $this->fetchAll('select * from cj_system_base');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = [];
            return true;
        }

        // 保存数据
        $this->table('system_base')->insert($rows)->save();
    }
}
