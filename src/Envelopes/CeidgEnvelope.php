<?php

/*
 * This file is a part of sigrun/ceidg-api package, a PHP library for to deal
 * with the CEIDG (https://datastore.ceidg.gov.pl) SOAP webservice.
 *
 * @author Marek Kapusta-Ognicki <marek@sigrun.eu>
 * @author Sigrun Sp. z o.o. <sigrun@sigrun.eu>
 * @copy (C)2019 Sigrun Sp. z o.o. All rights reserved.
 */

namespace CeidgApi\Envelopes;

use CeidgApi\Contracts\CeidgApiContract;
use CeidgApi\Contracts\CeidgEnvelopeContract;
use CeidgApi\Validators\BaseValidator;
use Exception;

class CeidgEnvelope implements CeidgEnvelopeContract
{
    /**
     * CeidgApi instance.
     *
     * @var CeidgEnvelopeContract
     */
    protected $ceidgApi;

    /**
     * Allowed params array (overriden by extending classes).
     *
     * @var array
     */
    protected $allowedParams = [];

    /**
     * Query params, sent within SOAP envelope.
     *
     * @var array
     */
    protected $params = [];

    /**
     * SOAP function name.
     *
     * @var string
     */
    protected $callFunctionName;

    /**
     * Class constructor.
     *
     * @param CeidgApiContract $ceidgApi
     */
    public function __construct(CeidgApiContract $ceidgApi)
    {
        $this->ceidgApi = $ceidgApi;
    }

    /**
     * Magic __call method used to set query params.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return CeidgEnvelopeContract
     */
    public function __call($name, $value): CeidgEnvelopeContract
    {
        $name = str_replace('set', '', $name);

        if (\in_array($name, array_keys($this->allowedParams), true)) {
            if ('single' === $this->allowedParams[$name]) {
                if (false !== ($validated = $this->validate($name, $this->processSingleParam($value)))) {
                    $this->params[$name] = $validated;
                }
            }
            if ('list' === $this->allowedParams[$name]) {
                if (false !== ($validated = $this->validate($name, $this->processListParam($value)))) {
                    $this->params[$name] = $validated;
                }
            }
        }

        if ((\is_array($this->params[$name]) && 0 === \count($this->params[$name]))
            || !\is_array($this->params[$name]) && '' === $this->params[$name]
            || \is_bool($validated) && false === $validated) {
            try {
                unset($this->params[$name]);
            } catch (Exception $e) {
            }
        }

        return $this;
    }

    /**
     * Return query params with or without auth token. Auth token would be
     * returned when $merged param is true.
     *
     * @param bool $merged
     *
     * @return array
     */
    public function getParams($merged = false): array
    {
        return $merged ? $this->ceidgApi->makeParams($this->params) : $this->params;
    }

    /**
     * Send SOAP envelope and (optionally) parse it immediately
     * Return string with raw XML response or parsed array/object.
     *
     * @param bool $parse
     *
     * @throws Exception
     *
     * @return array|object|string
     */
    public function send($parse = true)
    {
        $params = $this->ceidgApi->makeParams($this->params);

        $client = $this->ceidgApi->getClient();

        try {
            $result = $client->__call($this->callFunctionName, ['body' => $params]);
        } catch (Exception $e) {
            throw $e;
        }

        $this->params = [];

        if (!$parse) {
            return $result;
        }

        return $this->ceidgApi->getParser($this->callFunctionName)->parse($result);
    }

    /**
     * Validate value against param name.
     * Return false on entirely negative validation, or validated content
     * if some values could be left.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return array|bool|string
     */
    public function validate($name, $value)
    {
        return BaseValidator::getValidator($name)->validate($value);
    }

    /**
     * Process list param from __call method's $value and return it as array.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function processListParam($value): array
    {
        return \is_array($value[0]) ? $value[0] : (!isset($value[1]) ? [$value[0]] : $value);
    }

    /**
     * Process single param from __call method's value and return it as string.
     * Method retrieves only first argument if more than one were given.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function processSingleParam($value): string
    {
        return (string) (\is_array($value[0]) ? $value[0][0] : $value[0]);
    }
}
