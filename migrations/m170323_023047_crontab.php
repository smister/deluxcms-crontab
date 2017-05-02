<?php

use yii\db\Migration;

class m170323_023047_crontab extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%crontabs}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull()->defaultValue(''),
            'type' => $this->smallInteger()->notNull()->defaultValue(1)->comment('类型;[1=系统],[2=php类型]'),
            'min' => $this->string(32)->notNull()->defaultValue('*'),
            'hour' => $this->string(32)->notNull()->defaultValue('*'),
            'day' => $this->string(32)->notNull()->defaultValue('*'),
            'month' => $this->string(32)->notNull()->defaultValue('*'),
            'week' => $this->string(32)->notNull()->defaultValue('*'),
            'command' => $this->string(254)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%crontab_job}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
