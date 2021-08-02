<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use app\modules\orders\models\Orders;

class ModeDropdown extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        $options = '';
        $mode = Yii::$app->request->get('order-mode');

        foreach (Orders::getOrderModes() as $key => $value) {
            $url = Url::current(['order-mode' => $key], true);
            $active = is_numeric($mode) && (int)$mode === $key ? 'class="active"' : '';
            $options .= "<li $active><a href='$url'>$value</a></li>";
        }

        return '
        <div class="dropdown">
            <button class="btn btn-th btn-default dropdown-toggle" 
                    type="button" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="true">
                ' . Yii::t('text', 'Mode') . '
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">' . $options . '</ul>
        </div>
        ';
    }
}