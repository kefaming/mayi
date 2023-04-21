<?php

use think\migration\Seeder;

class Admin extends Seeder
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
        // 计算帐号禁用时间
        $jyshijian = config('shangma.mimaguoqi');
        $now = date("Y-m-d", time());
        $jinyong = strtotime("$now+".$jyshijian." day");

        // 定义表的名称
        $rows = [
            [
                'id'        =>  1,
                'xingming'  =>  '超级管理员一',
                'sex' => 1,
                'shengri' => strtotime("-25 year"),
                'username'  =>  'admin',
                'password'  =>  '$apr1$oz6tgaAl$ix4GBv0VxcnVJPIiodXUY/',
                'school_id' =>  1,
                'phone' => "13190180000",
                'worktime' => time(),
                'zhicheng_id' => "10603",
                "xueli_id" => "10503",
                "quanpin" => "chaojiyi",
                "shoupin" => "cjy",
                "tuixiu" => 0,
                "lasttime" => time(),
                "thistime" => time(),
                "guoqi" => $jinyong,
                "create_time" => time(),
                "update_time" => time()
            ],
            [
                'id'        =>  2,
                'xingming'  =>  '超级管理员二',
                'sex' => 1,
                'shengri' => strtotime("-25 year"),
                'username'  =>  'admin1',
                'password'  =>  '$apr1$oz6tgaAl$ix4GBv0VxcnVJPIiodXUY/',
                'school_id' =>  1,
                'phone' => "13190180001",
                'worktime' => time(),
                'zhicheng_id' => "10603",
                "xueli_id" => "10503",
                "quanpin" => "chaojier",
                "shoupin" => "cje",
                "tuixiu" => 0,
                "lasttime" => time(),
                "thistime" => time(),
                "guoqi" => $jinyong,
                "create_time" => time(),
                "update_time" => time()
            ],
        ];

        $serRows = $this->fetchAll('select * from cj_admin');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = array();
            return true;
        }
        
        // 保存数据
        $this->table('admin')->insert($rows)->update();
    }

}
