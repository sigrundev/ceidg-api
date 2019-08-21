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

class StatusValidator extends BaseValidator
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
            if (false !== ($validatedSingleValue = $this->status($singleValue))) {
                $validated[] = $validatedSingleValue;
            }
        }

        return $validated;
    }

    /**
     * Validate status.
     *
     * @param string $value
     *
     * @return bool|int
     */
    public function status($value)
    {
        $value = preg_replace('/[^0-9]+/', '', $value) * 1;

        return 1 === $value || 2 === $value || 3 === $value || 4 === $value || 9 === $value ? $value : false;
    }
}
