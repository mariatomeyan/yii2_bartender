<?php
namespace app\modules\bartender\models;

use yii\base\Model;
use app\modules\bartender\models\Drink;


class CreateOrderForm extends Model
{
    public $customer_number;
    public $drink;
    public $quantity;

    public function rules()
    {
        return [
            [['drink', 'quantity'], 'required'],
            ['quantity', 'checkAllowedQuantity'],
        ];
    }

    public function checkAllowedQuantity($attribute, $params)
    {
        // Retrieve the drink type based on the drink_id and perform the check
        $drink = DrinkType::findOne(['name' => $this->drink]);
        $allowedQuantities = ($drink && $drink->name === 'BEER') ? [1, 2] : [1];

        if (!in_array((int)$this->$attribute, $allowedQuantities, true)) {
            $this->addError($attribute, 'Invalid quantity for the drink');
        }
    }
}
