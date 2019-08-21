<?php

/*
 * This file is a part of sigrun/ceidg-api package, a PHP library for to deal
 * with the CEIDG (https://datastore.ceidg.gov.pl) SOAP webservice.
 *
 * @author Marek Kapusta-Ognicki <marek@sigrun.eu>
 * @author Sigrun Sp. z o.o. <sigrun@sigrun.eu>
 * @copy (C)2019 Sigrun Sp. z o.o. All rights reserved.
 */

namespace CeidgApi\Validators;

use CeidgApi\Contracts\ValidatorContract;

abstract class BaseValidator implements ValidatorContract
{
    /**
     * Return validator for given param name.
     *
     * @param string $paramName
     *
     * @return ValidatorContract
     */
    public static function getValidator($paramName): ValidatorContract
    {
        $validatorClassName = '\\CeidgApi\\Validators\\'.$paramName.'Validator';

        return class_exists($validatorClassName) ? new $validatorClassName() : new EmptyValidator();
    }

    /**
     * Sanitize multiple types of input values into an array.
     *
     * @param array|string $value
     *
     * @return array
     */
    public function sanitize($value): array
    {
        if (!\is_array($value)) {
            return [(string) $value];
        }

        return $value;
    }
}
