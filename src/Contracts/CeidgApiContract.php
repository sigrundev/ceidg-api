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

use SoapClient;

interface CeidgApiContract
{
    /**
     * Class constructor.
     *
     * @param string $apiKey
     * @param bool   $sandbox
     */
    public function __construct($apiKey, $sandbox = false);

    /**
     * Magic __call method used to set SOAP function class.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return CeidgEnvelopeContract
     */
    public function __call($name, $value): CeidgEnvelopeContract;

    /**
     * Get SoapClient instance.
     *
     * @return SoapClient
     */
    public function getClient(): SoapClient;

    /**
     * Merge query params with api key / authorization token.
     *
     * @param array $params
     *
     * @return array
     */
    public function makeParams($params = []): array;

    /**
     * Return XML parser, depending on called function.
     *
     * @param string $callFunctionName
     *
     * @throws \Exception
     *
     * @return XmlParserContract
     */
    public function getParser($callFunctionName): XmlParserContract;
}
