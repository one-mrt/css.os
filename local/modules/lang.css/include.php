<?
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'lang.css',
    [
        "Lang\Main\Css\Generate" => "lib/main/css/generate.php",
        "Lang\Main\Css\CssTable" => "lib/main/css/css.php",
        "Lang\Main\Css\StyleTable" => "lib/main/css/style.php",
        "Lang\Main\Css\Modifier" => "lib/main/css/modifier.php",
        // "Lang\Main\Css\Lazy" => "lib/main/css/lazy.css.php",
        // "Lang\Main\Css\LazyCss" => "lib/main/css/lazycss.php",
    ]
);

?>