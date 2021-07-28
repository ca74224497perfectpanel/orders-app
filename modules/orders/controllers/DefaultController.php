<?php

namespace app\modules\orders\controllers;

use Yii;
use yii\web\Controller;

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
        return $this->render('index');
    }

    /**
     * Редирект на главную / предыдущую страницу модуля при ошибке запроса.
     * @return \yii\web\Response
     */
    public function actionError() {
        return $this->redirect(Yii::$app->homeUrl);
    }
}
