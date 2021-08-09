<?php

namespace orders\helpers;

use Yii;
use yii\helpers\Url;

class Utils
{
    /**
     * Получение списка поддерживаемых модулем языков.
     * @param string $name
     * @return array
     */
    public static function getModuleLanguages(string $name = 'orders'): array
    {
        $modulePath = Yii::$app->getModule($name)->basePath;
        $moduleLanguagesPath = $modulePath .
            DIRECTORY_SEPARATOR . 'messages' .
            DIRECTORY_SEPARATOR;

        if (is_dir($moduleLanguagesPath) && $dirs = glob(
                "$moduleLanguagesPath*",
                GLOB_ONLYDIR
            )) {
            return array_map(function ($item) {
                return basename($item);
            }, $dirs);
        } else {
            return [];
        }
    }

    /**
     * Формирует текущий URL с указанием языковой настройки.
     * @param string $lang
     * @return string
     */
    public static function getCurrentUrlWithLang(string $lang = 'en'): string
    {
        $params = Yii::$app->request->queryString;

        return Url::base(true) . DIRECTORY_SEPARATOR .
            $lang . (empty($params) ? '' : '?') . $params;
    }
}
