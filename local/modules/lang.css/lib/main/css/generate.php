<?
namespace Lang\Main\Css;

class Generate
{
    /**
     * Список сгенерированных модификаторов
     *
     * @var array
     */
    public array $modifierEnd = [];
    public array $modifierDouble = [];

    /**
     * Контрольные точки
     *
     * @var array
     */
    private array $breakpoint = ['xs','sm','md','lg','xl'];

    private $event = [];
    private $pseudoclass = [];

    /**
     * Инициализация генерации
     *
     * @param array $cssStyleProeprty список свойств/значений CSS
     */
    public function __construct(array $cssStyleProeprty)
    {
        $this->generate($cssStyleProeprty);
    }

    /**
     * Берем перый символ из слова
     *
     * @param string $word
     * @return string
     */
    private function letterStart(string $word) : string
    {
        return substr($word, 0, 1);
    }
    
    /**
     * Берем перый и последний символ из слова
     *
     * @param string $word
     * @return string
     */
    private function letterStartEnd(string $word) : string
    {
        $modifier = "";
        $modifier .= substr($word, 0, 1);
        $modifier .= substr($word, -1, 1);

        return $modifier;
    }

    /**
     * Undocumented function
     *
     * @param string $word
     * @return string
     */
    private function letterStartCenterEnd(string $word) : string
    {
        $modifier = "";
        $indexChar = (strlen($word) % 2 == 0) ? round(strlen($word) / 2, 0) : round(strlen($word) / 2, 0) + 1;
        $modifier .= substr($word, 0, 1);
        $modifier .= substr($word, $indexChar, 1);
        $modifier .= substr($word, -1, 1);

        return $modifier;
    }

    /**
     * Первое правило - только первый символ
     *
     * @param array $value
     * @return string
     */
    private function ruleStart(array $value = []) : string
    {
        $stringEnd = "";

        foreach($value as $v)
        {
            $stringEnd .= $this->letterStart($v);
        }

        return $stringEnd;
    }

    /**
     * Второе правило - первый символ у первых слов и
     * первый и последний символ у последнего слова
     *
     * @param array $value
     * @return string
     */
    private function ruleStartEnd(array $value=[]) : string
    {
        $stringEnd = "";

        foreach($value as $k => $v)
        {
            if(count($value) > 0)
            {
                if(count($value) == ($k+1))
                {
                    $stringEnd .= $this->letterStartEnd($v);
                }
                else
                {
                    $stringEnd .= $this->letterStart($v);
                }
            }
            else
            {
                $stringEnd .= $this->letterStartEnd($v);
            }
        }

        return $stringEnd;
    }

    /**
     * Третье правило первый символ у первых слов и
     * первый и последний символы у двух последних слов
     *
     * @param array $value
     * @return string
     */
    private function ruleStartEndTwo(array $value=[]) : string
    {
        $stringEnd = "";

        foreach($value as $k => $v)
        {
            if(count($value) > 0)
            {
                if((count($value) == ($k+1)) || (count($value)-1 == ($k+1)))
                {
                    $stringEnd .= $this->letterStartEnd($v);
                }
                else
                {
                    $stringEnd .= $this->letterStart($v);
                }
            }
            else
            {
                $stringEnd .= $this->letterStartEnd($v);
            }
        }

        return $stringEnd;
    }

    /**
     * Четвертое правило
     *
     * @param array $value
     * @return string
     */
    private function ruleStartCenterEnd(array $value=[]) : string
    {
        $stringEnd = "";

        foreach($value as $k => $v)
        {
            if(count($value) > 0)
            {
                if(count($value) == ($k+1))
                {
                    $stringEnd .= $this->letterStartCenterEnd($v);
                }
                else
                {
                    $stringEnd .= $this->letterStart($v);
                }
            }
            else
            {
                $stringEnd .= $this->letterStartCenterEnd($v);
            }
        }

        return $stringEnd;
    }

