<?php

/*
 * This file is a part of sigrun/ceidg-api package, a PHP library for to deal
 * with the CEIDG (https://datastore.ceidg.gov.pl) SOAP webservice.
 *
 * @author Marek Kapusta-Ognicki <marek@sigrun.eu>
 * @author Sigrun Sp. z o.o. <sigrun@sigrun.eu>
 * @copy (C)2019 Sigrun Sp. z o.o. All rights reserved.
 */

namespace CeidgApi\Contracts;

use SimpleXMLElement;

interface XmlParserContract
{
    /**
     * Make appropriate parser to parse response, based on called function.
     *
     * @param string $callFunctionName
     *
     * @return XmlParserContract
     */
    public static function make($callFunctionName): self;

    /**
     * Parse raw XML SOAP response.
     *
     * @param string $data
     *
     * @return array|object
     */
    public function parse($data);

    /**
     * Iterate SimpleXMLElement and return it as array without null or empty values.
     *
     * @param SimpleXMLElement $xml
     *
     * @return array
     */
    public function iterateToNull(SimpleXMLElement $xml): array;

    /**
     * Converts array to object recursively.
     *
     * @param array $array
     *
     * @return object
     */
    public function arrayToObjectR(array $array): object;
}
