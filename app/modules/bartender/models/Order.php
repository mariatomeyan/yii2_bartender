<?php
namespace app\modules\bartender\models;
use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_number'], 'required'],
            [['customer_number'], 'string', 'max' => 100],
            [['drink_id', 'quantity'], 'integer'],
            [['is_served'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_number' => 'Customer Number',
            'drink_id' => 'Drink ID',
            'quantity' => 'Quantity',
            'is_served' => 'Is Served',
        ];
    }

    /**
     * Define a relation for the "drink" associated with the order.
     * Assumes a foreign key constraint between "drink_id" column of "orders" table
     * and "id" column of "drinks" table.
     */
    public function getDrink()
    {
        return $this->hasOne(Drink::class, ['id' => 'drink_id']);
    }

    /**
     * Create a new order.
     *
     * @param string $customerNumber The customer number for the order.
     * @param int $drinkId The ID of the drink for the order.
     * @param int $quantity The quantity of the drink for the order.
     * @param bool $isServed Whether the order is served or not (optional).
     * @return Order|null The newly created Order model instance, or null if there was an error.
     */
    public static function createOrder($customerNumber, $drinkId, $quantity, $isServed = false)
    {
        // Check if the drink with the given drinkId exists
        $drink = Drink::findOne($drinkId);
        if ($drink === null) {
            Yii::error('Drink not found with ID: ' . $drinkId);
            return null;
        }

        $order = new self();
        $order->customer_number = $customerNumber;
        $order->drink_id = $drinkId;
        $order->quantity = $quantity;
        $order->is_served = $isServed;

        return $order->save();
    }
}

