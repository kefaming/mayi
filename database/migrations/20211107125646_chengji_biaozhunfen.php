<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ChengjiBiaozhunfen extends Migrator
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
        $table = $this->table('chengji');
        $column = $table->hasColumn('biaozhunfen');
        if (!$column) {
            $table
                ->addColumn('biaozhunfen','decimal',['precision'=>6,'scale'=>2,'default'=>Null,'null'=>false,'comment'=>'标准分'])
                ->update();
        }
    }


    //版本退回
    public function down()
    {
        $table = $this->table('chengji');
        $column = $table->hasColumn('biaozhunfen');
        if ($column) {
            $table
            ->removeColumn('biaozhunfen');
        }
    }
}
