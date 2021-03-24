<?php

namespace Tests\Unit\Shipping;

use PHPUnit\Framework\TestCase;
use App\Services\Shipping\XmlFormatter;
use TypeError;

class XmlFormatterTest extends TestCase
{
    /** @test */
    public function convertBasicArrayToXml()
    {
        $testArray = [
            'Package' => ['id' => '1']
        ];

        $xml = XmlFormatter::arrayToXml($testArray, []);

        $expectedXml = '<?xml version="1.0"?><root><Package><id>1</id></Package></root>';
        $this->assertEquals($expectedXml, str_replace("\n", '', $xml));
    }

    /** @test */
    public function convert2DArrayToXml()
    {
        $testArray = [
            'Package' => [
                ['Hello' => 'World'],
                ['another' => 'World'],
            ]
        ];

        $xml = XmlFormatter::arrayToXml($testArray, []);
        $expectedXml = '<?xml version="1.0"?><root><Package><Hello>World</Hello></Package><Package>'
        .'<another>World</another></Package></root>';
        
        $this->assertEquals($expectedXml, str_replace("\n", '', $xml));
    }

    /** @test */
    public function emptyArrayToJson()
    {
        $xml = XmlFormatter::arrayToXml([]);
        
        $expectedXml = '<?xml version="1.0"?><root/>';
        $this->assertEquals($expectedXml, str_replace("\n", '', $xml));
    }

    /** @test */
    public function arrayWithInvalidXmlAttributeKey()
    {
        $testArray = [
            'Package' => [
                '_attributes' => 'something'
            ]
        ];

        $this->expectException(TypeError::class);
        $xml = XmlFormatter::arrayToXml($testArray, []);
    }

    /** @test */
    public function emptyXmlToArray()
    {
        $testXml = "";

        $array = XmlFormatter::xmlToArray($testXml);

        $this->assertEmpty($array);
    }

    /** @test */
    public function invalidXmlToArray()
    {
        $testXml = "Hello World";

        $this->expectError();
        XmlFormatter::xmlToArray($testXml);
    }
}
