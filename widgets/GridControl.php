<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use app\modules\orders\models\Orders;

class GridControl extends Widget
{
    public array $orderStatuses;

    public function init(): void
    {
        parent::init();

        if (empty($this->orderStatuses)) {
            $this->orderStatuses = Orders::getOrderStatuses();
        }
    }

    public function run(): string
    {
        $searchOptions = '';
        $domain = Url::canonical();

        $status = Yii::$app->request->get('order-status');
        $search = Yii::$app->request->get('search');
        $srtype = Yii::$app->request->get('search-type');

        $html = "
        <li " . (is_numeric($status) ? '' : 'class="active"') . ">
            <a href='$domain/?order-status=all"  . (empty($search) ? "" : "&search=$search&search-type=$srtype") . "'>
                " . Yii::t('text', 'All orders') . "
            </a>
        </li>
        ";
        foreach ($this->orderStatuses as $key => $label) {
            $active = is_numeric($status) && (int)$status === $key ? 'class="active"' : '';
            $html .= "
                <li $active>
                    <a href='$domain/?order-status=$key" . (empty($search) ? "" : "&search=$search&search-type=$srtype") . "'>
                        $label
                    </a>
                </li>
            ";
        }

        foreach (Orders::getSearchTypes() as $key => $label) {
            $searchOptions .= '<option value="' . $key . '" ' .
                ((int)$srtype === $key ? 'selected' : '').'>' . $label . '</option>';
        }

        return '
         <ul class="nav nav-tabs p-b">' . $html . '
            <li class="pull-right custom-search">
                <form class="form-inline" 
                      action="' . $domain . '" 
                      method="get">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               value="' . $search . '" 
                               placeholder="' . Yii::t('text', 'Search orders') . '" />
                        ' . (is_numeric($status) ? '<input type="hidden" name="order-status" value="' . $status  . '" />' : '') . '
                        <span class="input-group-btn search-select-wrap">
                            <select class="form-control search-select" 
                                    name="search-type" />
                                ' . $searchOptions . '
                            </select>
                            <button type="submit" 
                                    class="btn btn-default">
                                <span class="glyphicon glyphicon-search" 
                                      aria-hidden="true"></span>
                            </button>
                        </span>
                    </div>
                </form>
            </li>
         </ul>
        ';
    }
}