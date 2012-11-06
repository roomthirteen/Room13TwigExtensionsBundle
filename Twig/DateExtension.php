<?php

namespace Room13\TwigExtensionsBundle\Twig;

use \Locale;
use \IntlDateFormatter;
use Room13\TwigExtensionsBundle\Exception\InvalidArgumentException;

class DateExtension extends \Twig_Extension
{

    /**
     * @var string Configurable prefix for all template filter/functions of this extension
     */
    private $prefix;

    /**
     * @var array Mapping of string type representations to IntlDateFormatter constants.
     */
    static $intlTypeMap = array(
        'full'          => IntlDateFormatter::FULL,
        'long'          => IntlDateFormatter::LONG,
        'medium'        => IntlDateFormatter::MEDIUM,
        'short'         => IntlDateFormatter::SHORT,
        'gregorian'     => IntlDateFormatter::GREGORIAN,
        'traditional'   => IntlDateFormatter::TRADITIONAL,
    );

    /**
     * @var array Conversion map to translate php date format to jquery compatible syntax
     */
    static $jqueryPatternMap = array(
        'j' => 'd',
        'd' => 'dd',
        'EEEE' => 'DD',
        'z' => 'o',
        'D' => 'D',
        'l' => 'DD',
        'j' => 'm',
        'n' => 'm',
        'MMM' => 'M',
        'MMMM' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'F' => 'MM',
        'y' => 'yy',
    );

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Gets the intl date format for a given intl date type
     *
     * @param $type Date type, available options: short,medium,long,full,traditional
     * @param $style Style of the patter, php are php style patterns and jquery outputs jquery ui datepicker compatible format
     * @return string date pattern
     */
    public function getDateFormat($type='short',$style='php')
    {
        if(!isset(self::$intlTypeMap[$type]))
        {
            throw new InvalidArgumentException(sprintf(
                'Invalid date format type "%s", valid values are: %s',
                $type,
                implode(',',array_keys(self::$intlTypeMap))
            ));
        }
        $typeConstant = self::$intlTypeMap[$type];

        $locale = Locale::getDefault();
        $formatter = IntlDateFormatter::create($locale,$typeConstant,IntlDateFormatter::NONE);
        $pattern = $formatter->getPattern();

        if($style==='jquery')
        {
            $pattern = str_replace(array_keys(self::$jqueryPatternMap),array_values(self::$jqueryPatternMap),$pattern);
        }

        return $pattern;

    }

    public function getFunctions()
    {
        return array(
            $this->prefix.'date_format'  => new \Twig_Function_Method($this,'getDateFormat'),
        );
    }

    public function getName()
    {
        return 'room13_twig_date_extension';
    }
}
