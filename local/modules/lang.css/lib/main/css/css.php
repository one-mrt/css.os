<?
namespace Lang\Main\Css;

use Bitrix\Main\Entity;

class CssTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'cssModifier';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('id', ['primary' => true, 'autocomplete' => true]),
            new Entity\StringField('point', ['required' => true]),
            new Entity\StringField('event'),
            new Entity\StringField('pseudo'),
            new Entity\StringField('property', ['required' => true]),
            new Entity\StringField('value', ['required' => true]),
            new Entity\StringField('modifier', ['required' => true]),
            new Entity\StringField('style', ['required' => true]),
        ];
    }
}