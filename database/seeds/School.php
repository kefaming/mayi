<?php

use think\migration\Seeder;

class School extends Seeder
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
                'id' => 1
                ,'title' => '大连无名岛经济区文教中心'
                ,'jiancheng' => '文教中心'
                ,'biaoshi' => ''
                ,'jibie_id' => 10204
                ,'xueduan_id'  =>  10303
                ,'paixu' => 10
                ,'kaoshi' => 1
                ,'xingzhi_id' => 10107
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            [
                'id' => 2
                ,'title' => '大连无名岛经济区靠海小学'
                ,'jiancheng' => '靠海'
                ,'biaoshi' => ''
                ,'jibie_id' => 10203
                ,'xueduan_id'  =>  10302
                ,'paixu' => 1
                ,'kaoshi' => 1
                ,'xingzhi_id' => 10102
                ,'create_time' => time()
                ,'update_time' => time()
            ],
        ];
        $serRows = $this->fetchAll('select * from cj_school');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = [];
            return true;
        }
        // 保存数据
        $this->table('school')->insert($rows)->save();
    }
}
