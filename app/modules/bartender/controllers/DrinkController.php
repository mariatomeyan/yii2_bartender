<?php

namespace app\modules\bartender\controllers;

use app\modules\bartender\models\Drink;
use app\modules\bartender\models\Order;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DrinkController implements the CRUD actions for Drink model.
 */
class DrinkController extends Controller
{
    /**
     * @inheritDoc
     */

    public function actionDrinks($type)
    {
        $drinks = Drink::find()->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $drinks;
    }
}
