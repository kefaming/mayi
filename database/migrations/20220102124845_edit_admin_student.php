<?php

use think\migration\Migrator;
use think\migration\db\Column;

class EditAdminStudent extends Migrator
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
        // 教师帐号过期时间
        $table = $this->table('admin');
        $column = $table->hasColumn('guoqi');
        if (!$column) {
            $table
                ->addColumn('guoqi','integer',['limit'=>11,'null'=>true, 'comment'=>'帐号过期时间'])
                ->update();
        }

        // 学生帐号过期时间
        $table = $this->table('student');
        $column = $table->hasColumn('guoqi');
        if (!$column) {
            $table
                ->addColumn('guoqi','integer',['limit'=>11,'null'=>true, 'comment'=>'帐号过期时间'])
                ->update();
        }

        // 教师任务分工
        $table = $this->table('fen_gong');
        $column = $table->hasColumn('bfdate');
        if (!$column) {
            $table
                ->addColumn('bfdate','integer',['limit'=>11,'null'=>true,'comment'=>'开始日期'])
                ->update();
        }
        $column = $table->hasColumn('xueqi_id');
        if (!$column) {
            $table
                ->addColumn('xueqi_id','integer',['limit'=>11,'null'=>true,'comment'=>'学期'])
                ->update();
        }


    }
}
