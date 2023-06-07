<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%drinks}}`.
 */
class m230605_130811_create_drinks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%drinks}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'drink_type' => $this->integer(11),
            'preparation_time' => $this->integer(11)->defaultValue(null),
        ]);

        // add foreign key for table `{{%drinks_type}}`
        $this->addForeignKey(
            '{{%fk-drinks-drink_type}}',
            '{{%drinks}}',
            'drink_type',
            '{{%drink_types}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('{{%drinks}}',
            ['name', 'drink_type', 'preparation_time'],
            [
                ['Mojito', 1, 400],
                ['Cosmopolitan', 1, 800],
                ['Martini', 1, 432],
                ['Margarita', 1, 700],
                ['Old Fashioned', 1, 900],
                ['Pina Colada', 1,333],
                ['Daiquiri', 1, 987],
                ['Manhattan', 1, 433],
                ['Bloody Mary', 1, 676],

                ['Guinness', 2, null],
                ['Heineken', 2, null],
                ['Budweiser', 2, null],
                ['Corona', 2, null],
                ['Stella Artois', 2, null],
                ['Blue Moon', 2, null],
                ['Coors Light', 2, null],
                ['Sierra Nevada', 2, null],
                ['Pilsner Urquell', 2, null],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-drinks-drink_type}}', '{{%drinks}}');
        $this->dropTable('{{%drinks}}');
    }
}
