<?php

use think\migration\Seeder;

class Xueqi extends Seeder
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
        
        $y = date('Y');
        $m = date('m');

        if ($m >= 8)
        {
            $title =  $y . '～' . ($y + 1) . '学年度第一学期';
            $niandu = $y . '～' . ($y + 1) . '学年度';
            $c = 10801;
            $b = $y . '-08-01';
            $e = ($y + 1) . '-01-31'; 
        } else {
            $title = ($y - 1) . '～' . $y . '学年度第二学期';
            $niandu = ($y - 1) . '～' . $y . '学年度';
            $c = 10802;
            $b = ($y - 1) . '-02-01';
            $e = $y . '-07-31'; 
        }

        // 设置数据
        $rows= [
            [
                'title' => $title
                ,'xuenian' => $y
                ,'category_id' => $c
                ,'bfdate' => strtotime($b)
                ,'enddate' => strtotime($e)
                ,'create_time' => time()
                ,'update_time' => time()
            ]
        ];

        $serRows = $this->fetchAll('select * from cj_xueqi');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = [];
            return true;
        }

        // 保存数据
        $this->table('xueqi')->insert($rows)->save();
    }
}