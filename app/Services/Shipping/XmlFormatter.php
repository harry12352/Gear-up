<?php

namespace App\Services\Shipping;

use Spatie\ArrayToXml\ArrayToXml;

class XmlFormatter
{
    public static function arrayToXml(array $array, array $xmlRootElement = []): string
    {
        $xmlObj = new ArrayToXml($array, $xmlRootElement);

        return $xmlObj->toXml();
    }

    public static function xmlToArray(string $xml)
    {
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);

        $json = json_encode($xml);

        return json_decode($json, true);
    }
}
