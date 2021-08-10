<?php
/** @noinspection PhpUnused */

namespace orders\controllers;

use Yii;
use Exception;
use yii\web\Response;
use yii\web\Controller;
use orders\helpers\Utils;
use orders\models\Orders;
use yii2tech\csvgrid\CsvGrid;
use orders\models\search\Cache;
use orders\widgets\ModeDropdown;
use orders\widgets\ServiceDropdown;
use orders\models\search\OrdersSearch;

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
        $orderSearch = new Cache(
            new OrdersSearch(Yii::$app->request->get())
        );

        $dataProvider = $orderSearch->getData();

        $dateYmd = fn($d) => date('Y-m-d', $d);
        $dateHis = fn($d) => date('H:i:s', $d);

        $columns = [
            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($item) {
                    return "{$item->user->first_name} {$item->user->last_name}";
                }
            ],
            [
                'attribute' => 'link',
                'contentOptions' => ['class' => 'link']
            ],
            'quantity',
            [
                'header' => ServiceDropdown::widget(),
                'attribute' => 'service_id',
                'contentOptions' => ['class' => 'service'],
                'headerOptions' => ['class' => 'dropdown-th'],
                'format' => 'html',
                'value' => function ($item) {
                    return "<span class='label-id'>{$item->service->id}</span> 
                        {$item->service->name}";
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($item) {
                    return Orders::getStatusLabelById($item->status);
                }
            ],
            [
                'header' => ModeDropdown::widget(),
                'attribute' => 'mode',
                'headerOptions' => ['class' => 'dropdown-th'],
                'value' => function ($item) {
                    return Orders::getModeLabelById($item->mode);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'value' => fn($item) => '<span class="nowrap">' . $dateYmd(
                        $item->created_at
                    ) . '</span><span class="nowrap">' . $dateHis(
                        $item->created_at
                    ) . '</span>'
            ]
        ];

        /* Отдаем CSV-файл по запросу */
        if (Yii::$app->request->get('get-csv')) {
            // Редактируем колонки для репрезентации в csv-формате.

            $columns[2] = ['attribute' => 'link'];
            $columns[4] = [
                'attribute' => 'service_id',
                'value' => function ($item) {
                    return "{$item->service->name} ({$item->service->id})";
                }
            ];

            unset($columns[6]['header']);
            unset($columns[6]['headerOptions']);

            $columns[7] = [
                'attribute' => 'created_at',
                'value' => fn($item) => $dateYmd(
                        $item->created_at
                    ) . PHP_EOL . $dateHis($item->created_at)
            ];

            $exporter = new CsvGrid([
                'dataProvider' => $dataProvider,
                'columns' => $columns
            ]);

            $exporter->export()->send(
                Utils::generateCsvFileName()
            );

            Yii::$app->end();
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'columns' => $columns
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
