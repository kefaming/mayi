<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AddStudentKaohao extends Migrator
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
        $table = $this->table('student');
        $column = $table->hasColumn('xuehao');
        if (!$column) {
            $table
                ->addColumn('xuehao','string',['limit'=>25,'null'=>true,'comment'=>'自定义学号'])
                ->update();
        }
    }


    //版本退回
    public function down()
    {
        $table = $this->table('student');
        $column = $table->hasColumn('xuehao');
        if ($column) {
            $table
            ->removeColumn('xuehao');
        }
    }
}
