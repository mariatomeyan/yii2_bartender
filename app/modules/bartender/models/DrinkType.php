<?php
namespace app\modules\bartender\models;

use yii\db\ActiveRecord;

class DrinkType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%drink_types}}';
    }

    /**
     * Define the relations for DrinkType.
     */
    public function getDrinks()
    {
        return $this->hasMany(Drink::class, ['drink_type' => 'id']);
    }
}
