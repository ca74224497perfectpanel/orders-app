<?php
/** @noinspection PhpUnused */

namespace orders\controllers;

use Yii;
use Exception;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use orders\helpers\Utils;
use orders\models\search\Cache;
use yii\web\BadRequestHttpException;
use orders\models\search\OrdersSearch;
use yii2tech\csvgrid\CsvGrid;

/**
 * Default controller for the `orders` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module.
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $orderSearch = new OrdersSearch(
            Yii::$app->request->get()
        );

        $dataProvider = (new Cache($orderSearch))->getData();

        if (Yii::$app->request->get('get-csv')) {
            /* Отдаем CSV-файл по запросу */

            $columns = $orderSearch->getColumnsDefinition(true);

            $exporter = new CsvGrid([
                'dataProvider' => $dataProvider,
                'columns' => $columns
            ]);

            $exporter->export()->send(
                Utils::generateCsvFileName()
            );

            Yii::$app->end();
        } else {
            $columns = $orderSearch->getColumnsDefinition();
        }

        return $this->render('index', [
            'columns' => $columns,
            'dataProvider' => $dataProvider
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
