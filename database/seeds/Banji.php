<?php

use think\migration\Seeder;

class Banji extends Seeder
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
        // 定义表的名称
        $y = date('Y');
        $m = date('m');

        if ($m>=8)
        {
            $ruxuenian = $y;
        } else {
            $ruxuenian = $y - 1;
        }

        // 设置数据
        $rows = [
            [
                'school_id' => 2
                ,'ruxuenian' => $ruxuenian
                ,'paixu' => 1
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'school_id' => 2
                ,'ruxuenian' => $ruxuenian
                ,'paixu' => 2
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'school_id' => 2
                ,'ruxuenian' => $ruxuenian
                ,'paixu' => 3
                ,'create_time' => time()
                ,'update_time' => time()
            ]
        ];

        $serRows = $this->fetchAll('select * from cj_banji');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = array();
            return true;
        }

        // 保存数据
        $this->table('banji')->insert($rows)->save();


    }
}