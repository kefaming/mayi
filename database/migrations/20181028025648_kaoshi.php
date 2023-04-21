<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Kaoshi extends Migrator
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
        $table = $this->table('kaoshi');

        // 添加当前表字段
        $table
            ->addColumn('title','string',['limit'=>25,'default'=>'a','null'=>false,'comment'=>'考试名称'])
            ->addColumn('zuzhi_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'组织考试单位'])
            ->addColumn('xueqi_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'学期'])
            ->addColumn('category_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'类别'])
            ->addColumn('user_id','integer',['after' => 'category_id','limit'=>11,'default'=>0,'null'=>false,'comment'=>'用户ID'])
            ->addColumn('fanwei_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'允许查看范围ID'])
            ->addColumn('bfdate','integer',['limit'=>11,'default'=>1539158918,'null'=>false,'comment'=>'开始日期'])
            ->addColumn('enddate','integer',['limit'=>11,'default'=>1539158918,'null'=>false,'comment'=>'结束日期'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->addColumn('luru','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=不允许，1=允许'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('beizhu','string',['limit'=>80,'null'=>true,'comment'=>'备注'])
            ->create();
    }
}
