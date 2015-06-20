<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150101_000000_create_file_table
 */
class m150101_000000_create_file_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

    }

    public function down()
    {

    }
}
