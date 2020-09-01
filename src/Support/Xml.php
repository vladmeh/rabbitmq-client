<?php


namespace Vladmeh\RabbitMQ\Support;


use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use SimpleXMLElement;

class Xml
{
    use Macroable;

    /**
     * @param array $array
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    public static function arrayToXmlAttribute(array $array, SimpleXMLElement $xml)
    {
        foreach ($array as $key => $value) {
            if (is_string($key) && is_array($value)) {
                $node = $xml->addChild(Str::upper($key));
                static::arrayToXmlAttribute($value, $node);
            } elseif (is_int($key) && is_array($value)) {
                static::arrayToXmlAttribute($value, $xml);
            } else {
                $xml->addAttribute($key, $value);
            }
        }
        return $xml->asXML();
    }

    public static function arrayToXml(array $array, SimpleXMLElement $xml)
    {
        array_walk_recursive($array, function ($value, $key) use ($xml) {
            $xml->addChild(Str::upper($key), $value);
        });

        return $xml->asXML();
    }

}
