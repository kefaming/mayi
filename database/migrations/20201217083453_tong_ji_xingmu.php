<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TongJiXingmu extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('tongji_xiangmu');

        // 添加当前表字段
        $table
            ->addColumn('category_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'类别id'])
            ->addColumn('title','string',['limit'=>100,'default'=>'a','null'=>false,'default'=>0,'comment'=>'名称'])
            ->addColumn('biaoshi','string',['limit'=>100,'default'=>'a','null'=>false,'default'=>0,'comment'=>'标识'])
            ->addColumn('paixu','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'排序'])
            ->addColumn('tongji','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'是否参加统计'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('beizhu','string',['limit'=>80,'null'=>true,'comment'=>'备注'])
            ->create();
    }
}
