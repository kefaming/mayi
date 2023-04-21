<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Teacher extends Migrator
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
        $table = $this->table('teacher');

        // 添加当前表字段
        $table
            ->addColumn('xingming','string',['limit'=>8,'default'=>'a','null'=>false,'comment'=>'姓名'])
            ->addColumn('sex','boolean',['limit'=>1,'default'=>1,'null'=>false,'comment'=>'性别'])
            ->addColumn('shengri','integer',['limit'=>11,'null'=>true,'comment'=>'生日'])
            ->addColumn('worktime','integer',['limit'=>11,'null'=>true,'comment'=>'工作时间'])
            ->addColumn('zhiwu_id','integer',['limit'=>11,'null'=>true,'comment'=>'职务'])
            ->addColumn('zhicheng_id','integer',['limit'=>11,'null'=>true,'comment'=>'职称'])
            ->addColumn('danwei_id','integer',['limit'=>11,'default'=>1,'null'=>false,'comment'=>'现工作单位'])
            ->addColumn('biye','string',['limit'=>50,'null'=>true,'comment'=>'毕业学校'])
            ->addColumn('zhuanye','string',['limit'=>20,'null'=>true,'comment'=>'专业'])
            ->addColumn('xueli_id','integer',['limit'=>11,'null'=>true,'comment'=>'学历'])
            ->addColumn('subject_id','integer',['limit'=>11,'null'=>true,'comment'=>'学科'])
            ->addColumn('quanpin','string',['limit'=>30,'default'=>'a','null'=>false,'comment'=>'全拼'])
            ->addColumn('shoupin','string',['limit'=>5,'default'=>'a','null'=>false,'comment'=>'简拼'])
            ->addColumn('tuixiu','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'是否已经退休'])
            ->addColumn('phone','string',['limit'=>11,'default'=>0,'null'=>false,'comment'=>'手机号'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>true,'null'=>false,'comment'=>'状态'])
            ->addColumn('password','string',['limit'=>137,'null'=>false,'default'=>'$apr1$RSUodBwI$zOhVq9RQWfQDOW2sbeCDS1','comment'=>'登录密码'])
            ->addColumn('denglucishu','integer',['limit'=>5,'default'=>0,'null'=>false,'comment'=>'登录次数'])
            ->addColumn('lastip','string',['limit'=>55,'default'=>'127.0.0.1','null'=>false,'comment'=>'最后一次登录IP'])
            ->addColumn('ip','string',['limit'=>55,'default'=>'127.0.0.1','null'=>false,'comment'=>'登录IP'])
            ->addColumn('lasttime','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'最后登录时间'])
            ->addColumn('thistime','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'本次登录时间'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('beizhu','string',['limit'=>80,'null'=>true,'comment'=>'备注'])
            ->addIndex(array('phone'), array('unique' => true))
            ->create();
    }
}
