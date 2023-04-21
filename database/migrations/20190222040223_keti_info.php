<?php

use think\migration\Migrator;
use think\migration\db\Column;

class KetiInfo extends Migrator
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
        $table = $this->table('keti_info');

        // 添加当前表字段
        $table
            ->addColumn('title','string',['limit'=>100,'null'=>false,'default'=>'无标题','comment'=>'课题名称'])
            ->addColumn('lixiang_id','integer',['limit'=>11,'null'=>false,'default'=>0,'comment'=>'立项册id'])
            ->addColumn('jieti_id','integer',['limit'=>11,'null'=>false,'default'=>0,'comment'=>'结题册id'])
            ->addColumn('bianhao','string',['limit'=>50,'null'=>true,'comment'=>'课题编号'])
            ->addColumn('lxpic','string',['limit'=>100,'null'=>true,'comment'=>'立项证书图片'])
            ->addColumn('subject_id','integer',['limit'=>11,'null'=>true,'comment'=>'学科分类'])
            ->addColumn('fzdanwei_id','integer',['limit'=>11,'null'=>true,'comment'=>'负责单位id'])
            ->addColumn('category_id','integer',['limit'=>11,'null'=>true,'comment'=>'研究类型'])
            ->addColumn('jhjtshijian','integer',['limit'=>11,'null'=>true,'comment'=>'计划结题时间'])
            ->addColumn('jtshijian','integer',['limit'=>11,'null'=>true,'comment'=>'结题时间'])
            ->addColumn('jddengji_id','integer',['limit'=>11,'null'=>false,'default'=>11801,'comment'=>'鉴定等级'])
            ->addColumn('jtpic','string',['limit'=>100,'null'=>true,'comment'=>'结题证书图片'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->addColumn('beizhu','string',['limit'=>200,'null'=>true,'comment'=>'备注'])
            ->create();
    }
}
