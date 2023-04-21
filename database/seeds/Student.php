<?php

use think\migration\Seeder;

class Student extends Seeder
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
        $rows= [
            [
                'xingming' => '一宁'
                ,'sex' => 0
                ,'shengri' => 1349020800
                ,'shenfenzhenghao' => '210202201210018223'
                ,'banji_id' => 1
                ,'shoupin' => 'yn'
                ,'guoqi' => null
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'xingming' => '李倍亮'
                ,'sex' => 1
                ,'shengri' => 1349020800
                ,'shenfenzhenghao' => '210202201210018214'
                ,'banji_id' => 1
                ,'shoupin' => 'lbl'
                ,'guoqi' => null
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'xingming' => '张又张'
                ,'sex' => 1
                ,'shengri' => 1349020800
                ,'shenfenzhenghao' => '210202201210018255'
                ,'banji_id' => 1
                ,'shoupin' => 'zyz'
                ,'guoqi' => null
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'xingming' => '王小天'
                ,'sex' => 1
                ,'shengri' => 1349020800
                ,'shenfenzhenghao' => '210202201210018236'
                ,'banji_id' => 1
                ,'shoupin' => 'wxt'
                ,'guoqi' => null
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'xingming' => '张一凡'
                ,'sex' => 0
                ,'shengri' => 1349020800
                ,'shenfenzhenghao' => '210202201210018227'
                ,'banji_id' => 1
                ,'shoupin' => 'zyf'
                ,'guoqi' => null
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'xingming' => '成乐园'
                ,'sex' => 1
                ,'shengri' => 1349020800
                ,'shenfenzhenghao' => '210202201210018218'
                ,'banji_id' => 1
                ,'shoupin' => 'cly'
                ,'guoqi' => null
                ,'create_time' => time()
                ,'update_time' => time()
            ]
        ];

        $serRows = $this->fetchAll('select * from cj_student');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = [];
            return true;
        }

        // 保存数据
        $this->table('student')->insert($rows)->save();
    }
}
