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
        return json_encode(self::toArray($xml), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    /**
     * @param $data
     * @param null $result
     * @param int $recursionDepth
     * @return array|null
     */
    public static function toArray($data, &$result = null, &$recursionDepth = 0)
    {
        if (is_object($data)) {
            if ($recursionDepth == 0) {
                $callerProviderObject = $data;
            }
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $res = null;

                $recursionDepth++;
                self::toArray($value, $res, $recursionDepth);
                $recursionDepth--;

                if ($key === '@attributes' && ($key) || is_array($value)) {
                    $result = $res;
                } else {
                    $result[strtolower($key)] = $res;
                }
            }

            if ($recursionDepth == 0) {
                $temp = $result;
                $result = [];
                if (isset($callerProviderObject)) {
                    $result[strtolower($callerProviderObject->getName())] = $temp;
                } elseif (is_array($temp) && count($temp) == 1 && array_key_exists(0, $temp)) {
                    $result = $temp[array_key_first($temp)];
                } else {
                    $result = $temp;
                }
            }

        } else {
            $result = $data;
        }

        return $result;
    }
}
