<?php

namespace app\widgets;

use app\modules\orders\models\Orders;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class GridControl extends Widget
{
    public array $orderStatuses;

    public function init()
    {
        parent::init();

        if (empty($this->orderStatuses)) {
            $this->orderStatuses = Orders::ORDER_STATUSES;
        }
    }

    public function run(): string
    {
        $html = '';
        $domain = Url::canonical();

        $status = Yii::$app->request->get('order-status');
        $search = Yii::$app->request->get('search');
        $srtype = Yii::$app->request->get('search-type');

        foreach ($this->orderStatuses as $key => $label) {
            $active = isset($status) && (int)$status === $key ? 'class="active"' : '';
            $html .= "<li $active><a href='$domain/?order-status=$key'>$label</a></li>";
        }

        return '
         <ul class="nav nav-tabs p-b">
            <li ' . (isset($status) ? '' : 'class="active"') . '>
                <a href="' . $domain . '">All orders</a>
            </li>' . $html . '
            <li class="pull-right custom-search">
                <form class="form-inline" 
                      action="' . $domain . '" 
                      method="get">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               value="' . $search . '" 
                               placeholder="Search orders" />
                        ' . (isset($status) ? '<input type="hidden" name="order-status" value="' . $status . '" />' : '') . '
                        <span class="input-group-btn search-select-wrap">
                            <select class="form-control search-select" 
                                    name="search-type" />
                                <option value="' . Orders::SEARCH_TYPE_ORDER_ID  . '" ' . ((int)$srtype === Orders::SEARCH_TYPE_ORDER_ID  ? 'selected' : '') . '>Order ID</option>
                                <option value="' . Orders::SEARCH_TYPE_LINK      . '" ' . ((int)$srtype === Orders::SEARCH_TYPE_LINK      ? 'selected' : '') . '>Link</option>
                                <option value="' . Orders::SEARCH_TYPE_USER_NAME . '" ' . ((int)$srtype === Orders::SEARCH_TYPE_USER_NAME ? 'selected' : '') . '>Username</option>
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