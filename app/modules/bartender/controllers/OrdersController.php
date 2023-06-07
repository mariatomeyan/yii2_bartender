<?php
namespace app\modules\bartender\controllers;
use app\modules\bartender\models\Drink;
use Yii;
use app\components\WebSocketComponent;
use app\modules\bartender\jobs\MakingDrinkJob;
use app\modules\bartender\models\CreateOrderForm;
use yii\rest\Controller;
use app\modules\bartender\models\Order;
use yii\web\Response;
class OrdersController extends Controller
{
    /**
     * creating and storing drinks
     *
     * 1. check the request
     * 2. validate the reuqets
     * 3. store the request in the job queue
     * 4. logic
     * 5. response back accordingly
    **/
    public function actionCreateOrder()
    {
        $request = Yii::$app->request;
        $orderModelForm = new CreateOrderForm();
        $orderModelForm->setAttributes(Yii::$app->request->post());

        if ($orderModelForm->validate()) {

            $customerNumber = Yii::$app->security->generateRandomString(8); // Generate a random string of length 8

            $drink = Drink::findDrinkByType($orderModelForm->drink);

            // Create a new Drink model and set the attributes
            $order = new Order();
            $result = $order::createOrder(
                $customerNumber, $drink->id, $orderModelForm->quantity
            );
            
            Yii::$app->queue->delay($drink->preparation_time)->push(new MakingDrinkJob());

            // Save the drink to the database
            if ($result) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => 'Order created successfully'];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['error' => $order->errors];
            }
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $orderModelForm->errors];
        }
    }

    public function actionGetOrders()
    {
        $orders = Order::find()->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $orders;
    }
}