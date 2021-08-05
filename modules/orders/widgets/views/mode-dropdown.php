<?php
/**
 * @var $this yii\web\View
 * @var $orderModes array
 * @var $mode int | null
 */

use yii\helpers\Url;
?>

<div class="dropdown">
    <button class="btn btn-th btn-default dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="true">
        <?= Yii::t('text', 'orders.grid.column.mode'); ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($orderModes as $modeId => $modeLabel): ?>
            <?php if (is_numeric($mode) && (int)$mode === $modeId): ?>
                <li class="active">
            <?php else: ?>
                <li>
            <?php endif; ?>
                <a href="<?= Url::current(['order-mode' => $modeId], true); ?>">
                    <?= $modeLabel; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
