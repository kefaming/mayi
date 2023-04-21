<?php

use think\migration\Seeder;

class ZauthGroup extends Seeder
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
        $authList = $this->fetchAll('select * from cj_auth_rule where status = 1');
        $authList = array_column($authList, 'id');
        $authList = implode(',', $authList);

        // 设置数据
        $rows= [
            [
                'title' => '管理员'
                ,'rules' => $authList
                ,'miaoshu' => '拥有所有权限'
                ,'create_time' => time()
                ,'update_time' => time()
            ]
        ];

        $serRows = $this->fetchAll('select * from cj_auth_group');
        if($rows[0]['rules'] == '' || (is_array($serRows) && count($serRows) > 0))
        {
            $rows = array();
            return true;
        }

        // 保存数据
        $this->table('auth_group')->insert($rows)->save();
    }
}