<?
namespace Lang\Main\Css;

use Lang\Main\Css\StyleTable;
use Lang\Main\Css\CssTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Web\Uri;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class Modifier {

    public array $styleOut = [
        'xs' => '',
        'sm' => '',
        'md' => '',
        'lg' => '',
        'xl' => '',
        'hover' => '',
        'focus' => '',
        'active' => '',
        'visited' => '',
        'target' => '',
        'focus-within' => '',
        'focus-visible' => '',
    ];

    private array $defaultWidth = [
        'sm' => [
            'min' => 540,
            'max' => 576,
        ],
        'md' => [
            'min' => 720,
            'max' => 768,
        ],
        // 'lg' => [
        //     'min' => 960,
        //     'max' => 992,
        // ],
        'lg' => [
            'min' => 1140,
            'max' => 1200,
        ],
        'xl' => [
            'min' => 1320,
            'max' => 1400,
        ]
    ];
    private int $baseFont = 16;
    private $sm = false;
    private $md = false;
    private $lg = false;
    private $xl = false;
    public static array $container = ['sm' => false,'md' => false,'lg' => false,'xl' => false];
    public static array $endModifier = [];
    public static $currentModifier = [
        'xs' => [],
        'sm' => [],
        'md' => [],
        'lg' => [],
        'xl' => [],
        'hover' => [],
        'focus' => [],
        'active' => [],
        'visited' => [],
        'target' => [],
        'focus-within' => [],
        'focus-visible' => [],
    ];

    public function __construct(?array $paramInit = [])
    {
        foreach($paramInit as $k => $i)
        {
            if(!empty($i) && !is_array($i))
            {
                $this->{$k} = $i;
            }
            # TODO:
            #   array
            else if(empty($i) && !is_array($i))
            {
                #foreach($i as $kI => $iI)
                #{
                $this->{$k} = $i;
                #}
            }
        }
        $this->container();
    }

    private function container()
    {
        $this->styleOut['xs'] .= ".container{width:100%;padding-right:var(--bs-gutter-x, 1rem);padding-left:var(--bs-gutter-x, 1rem);margin-right:auto;margin-left:auto}";
        
        if(!empty($this->sm))
        {
            $this->initBreakpoint('sm');
        }

        if(!empty($this->md))
        {
            $this->initBreakpoint('md');
        }

        if(!empty($this->lg))
        {
            $this->initBreakpoint('lg');
        }

        if(!empty($this->xl))
        {
            $this->initBreakpoint('xl');
        }
    }

    private function initBreakpoint(string $point='')
    {
        if(!empty($point))
        {
            if(empty(self::$container[$point]))
            {
                $width = $this->defaultWidth[$point];
                $min = (round(($width['min'] / $this->baseFont), 4));
                $max = (round(($width['max'] / $this->baseFont), 4));
                self::$container[$point] = ['min' => "{$min}rem",'max' => "{$max}rem"];
                $this->styleOut[$point] .= ".container{max-width:{$min}rem}";
            }
            else
            {
                ${$point} =& self::$container[$point];
                $min = (round((${$point}['min'] / $this->baseFont),4));
                $max = (round((${$point}['max'] / $this->baseFont),4));
                self::$container[$point] = ['min' => "{$min}rem",'max' => "{$max}rem"];
                $this->styleOut[$point] .= ".container{max-width:{$min}rem}";
            }
        }
    }

    public function getStyleBreakpoint(array $list, array $param, string $point, string $prefix, string $style) : string
    {
        $cssGlobalOut = "";
        $prefixPoint = ($point == 'xs') ? '' : "{$point}\:";
        $event = ($point == 'hover' || $point == 'focus' || $point == 'active') ? ":{$point}" : "";

        foreach($list as $mod => $styleValue)
        {
            if($param['type'] == 'number')
            {
                $unit = $param['unit'] ? $param['unit'] : 'rem';

                if(gettype($styleValue) == 'integer')
                {
                    $styleValue = ($styleValue !== 0) ? $styleValue / $this->baseFont : $styleValue;
                    $styleValue = "{$styleValue}{$unit}";
                }
                else
                {
                    $styleValue = "{$styleValue}";
                }
            }

            $this->styleOut[$point] .= ".{$prefixPoint}{$prefix}{$mod}{$event}{{$style}:{$styleValue}}";
        }

        return $cssGlobalOut;
    }

    public static function isMobile()
    {
        if(!empty($_SERVER['HTTP_USER_AGENT']))
        {
            $useragent = $_SERVER['HTTP_USER_AGENT'];

            if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * 
     */
    public function generate(array $css)
    {
        foreach($css as $style => $modifier)
        {
            $prefix = (!empty($modifier['prefix'])) ? "{$modifier['prefix']}-" : "";

            foreach($modifier['breakpoint'] as $point => $listModifier)
            {   
                $event = ($point == 'hover' || $point == 'focus' || $point == 'active') ? ":{$point}" : "";
                $prefixPoint = ($point == 'xs') ? '' : "{$point}\:";
                $pointMod = ($point == 'xs') ? '' : "{$point}:";

                foreach($listModifier as $mod => $styleValue)
                {
                    if($modifier['type'] == 'number')
                    {
                        $unit = $modifier['unit'] ? $modifier['unit'] : 'rem';

                        if(gettype($styleValue) == 'integer')
                        {
                            $styleValue = ($styleValue !== 0) ? $styleValue / $this->baseFont : $styleValue;
                            $styleValue = "{$styleValue}{$unit}";
                        }
                        else
                        {
                            $styleValue = "{$styleValue}";
                        }
                    }

                    $modString = preg_replace('#\\\\#','',$mod);
                    $modString = preg_replace('/[^a-z\-:[0-9]]\//','',$modString);

                    self::$endModifier[$point][$style]["{$pointMod}{$prefix}{$modString}"]['value'] = "{$styleValue}";
                    self::$endModifier[$point][$style]["{$pointMod}{$prefix}{$modString}"]['style'] = ".{$prefixPoint}{$prefix}{$mod}{$event}{{$style}:{$styleValue}}";
                }
            }
        }
    }

    private static function search(string $mod, ?array $endModifier=[],?string $result="",string $point='xs') : string
    {
        if(!empty($endModifier))
        {
            foreach($endModifier as $k => $i)
            {
                if(empty($i['style']))
                {
                    $result = self::search($mod, $i, $result, $point);
                }
                else
                {
                    if($k === $mod)
                    {
                        self::$currentModifier[$point][$k] = $i['style'];
                        return $mod;
                    }
                }
            }
        }
        else
        {
            foreach(self::$endModifier as $k => $i)
            {
                if(empty($i['style']))
                {
                    $result = self::search($mod, $i, $result, $k);
                }
                else
                {
                    if($k == $mod)
                    {
                        self::$currentModifier[$point][$k] = $i['style'];
                        return $mod;
                    }
                }
            }
        }

        return $result;
    }

    public static function get(string $css) : string
    {
        $outModifier = "";
        $css = explode(" ", $css);

        if(is_array($css))
        {
            foreach($css as $k => $i)
            {
                if(!empty($i))
                {
                    unset($css[$k]);
                    $search = self::search($i);
                    $outModifier .= $search;
                    $outModifier .= (!empty($css) && !empty($search)) ? " " : "";
                }
            }
        }

        return $outModifier;
    }

    private static function priorityStyleSort()
    {
        $priorityStyleSort = [];

        if(!empty(self::$currentModifier))
        {
            foreach(self::$currentModifier as $k => $i)
            {
                $localStyle = "";

                foreach($i as $modifier => $style)
                {

                }
            }
        }
    }

    public static function outStyle() : string
    {
        global $USER;
        
        $request = Application::getInstance()->getContext()->getRequest();
        $uri = new Uri($request->getRequestUri());
        $url = $uri->getPath();
        $clearCache = $request->getQuery("clear_cache");
        $scan = (($clearCache == 'Y') && ($USER->IsAdmin())) ? true : false;

        if(!empty(self::$currentModifier) && $scan)
        {
            $stringStyle = "<style lang-generator>";

            foreach(self::$currentModifier as $k => $i)
            {
                $localStyle = "";

                foreach($i as $modifier => $style)
                {
                    $localStyle .= "{$style}";
                }

                switch($k)
                {
                    case 'xs':
                    case 'hover':
                    case 'focus':
                    case 'focus-visible':
                    case 'active':
                        $stringStyle .= "{$localStyle}";
                        break;
                    default:
                        if(!empty($localStyle))
                        {
                            $max =& self::$container[$k]["max"];
                            $stringStyle .= "@media(min-width:{$max}){{$localStyle}}";
                        }
                        break;
                }
            }

            $stringStyle .= '</style>';

            $styleTableId = StyleTable::getList([
                'select' => ['id', 'md5', 'page'],
                'filter' => ['site' => SITE_ID, 'page' => $url],
                'cache' => ['ttl' => (1000 * 60) * 60 * 24 * 365]
            ])->fetch();

            if(!empty($styleTableId['id']))
            {
                if($styleTableId['md5'] != md5($stringStyle))
                {
                    StyleTable::update($styleTableId['id'], [
                        'site' => SITE_ID,
                        'style' => $stringStyle,
                        'md5' => md5($stringStyle)
                    ]);
                }
            }
            else
            {
                StyleTable::add([
                    'site' => SITE_ID,
                    'style' => $stringStyle,
                    'md5' => md5($stringStyle),
                    'create' => new DateTime,
                    'page' => $url,
                ]);
            }
        }
        else
        {
            $stringStyle = StyleTable::getList([
                'select' => ['style'],
                'filter' => ['site' => SITE_ID, 'page' => $url],
                'cache' => ['ttl' => (1000 * 60) * 60 * 24 * 365]
            ])->fetch()['style'];

            $stringStyle = $stringStyle ? "{$stringStyle}" : "";
        }

        return $stringStyle;
    }


    public static function addStyleHead()
    {
        global $APPLICATION;
        $APPLICATION->AddViewContent('StringStyle', self::outStyle());
    }

    public function addStyleBase()
    {
        $addS = [];
        $addId = [];

        foreach(self::$endModifier as $k => $i)
        {
            $add = [];

            switch($k)
            {
                case 'hover':
                case 'focus':
                case 'active':
                case 'visited':
                case 'target':
                case 'focus-within':
                case 'focus-visible':
                    $add['event'] = $k;
                    $add['point'] = 'xs';
                    break;
                case 'xs':
                case 'sm':
                case 'md':
                case 'lg':
                case 'xl':
                    $add['point'] = $k;
                    break;
            }

            foreach($i as $j => $p)
            {
                $add['property'] = $j;

                foreach($p as $m => $s)
                {
                    $add['modifier'] = $m;
                    $add['style'] = $s['style'];
                    $add['value'] = $s['value'];

                    $result = CssTable::add($add);

                    if($result->isSuccess())
                    {
                        $addId[] = $result->getId();
                    }
                    else
                    {
                        $addId[] = $result->getErrorMessages();
                    }
                }
            }
        }
    }

}