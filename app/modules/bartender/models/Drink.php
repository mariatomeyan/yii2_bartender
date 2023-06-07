<?php

namespace app\modules\bartender\models;

use Yii;

/**
 * This is the model class for table "drinks".
 *
 * @property int $id
 * @property string $name
 * @property int|null $drink_type
 * @property int|null $preparation_time
 *
 * @property DrinkType $drinkType
 */
class Drink extends \yii\db\ActiveRecord
{
    public $preparation_time;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drinks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['drink_type', 'preparation_time'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['drink_type'], 'exist', 'skipOnError' => true, 'targetClass' => DrinkType::class, 'targetAttribute' => ['drink_type' => 'id']],
        ];
    }

    public static function find()
    {
        return parent::find()->with('drinkType');
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'drink_type' => 'Drink Type',
            'preparation_time' => 'Preparation Time',
        ];
    }

    /**
     * Gets query for [[DrinkType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDrinkType()
    {
        return $this->hasOne(DrinkType::class, ['id' => 'drink_type']);
    }

    public static function findDrinkByType($type)
    {
        return static::find()
            ->joinWith('drinkType')
            ->where(['drink_types.name' => $type])
            ->one();
    }
}
