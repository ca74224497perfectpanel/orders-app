<?php
/**
 * @var $this yii\web\View
 * @var $orderStatuses array
 * @var $searchTypes array
 * @var $status int | null
 * @var $search string | null
 * @var $srtype int | null
 * @var $domain string
 */

use yii\helpers\Html;

$searchPartUrl = empty($search) ?
    '' : "&search=$search&search_type=$srtype";
?>

<ul class="nav nav-tabs p-b">
    <?php if (is_numeric($status)): ?>
        <li>
    <?php else: ?>
        <li class="active">
    <?php endif; ?>
        <a href="<?= "$domain/?status=all"; ?>">
            <?= Yii::t('text', 'orders.status.all'); ?>
        </a>
    </li>
    <?php foreach ($orderStatuses as $statusId => $statusLabel): ?>
        <?php if (is_numeric($status) && (int)$status === $statusId): ?>
            <li class="active">
        <?php else: ?>
            <li>
        <?php endif; ?>
            <a href="<?= Html::encode("$domain/?status=$statusId$searchPartUrl"); ?>">
                <?= $statusLabel; ?>
            </a>
        </li>
    <?php endforeach; ?>
    <li class="pull-right custom-search">
        <form class="form-inline"
              action="<?= $domain; ?>"
              method="get">
            <div class="input-group">
                <input type="text"
                       name="search"
                       class="form-control"
                       value="<?= Html::encode($search); ?>"
                       placeholder="<?= Yii::t('text', 'orders.search.placeholder'); ?>" />
                <?php if (is_numeric($status)): ?>
                    <input type="hidden"
                           name="status"
                           value="<?= $status; ?>" />
                <?php endif; ?>
                <span class="input-group-btn search-select-wrap">
                    <select class="form-control search-select" name="search_type">
                    <?php foreach ($searchTypes as $typeId => $typeLabel): ?>
                        <?php if ((int)$srtype === $typeId): ?>
                            <option value="<?= $typeId; ?>" selected>
                        <?php else: ?>
                            <option value="<?= $typeId; ?>">
                        <?php endif; ?>
                            <?= $typeLabel; ?>
                        </option>
                    <?php endforeach; ?>
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
