<?php


namespace Vladmeh\RabbitMQ\Support;


use SimpleXMLElement;

class MessageRequest
{
    /**
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function xmlAttribute(string $type, array $attributes = []): string
    {
        $message = new SimpleXMLElement('<REQUEST/>');
        $message->addAttribute('type', $type);

        if (!empty($attributes)) {
            Xml::arrayToXmlAttribute($attributes, $message);
        }

        return $message->asXML();
    }

    /**
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function xml(string $type, array $attributes = []): string
    {
        $message = new SimpleXMLElement('<REQUEST/>');
        $message->addAttribute('type', $type);

        if (!empty($attributes)) {
            Xml::arrayToXml($attributes, $message);
        }

        return $message->asXML();
    }

    public function __call($method, $parameters)
    {
        return static::$method($parameters);
    }
}
