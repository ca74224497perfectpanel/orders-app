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
        $search = Yii::$app->request->get('search');
        $srtype = Yii::$app->request->get('search-type');

        /**
         * Фильтрация по статусу заказа.
         */
        if (isset($status) &&
            array_key_exists((int)$status, Orders::ORDER_STATUSES)) {
            $orders = Orders::find()->where(['status' => $status]);
        } else {
            $orders = Orders::find();
        }

        /**
         * Поиск.
         */
        if (isset($search)) {
            switch ((int)$srtype) {
                case Orders::SEARCH_TYPE_ORDER_ID:
                    $orders->where(['id' => $search]);
                    break;
                case Orders::SEARCH_TYPE_LINK:
                    $orders->where(['like', 'link', $search]);
                    break;
                case Orders::SEARCH_TYPE_USER_NAME:
                    $orders
                        ->joinWith('user')
                        ->where(['like', "CONCAT(first_name, ' ', last_name)", $search]);
                    break;
            }
        }

        // Сортировка заказов по "id" в обратном порядке.
        $orders->orderBy(['id' => SORT_DESC]);

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
