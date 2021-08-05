<?php
/**
 * @var $this yii\web\View
 * @var $countByServices array
 * @var $service int | null
 */

use yii\helpers\Url;
?>

<div class="dropdown">
    <button class="btn btn-th btn-default dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="true">
        <?= Yii::t('text', 'orders.grid.column.service'); ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($countByServices as $item): ?>
            <?php if (is_numeric($service) && (int)$service === (int)$item['id']): ?>
                <li class="active">
            <?php else: ?>
                <li>
            <?php endif; ?>
                <a href="<?= Url::current(['service' => $item['id']], true); ?>">
                    <?php if ((int)$item['id']): ?>
                        <span class="label-id">
                            <?= $item['count']; ?>
                        </span> <?= $item['name']; ?>
                    <?php else: ?>
                        <?= Yii::t('text', 'orders.filter.all'); ?>
                        (<?= $item['count']; ?>)
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>