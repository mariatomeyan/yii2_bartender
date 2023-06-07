<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%drink_types}}`.
 */
class m230604_180133_create_drink_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%drink_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(60),
            'preparation_time' => $this->integer()
        ]);

        $this->batchInsert('{{%drink_types}}',
            ['name', 'preparation_time'],
            [
                ['BEER', 5],
                ['DRINK', 300],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%drink_types}}');
    }
}
