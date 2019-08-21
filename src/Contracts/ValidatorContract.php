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

interface ValidatorContract
{
    /**
     * Return validator for given param name.
     *
     * @param string $paramName
     *
     * @return ValidatorContract
     */
    public static function getValidator($paramName): self;

    /**
     * Validate $value, return false on entirely false validation
     * or validated content if part of it could be left.
     *
     * @param array|string $value
     *
     * @return array|bool|string
     */
    public function validate($value);

    /**
     * Sanitize multiple types of input values into an array.
     *
     * @param array|string $value
     *
     * @return array
     */
    public function sanitize($value): array;
}
