<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TongjiNj extends Migrator
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
        $table = $this->table('tongji_nj');

        // 添加当前表字段
        $table
            ->addColumn('school_id','integer',['limit'=>11,'null'=>false,'default'=>0,'comment'=>'学校'])
            ->addColumn('kaoshi_id','integer',['limit'=>11,'null'=>false,'default'=>0,'comment'=>'考试'])
            ->addColumn('ruxuenian','integer',['limit'=>11,'null'=>false,'default'=>0,'comment'=>'入学年'])
            ->addColumn('subject_id','integer',['limit'=>11,'null'=>false,'default'=>0,'comment'=>'学科'])
            ->addColumn('stu_cnt','integer',['limit'=>11,'null'=>true,'comment'=>'参加考试人数'])
            ->addColumn('chengji_cnt','integer',['limit'=>11,'null'=>true,'comment'=>'有成绩数'])
            ->addColumn('sum','decimal',['precision'=>10,'scale'=>1,'null'=>true,'comment'=>'总分'])
            ->addColumn('avg','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'平均分'])
            ->addColumn('defenlv','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'得分率'])
            ->addColumn('biaozhuncha','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'标准差'])
            ->addColumn('youxiu','integer',['limit'=>11,'null'=>true,'default'=>0,'comment'=>'优秀人数'])
            ->addColumn('jige','integer',['limit'=>11,'null'=>true,'default'=>0,'comment'=>'及格人数'])
            ->addColumn('max','decimal',['precision'=>5,'scale'=>1,'null'=>true,'comment'=>'最大'])
            ->addColumn('min','decimal',['precision'=>5,'scale'=>1,'null'=>true,'comment'=>'最小'])
            ->addColumn('q1','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'下25%'])
            ->addColumn('q2','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'中间%25'])
            ->addColumn('q3','decimal',['precision'=>6,'scale'=>2,'null'=>true,'comment'=>'上面25%'])
            ->addColumn('zhongshu','string',['limit'=>100,'null'=>true,'comment'=>'众数'])
            ->addColumn('zhongweishu','decimal',['limit'=>100,'null'=>true,'comment'=>'中位数'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'创建时间'])
            ->addColumn('update_time','integer',['limit'=>11,'default'=>'1539158918','null'=>false,'comment'=>'更新时间'])
            ->addColumn('delete_time','integer',['limit'=>11,'null'=>true,'comment'=>'删除时间'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>'1','null'=>false,'comment'=>'0=禁用，1=正常'])
            ->addIndex(array('kaoshi_id','ruxuenian','subject_id','school_id'), array('unique' => true))
            ->create();
    }
}
