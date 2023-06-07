<?php

use yii\db\Migration;

/**
 * Class m230606_070731_create_table_queues
 */
class m230606_070731_create_table_queues extends Migration
{
    /**
     * {@inheritdoc}
     */


    public function safeUp()
    {
        $this->createTable('{{%queue}}', [
            'id' => $this->primaryKey(),
            'channel' => $this->string(255)->notNull(),
            'job' => $this->binary()->notNull(),
            'pushed_at' => $this->integer(11)->notNull(),
            'ttr' => $this->integer(11)->notNull(),
            'delay' => $this->integer(11)->notNull()->defaultValue(0),
            'priority' => $this->integer(11)->unsigned()->notNull()->defaultValue(1024),
            'reserved_at' => $this->integer(11),
            'attempt' => $this->integer(11),
            'done_at' => $this->integer(11),
        ]);

        $this->createIndex(
            '{{%idx-queue-channel}}',
            '{{%queue}}',
            'channel'
        );

        $this->createIndex(
            '{{%idx-queue-reserved_at}}',
            '{{%queue}}',
            'reserved_at'
        );

        $this->createIndex(
            '{{%idx-queue-priority}}',
            '{{%queue}}',
            'priority'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230606_070731_create_table_queues cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230606_070731_create_table_queues cannot be reverted.\n";

        return false;
    }
    */
}
