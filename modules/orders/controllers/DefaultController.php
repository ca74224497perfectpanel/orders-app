<?php

namespace app\modules\orders\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\modules\orders\models\Orders;

/**
 * Default controller for the `orders` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $status = Yii::$app->request->get('order-status');

        /**
         * Фильтрация по статусу заказа.
         */
        if (isset($status) &&
            array_key_exists((int)$status, Orders::ORDER_STATUSES)) {
            $orders = Orders::find()->where(['status' => $status]);
        } else {
            $orders = Orders::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $orders,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Редирект на главную / предыдущую страницу модуля при ошибке запроса.
     * @return \yii\web\Response
     */
    public function actionError() {
        return $this->redirect(Yii::$app->homeUrl);
    }
}
