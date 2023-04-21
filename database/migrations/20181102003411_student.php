<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Student extends Migrator
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
        $table = $this->table('student');

        // 添加当前表字段
        $table
            ->addColumn('xingming','string',['limit'=>5,'default'=>'a','null'=>false,'comment'=>'姓名'])
            ->addColumn('sex','boolean',['limit'=>1,'default'=>1,'null'=>false,'comment'=>'性别 0=女，1=男，2=未知'])
            ->addColumn('shengri','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'生日'])
            ->addColumn('shenfenzhenghao','string',['limit'=>18,'default'=>'N1539158918','null'=>false,'comment'=>'身份证号'])
            ->addColumn('password','string',['limit'=>137,'null'=>false,'default'=>'$apr1$oz6tgaAl$ix4GBv0VxcnVJPIiodXUY/','comment'=>'登录密码'])
            ->addColumn('denglucishu','integer',['limit'=>5,'default'=>0,'null'=>false,'comment'=>'登录次数'])
            ->addColumn('lastip','string',['limit'=>55,'default'=>'127.0.0.1','null'=>false,'comment'=>'最后一次登录IP'])
            ->addColumn('ip','string',['limit'=>55,'default'=>'127.0.0.1','null'=>false,'comment'=>'登录IP'])
            ->addColumn('lasttime','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'最后登录时间'])
            ->addColumn('thistime','integer',['limit'=>11,'default'=>'0','null'=>false,'comment'=>'本次登录时间'])
            ->addColumn('banji_id','integer',['limit'=>11,'default'=>1,'null'=>false,'comment'=>'班级'])
            ->addColumn('kaoshi','boolean',['limit'=>1,'default'=>1,'null'=>false,'comment'=>'是否参加考试'])
            ->addColumn('quanpin','string',['limit'=>30,'default'=>'a','null'=>false,'comment'=>'全拼'])
            ->addColumn('shoupin','string',['limit'=>5,'default'=>'a','null'=>false,'comment'=>'简拼'])
            ->addColumn('xuehao','string',['limit'=>25,'null'=>true,'comment'=>'自定义考号'])
            ->addColumn('guoqi','integer',['limit'=>11,'null'=>true,'comment'=>'学生帐号过期时间'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->addIndex(array('shenfenzhenghao'), array('unique' => true))
            ->create();
    }
}
