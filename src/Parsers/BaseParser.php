<?php

/*
 * This file is a part of sigrun/ceidg-api package, a PHP library for to deal
 * with the CEIDG (https://datastore.ceidg.gov.pl) SOAP webservice.
 *
 * @author Marek Kapusta-Ognicki <marek@sigrun.eu>
 * @author Sigrun Sp. z o.o. <sigrun@sigrun.eu>
 * @copy (C)2019 Sigrun Sp. z o.o. All rights reserved.
 */

namespace CeidgApi\Parsers;

use CeidgApi\Contracts\XmlParserContract;
use Exception;
use SimpleXMLElement;
use stdClass;

abstract class BaseParser implements XmlParserContract
{
    /**
     * Make appropriate parser to parse response, based on called function.
     *
     * @param string $callFunctionName
     *
     * @return XmlParserContract
     */
    public static function make($callFunctionName): XmlParserContract
    {
        $parsers = [
            'GetId' => IdParser::class,
            'GetMigrationData201901' => MigrationDataParser::class,
        ];

        if (isset($parsers[$callFunctionName]) && class_exists($parsers[$callFunctionName])) {
            return new $parsers[$callFunctionName]();
        }

        throw new Exception('Parser for function '.$callFunctionName.' does not exist');
    }

    /**
     * Converts array to object recursively.
     *
     * @param array $array
     *
     * @return object
     */
    public function arrayToObjectR(array $array): object
    {
        $obj = new stdClass();
        foreach ($array as $k => $v) {
            if (\strlen($k)) {
                if (\is_array($v)) {
                    $obj->{$k} = $this->arrayToObjectR($v);
                } else {
                    $obj->{$k} = $v;
                }
            }
        }

        return $obj;
    }

    /**
     * Iterate SimpleXMLElement and return it as array without null or empty values.
     *
     * @param SimpleXMLElement $xml
     *
     * @return array
     */
    public function iterateToNull(SimpleXMLElement $xml): array
    {
        $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
            $nodes = $xml->children();

            if (0 === $nodes->count()) {
                return $this->xmlToString($xml);
            }

            foreach ($nodes as $nodeName => $nodeValue) {
                if (false === ($nodeValueParsed = $parser($nodeValue))) {
                    continue;
                }

                if (\count($nodeValue->xpath('../'.$nodeName)) < 2) {
                    $collection[$nodeName] = $nodeValueParsed;
                } else {
                    $collection[$nodeName][] = $nodeValueParsed;
                }
            }

            return $collection;
        };

        return [
            $xml->getName() => $parser($xml),
        ];
    }

    /**
     * Converts xml node with 0 children to string or false if string is empty.
     *
     * @param SimpleXMLElement $xml
     *
     * @throws Exception
     *
     * @return bool|string
     */
    protected function xmlToString(SimpleXMLElement $xml)
    {
        if ($xml->children()->count() > 0) {
            throw new Exception('XML cannot be converted to string');
        }

        $xml = (string) $xml;

        return \strlen($xml) > 0 ? $xml : false;
    }

    /**
     * Converts XML string to structured SimpleXMLElement.
     *
     * @param string $xmlString
     *
     * @throws Exception
     *
     * @return SimpleXMLElement
     */
    protected function xmlToStructure($xmlString): SimpleXMLElement
    {
        if (\function_exists('simplexml_load_string')) {
            return simplexml_load_string($xmlString);
        }

        throw new Exception('SimpleXML is not present');
    }
}
