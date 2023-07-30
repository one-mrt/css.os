<?
namespace Lang\Main\Css;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class StyleTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'cssStyle';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('id', ['primary' => true, 'autocomplete' => true]),
            new Entity\StringField('site', ['required' => true]),
            new Entity\TextField('style', ['required' => true]),
            new Entity\StringField('md5', ['required' => true]),
            new Entity\StringField('page', ['required' => true]),
            new Entity\DatetimeField('create'),
            new Entity\DatetimeField('update', ['default_value' => new Type\DateTime]),
        ];
    }
}