<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

class ServiceDropdown extends Widget
{
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
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
            <ul class="dropdown-menu">
                <li class="active"><a href="">All (894931)</a></li>
                <li><a href=""><span class="label-id">214</span>  Real Views</a></li>
                <li><a href=""><span class="label-id">215</span> Page Likes</a></li>
                <li><a href=""><span class="label-id">10</span> Page Likes</a></li>
                <li><a href=""><span class="label-id">217</span> Page Likes</a></li>
                <li><a href=""><span class="label-id">221</span> Followers</a></li>
                <li><a href=""><span class="label-id">224</span> Groups Join</a></li>
                <li><a href=""><span class="label-id">230</span> Website Likes</a></li>
            </ul>
        </div>
        ';
    }
}