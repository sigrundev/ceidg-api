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

class PostcodeValidator extends BaseValidator
{
    /**
     * Validate $value, return false on entirely false validation
     * or validated content if part of it could be left.
     *
     * @param array|string $value
     *
     * @return array|bool|string
     */
    public function validate($value): array
    {
        $validated = [];
        foreach ($value as $singleValue) {
            if (false !== ($validatedSingleValue = $this->postcode($singleValue))) {
                $validated[] = $validatedSingleValue;
            }
        }

        return $validated;
    }

    /**
     * Validate postcode.
     *
     * @param string $value
     *
     * @return bool|string
     */
    public function postcode($value)
    {
        $value = preg_replace('/[^0-9]+/', '', $value);

        if (5 !== \strlen($value)) {
            return false;
        }

        return substr($value, 0, 2).'-'.substr($value, 2, 3);
    }
}
