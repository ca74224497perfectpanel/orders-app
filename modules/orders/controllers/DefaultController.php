<?php
/** @noinspection PhpUnused */

namespace orders\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use orders\models\search\Cache;
use orders\models\search\OrdersSearch;

/**
 * Default controller for the `orders` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module.
     * @return string
     */
    public function actionIndex(): string
    {
        $orderSearch = new Cache(
            new OrdersSearch(Yii::$app->request->get())
        );

        return $this->render('index', [
            'dataProvider' => $orderSearch->getData()
        ]);
    }

    /**
     * Редирект на главную / предыдущую страницу модуля при ошибке запроса.
     * @return Response
     */
    public function actionError(): Response
    {
        return $this->redirect(Yii::$app->homeUrl);
    }
}
