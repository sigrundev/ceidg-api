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

class REGONValidator extends BaseValidator
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
            if (false !== ($validatedSingleValue = $this->regon($singleValue))) {
                $validated[] = $validatedSingleValue;
            }
        }

        return $validated;
    }

    /**
     * Validate REGON.
     *
     * @param string $value
     *
     * @return bool|string
     */
    public function regon($value)
    {
        if (9 !== \strlen($value)) {
            return false;
        }

        $arrSteps = [8, 9, 2, 3, 4, 5, 6, 7];
        $intSum = 0;

        for ($i = 0; $i < 8; $i++) {
            $intSum += $arrSteps[$i] * $value[$i];
        }

        $int = $intSum % 11;
        $intControlNr = (10 === $int) ? 0 : $int;

        if ($intControlNr === (int) $value[8]) {
            return $value;
        }

        return false;
    }
}
