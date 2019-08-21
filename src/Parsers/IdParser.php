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

use Exception;

class IdParser extends BaseParser
{
    /**
     * Parse raw XML SOAP response.
     *
     * @param string $data
     *
     * @return array|object
     */
    public function parse($data)
    {
        try {
            return (array) $this->xmlToStructure($data->GetIDResult)->IdentyfikatorWpisu;
        } catch (Exception $e) {
            throw new Exception('Data cannot be converted to array');
        }
    }
}
