<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TeacherToAdmin extends Migrator
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
    public function up()
    {
        $table = $this->table('admin');
        $column = $table->hasColumn('worktime');
        if (!$column) {
            $this->execute('UPDATE cj_admin set phone=null where id>0');
            $table
            ->addColumn('worktime','integer',['limit'=>11,'null'=>true,'comment'=>'工作时间'])
            ->addColumn('zhiwu_id','integer',['limit'=>11,'null'=>true,'comment'=>'职务'])
            ->addColumn('zhicheng_id','integer',['limit'=>11,'null'=>true,'comment'=>'职称'])
            ->addColumn('biye','string',['limit'=>50,'null'=>true,'comment'=>'毕业学校'])
            ->addColumn('zhuanye','string',['limit'=>20,'null'=>true,'comment'=>'专业'])
            ->addColumn('xueli_id','integer',['limit'=>11,'null'=>true,'comment'=>'学历'])
            ->addColumn('subject_id','integer',['limit'=>11,'null'=>true,'comment'=>'学科'])
            ->addColumn('quanpin','string',['limit'=>30,'default'=>'a','null'=>false,'comment'=>'全拼'])
            ->addColumn('shoupin','string',['limit'=>5,'default'=>'a','null'=>false,'comment'=>'简拼'])
            ->addColumn('tuixiu','boolean',['limit'=>1,'default'=>0,'null'=>false,'comment'=>'是否已经退休'])
            ->addIndex(array('phone'), array('unique' => true))
            ->update();
        }

        $table = $this->table('kaoshi');
        $column = $table->hasColumn('user_id');
        if (!$column) {
            $table
            ->addColumn('user_id','integer',['after' => 'category_id','limit'=>11,'default'=>0,'null'=>false,'comment'=>'用户ID'])
            ->update();
        }
        $column = $table->hasColumn('fanwei_id');
        if (!$column) {
            $table
            ->addColumn('fanwei_id','integer',['after' => 'category_id','limit'=>11,'default'=>0,'null'=>false,'comment'=>'允许查看范围ID'])
            ->update();
        }
        

        $table = $this->table('kaoshi_set');
        $column = $table->hasColumn('lianghao');
        if (!$column) {
            $table
                ->addColumn('lianghao','integer',['after'=>'youxiu','limit'=>3,'default'=>90,'null'=>false,'comment'=>'良好分数线'])
                ->addColumn('youxiubi','integer',['after'=>'jige','limit'=>3,'default'=>90,'null'=>false,'comment'=>'优秀人数比'])
                ->addColumn('lianghaobi','integer',['after'=>'youxiubi','limit'=>3,'default'=>90,'null'=>false,'comment'=>'良好人数比'])
                ->addColumn('jigebi','integer',['after'=>'lianghaobi','limit'=>3,'default'=>00,'null'=>false,'comment'=>'及格人数比'])
                ->update();
        }

        $exists = $this->hasTable('keti');
        if ($exists) {
            $table = $this->table('keti');
            $table->rename('lixiang');
        }

        $table = $this->table('keti_info');
        $column = $table->hasColumn('ketice_id');
        if ($column) {
            $table->renameColumn('ketice_id', 'lixiang_id');
            $table
                ->addColumn('jieti_id','integer',['after'=>'lixiang_id','limit'=>11,'null'=>false,'default'=>0,'comment'=>'结题册id'])
                ->update();
        }

        $table = $this->table('cj_migrations');
        $this->execute('DELETE FROM cj_migrations WHERE version="20210107070200" and migration_name="KaoshiAddUser"');
        $this->execute('UPDATE cj_migrations set migration_name="Lixiang" WHERE version="20190218055858" and migration_name="Keti"');
    }


    //版本退回
    public function down1()
    {
        $table = $this->table('fields');
        $column = $table->hasColumn('user_group');
        if ($column) {
            $table
            ->removeColumn('user_group');
        }

        $table = $this->table('chengji');
        $column = $table->hasColumn('user_group');
        if ($column) {
            $table
            ->removeColumn('user_group');
        }

        $table = $this->table('kaoshi');
        $column = $table->hasColumn('user_group');
        if ($column) {
            $table
            ->removeColumn('user_group');
        }

        $table = $this->table('admin');
        $column = $table->hasColumn('teacher_id');
        if ($column) {
            $table
            ->removeColumn('teacher_id');
        }

        $table = $this->table('system_base');
        $column = $table->hasColumn('teacherrongyu');
        if ($column) {
            $table
            ->removeColumn('teacherrongyu')
            ->removeColumn('teacherketi');
        }

        $exists = $this->hasTable('teacher');
        if ($exists) {
            $this->dropTable('teacher');
        }
    }


}
