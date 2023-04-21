<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TongjiLog extends Migrator
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
        // 定义数据表名称
        $table = $this->table('tongji_log');

        // 添加当前表字段
        $table
            ->addColumn('kaoshi_id','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'考试ID'])
            ->addColumn('category_id','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'统计项目'])
            ->addColumn('user_id','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'统计人ID'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->create();
    }
}
