<?php

/* @var $this View */
/* @var $content string */

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAsset;
use app\assets\PolyfillsAsset;
use orders\helpers\Utils;

AppAsset::register($this);
PolyfillsAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?= Yii::t('text', 'orders'); ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
        <div class="wrap">
            <div class="container">
                <nav class="navbar navbar-fixed-top navbar-default">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                            <span class="languages">
                                <?php foreach (Utils::getModuleLanguages() as $language): ?>
                                    <?= Html::a($language, Utils::getCurrentUrlWithLang($language),
                                        [
                                            'class' => Yii::$app->language === $language ?
                                                'languages__item_underlined' : ''
                                        ]
                                    ); ?>
                                    <span class="languages__delimiter">&nbsp;|&nbsp;</span>
                                <?php endforeach; ?>
                            </span>
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <a href="<?= Url::current(); ?>">
                                        <?= Yii::t('text', 'orders'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <?= $content ?>
            </div>
        </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
