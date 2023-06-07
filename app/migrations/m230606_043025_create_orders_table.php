<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m230606_043025_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'customer_number' => $this->string(100),
            'drink_id' => $this->integer(60),
            'quantity' => $this->integer(60),
            'is_served' => $this->boolean()->defaultValue(false)
        ]);

        // add foreign key for table `{{%drinks}}`
        $this->addForeignKey(
            '{{%fk-orders-drink_id}}',
            '{{%orders}}',
            'drink_id',
            '{{%drinks}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%orders}}');
    }
}
