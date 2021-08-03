<?php

namespace app\widgets;

use app\modules\orders\models\Orders;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class ServiceDropdown extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        $options = '';
        $service = Yii::$app->request->get('service');

        foreach (Orders::getOrdersCountByServices() as $item) {
            $allText = Yii::t('text', 'All');
            $url = Url::current(['service' => $item['id']], true);
            $active = is_numeric($service) && (int)$service === (int)$item['id']
                ? 'class="active"' : '';
            $label = (int)$item['id'] ?
                "<span class='label-id'>{$item['count']}</span> {$item['name']}" :
                "$allText ({$item['count']})";

            $options .= "<li $active><a href='$url'>$label</a></li>";
        }

        return '
        <div class="dropdown">
            <button class="btn btn-th btn-default dropdown-toggle" 
                    type="button" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="true">
                ' . Yii::t('text', 'Service') . '
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">' . $options . '</ul>
        </div>
        ';
    }
}