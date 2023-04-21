<?php

use think\migration\Migrator;
use think\migration\db\Column;

class JsRongyuInfo extends Migrator
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
        $table = $this->table('js_rongyu_info');

        // 添加当前表字段
        $table
            ->addColumn('title','string',['limit'=>100,'default'=>'a','null'=>false,'comment'=>'获奖内容标题'])
            ->addColumn('rongyuce_id','integer',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'荣誉册'])
            ->addColumn('bianhao','string',['limit'=>15,'null'=>true,'comment'=>'证书编号'])
            ->addColumn('hjschool_id','integer',['limit'=>11,'null'=>true,'comment'=>'证书所属单位'])
            ->addColumn('subject_id','integer',['limit'=>11,'null'=>true,'comment'=>'所属学科'])
            ->addColumn('jiangxiang_id','integer',['limit'=>11,'null'=>true,'comment'=>'荣誉奖项'])
            ->addColumn('pic','string',['limit'=>100,'null'=>true,'comment'=>'证书图片'])
            ->addColumn('hjshijian','integer',['limit'=>11,'null'=>true,'comment'=>'获奖时间'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->create();
    }
}
