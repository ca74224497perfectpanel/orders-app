<?php /** @noinspection PhpUnused */

namespace app\modules\orders\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\modules\orders\models\search\OrdersSearch;

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
        $currentUrl = Url::current();
        $key = "page:$currentUrl:cache";
        $cache = Yii::$app->cache;
        $expiration = Yii::$app->params['cache_expiration'];

        if (($dataProvider = $cache->get($key)) === false /* в кэше нет данных */) {
            $orderSearch = new OrdersSearch(
                Yii::$app->request->get()
            );

            $adpParams = [
                'query' => $orderSearch->getQuery(),
                'pagination' => [
                    'pageSize' => 100
                ]
            ];

            $dataProvider = new ActiveDataProvider($adpParams);

            // Заносим в кэш.
            $cache->set($key, $dataProvider, $expiration);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Редирект на главную / предыдущую страницу модуля при ошибке запроса.
     * @return Response
     */
    public function actionError(): Response {
        return $this->redirect(Yii::$app->homeUrl);
    }
}
