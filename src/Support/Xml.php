<?php

namespace Vladmeh\RabbitMQ\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class Xml
{
    use Macroable;

    /**
     * @param array $array
     * @param \SimpleXMLElement $xml
     * @return \SimpleXMLElement
     */
    public static function arrayToXmlAttribute(array $array, \SimpleXMLElement $xml)
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

    public static function arrayToXml(array $array, \SimpleXMLElement $xml)
    {
        array_walk_recursive($array, function ($value, $key) use ($xml) {
            $xml->addChild(Str::upper($key), $value);
        });

        return $xml->asXML();
    }

    /**
     * @param \SimpleXMLElement|\SimpleXMLElement[] $xml
     * @return false|string
     */
    public static function toJson($xml)
    {
        return json_encode(static::toArray($xml), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $data
     * @param null $result
     * @return array|null
     */
    public static function toArray($data, &$result = null)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $res = null;
                static::toArray($value, $res);
                if ($key === '@attributes' && ($key) || is_array($value)) {
                    $result = $res;
                } else {
                    $result[strtolower($key)] = $res;
                }
            }
        } else {
            $result = $data;
        }

        return $result;
    }

}