    /**
     * Применение правил
     *
     * @param array $valueArray список слов свойства/значения
     * @param integer $rule порядковый номер правила
     * @return string сгенерированное значение
     */
    private function useRule(array $valueArray=[], int $rule=0) : string
    {
        $useRuleModifier = "";

        switch($rule)
        {
            case 0:
                return $this->ruleStart($valueArray);
                break;
            case 1:
                return $this->ruleStartEnd($valueArray);
                break;
            case 2:
                return $this->ruleStartEndTwo($valueArray);
                break;
            case 3:
                return $this->ruleStartCenterEnd($valueArray);
                break;
        }
        
        return $useRuleModifier;
    }

    /**
     * Генерация значиний
     *
     * @param array $arValue
     * @param array $valueEnd
     * @return array
     */
    private function valueToModifier(array $arValue=[], array $valueEnd=[]) : array
    {
        if(!empty($arValue))
        {
            foreach($arValue as $value => $v)
            {
                $valueArray = explode('-', $value);
                $indexRepeat = 0;
                $stringEnd = $this->useRule($valueArray, $indexRepeat);

                if(empty($valueEnd))
                {
                    $valueEnd[$stringEnd] = $value;
                }
                else
                {
                    $isValue = $this->isModifierDouble($stringEnd, $valueEnd, $valueArray, null, $indexRepeat, $value);

                    // TODO:
                    // Удалить
                    if(!empty($isValue))
                    {

                    }
                }
            }
        }
        else
        {
            return [];
        }

        return $valueEnd;
    }

    /**
     * Поиск дублирующихся модификаторов
     *
     * @param string $propertyEnd сгенерированный модификатор
     * @param array $modifierEnd список сгенерированных модификаторов
     * @param array|null $propertyArray список слов свойства/значения
     * @param array|null $arrayValue список значений
     * @param integer $indexRepeat порядковый номер правила
     * @param string $propertyValue свойство CSS
     * @return string сгенерированное свойство/значение
     */
    private function isModifierDouble(string $propertyEnd, array &$modifierEnd, ?array $propertyArray, ?array $arrayValue, int $indexRepeat, string $propertyValue) : string
    {
        if(!array_key_exists($propertyEnd, $modifierEnd))
        {
            if(!empty($arrayValue))
            {
                $modifierEnd[$propertyEnd]['name'] = $propertyValue;
                $modifierEnd[$propertyEnd]['value'] = $this->valueToModifier($arrayValue, []);
            }
            else
            {
                $modifierEnd[$propertyEnd] = $propertyValue;
            }

            return "";
        }
        else
        {
            $isModifier = $this->useRule($propertyArray, $indexRepeat++);

            if(!empty($isModifier))
            {
                return $this->isModifierDouble($isModifier, $modifierEnd, $propertyArray, $arrayValue, $indexRepeat++, $propertyValue);
            }
        }

        return $propertyEnd;
    }

    /**
     * Генерация полного модификатора
     *
     * @param array $cssStyleProeprty список свойств
     * @return array конечный список сгенерированных модификаторов свойств/значений
     */
    private function generate(array $cssStyleProeprty) : array
    {
        foreach($cssStyleProeprty as $k => $i)
        {
            $propertyArray = explode('-', $k);
            $indexRepeat = 0;
            $propertyEnd = $this->useRule($propertyArray, $indexRepeat);

            if(empty($this->modifierEnd))
            {
                $this->modifierEnd[$propertyEnd]['name'] = $k;
                $this->modifierEnd[$propertyEnd]['value'] = $this->valueToModifier($i, []);
            }
            else
            {
                $isModifier = $this->isModifierDouble($propertyEnd, $this->modifierEnd, $propertyArray, $i, $indexRepeat, $k);

                if(!empty($isModifier))
                {
                    $this->modifierDouble[$isModifier]['name'] = $k;
                }
            }
        }

        return ['end' => $this->modifierEnd, 'double' => $this->modifierDouble];
    }
}