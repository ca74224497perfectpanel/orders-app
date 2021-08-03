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
        $mode = Yii::$app->request->get('order-mode');
        $service = Yii::$app->request->get('service');

        /**
         * Фильтрация по статусу заказа.
         */
        if (is_numeric($status) && array_key_exists(
                $status,
                Orders::getOrderStatuses()
            )) {
            $orders = Orders::find()->where(['status' => $status]);
        } else {
            $orders = Orders::find();
        }

        /**
         * Фильтрация по режиму.
         */
        if (is_numeric($mode) && array_key_exists(
                $mode,
                Orders::getOrderModes()
            ) && (int)$mode !== Orders::MODE_ALL) {
            $orders->andWhere(['mode' => $mode]);
        }

        /**
         * Фильтрация по сервису.
         */
        if (is_numeric($service) && (int)$service) {
            $orders->andWhere(['service_id' => $service]);
        }

        /**
         * Поиск.
         */
        if (!empty($search) && is_numeric($srtype) &&
            array_key_exists($srtype, Orders::getOrderStatuses())) {
            switch ($srtype) {
                case Orders::SEARCH_TYPE_ORDER_ID:
                    $orders->andWhere(['id' => $search]);
                    break;
                case Orders::SEARCH_TYPE_LINK:
                    $orders->andWhere(['like', 'link', $search]);
                    break;
                case Orders::SEARCH_TYPE_USER_NAME:
                    $orders->joinWith('user')->andWhere([
                        'like',
                        "CONCAT(first_name, ' ', last_name)",
                        $search
                    ]);
                    break;
            }
        }

        /**
         * Сортировка заказов по "id" в обратном порядке.
         */
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
