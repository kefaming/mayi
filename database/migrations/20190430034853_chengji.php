<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Chengji extends Migrator
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
        $table = $this->table('chengji');

        // 添加当前表字段
        $table
            ->addColumn('kaohao_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'考试'])
            ->addColumn('user_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'录入'])
            ->addColumn('user_group','string',['limit'=>11, 'null'=>true,'comment'=>'用户组'])
            ->addColumn('subject_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'学科'])
            ->addColumn('defen','decimal',['precision'=>5,'scale'=>1,'default'=>Null,'null'=>false,'comment'=>'得分'])
            ->addColumn('defenlv','decimal',['precision'=>6,'scale'=>2,'default'=>Null,'null'=>false,'comment'=>'得分率'])

            ->addColumn('bpaixu','integer',['limit'=>4,'null'=>true,'comment'=>'班级排序'])
            ->addColumn('bweizhi','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'班级位置'])
            ->addColumn('xpaixu','integer',['limit'=>4,'null'=>true,'comment'=>'学校排序'])
            ->addColumn('xweizhi','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'学校位置'])
            // 区排序和位置
            ->addColumn('qpaixu','integer',['limit'=>4,'null'=>true,'comment'=>'区排序'])
            ->addColumn('qweizhi','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'区位置'])
            ->addColumn('biaozhunfen','decimal',['precision'=>6,'scale'=>2,'default'=>null,'null'=>true,'comment'=>'标准分'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->addIndex(array('kaohao_id','subject_id'), array('unique' => true))
            ->create();
    }
}
