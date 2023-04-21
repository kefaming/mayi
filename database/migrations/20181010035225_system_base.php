<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemBase extends Migrator
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
        // 定义表的名称
        $table = $this->table('system_base');

        // 给当前表添加字段
        $table
            ->addColumn('sys_title','string',['limit'=>60,'null'=>false,'default'=>'码蚁成绩管理系统','comment'=>'系统名称'])
            ->addColumn('keywords','string',['limit'=>60,'null'=>false,'default'=>'尚码成绩管理,录入,统计,查询,管理','comment'=>'关键词'])
            ->addColumn('description','string',['limit'=>100,'null'=>false,'default'=>'尚码成绩统计系统，包含成绩采集、成绩统计、成绩查询等功能。适合一线的成绩统计系统才是好系统。','comment'=>'网站说明'])
            ->addColumn('thinks','string',['limit'=>80,'null'=>false,'default'=>'ThinkPHP,X-admin,百度Echarts,jquery','comment'=>'网站感谢'])
            ->addColumn('danwei','string',['limit'=>20,'null'=>false,'default'=>'大连长兴岛','comment'=>'使用单位'])
            ->addColumn('gradelist','string',['limit'=>200,'default'=>'一年级|二年级|三年级|四年级|五年级|六年级','null'=>false,'comment'=>'年级名称列表'])
            ->addColumn('classmax','integer',['limit'=>2,'default'=>30,'null'=>false,'comment'=>'最大班级数'])
            ->addColumn('xuenian','integer',['limit'=>11,'null'=>false,'default'=>523206000,'comment'=>'划分学年节点'])
            ->addColumn('xueqishang','integer',['limit'=>11,'null'=>false,'default'=>523206000,'comment'=>'划分上学期节点'])
            ->addColumn('xueqixia','integer',['limit'=>11,'null'=>false,'default'=>523206000,'comment'=>'划分下学期节点'])
            ->addColumn('classalias','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'是否开启班级别名'])
            ->addColumn('teacherrongyu','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'教师查看个人荣誉'])
            ->addColumn('teacherketi','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'教师查看个人课题'])
            ->addColumn('studefen','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'是否允许学生查看分数'])
            ->addColumn('teacher_guoqi','integer',['limit'=>11,'null'=>false,'default'=>1539158918,'comment'=>'教师帐号过期时间'])
            ->addColumn('student_guoqi','integer',['limit'=>11,'null'=>false,'default'=>1539158918,'comment'=>'学生帐号过期时间'])
            ->addColumn('create_time','integer',['limit'=>11,'null'=>false,'default'=>1539158918,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'null'=>false,'default'=>1539158918,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->create();
    }
}
