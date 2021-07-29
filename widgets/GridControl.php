<?php

namespace app\widgets;

use app\modules\orders\models\Orders;
use yii\base\Widget;

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
        foreach ($this->orderStatuses as $key => $label) {
            $html .= "<li><a href='/?order-status=$key'>$label</a></li>";
        }

        return '
         <ul class="nav nav-tabs p-b">
            <li class="active"><a href="#">All orders</a></li>' . $html . '
            <li class="pull-right custom-search">
                <form class="form-inline" action="#" method="get">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="" placeholder="Search orders">
                        <span class="input-group-btn search-select-wrap">
                            <select class="form-control search-select" name="search-type">
                                <option value="1" selected="">Order ID</option>
                                <option value="2">Link</option>
                                <option value="3">Username</option>
                            </select>
                            <button type="submit" class="btn btn-default">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>
                        </span>
                    </div>
                </form>
            </li>
         </ul>
        ';
    }
}