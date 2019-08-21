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

class MigrationDataParser extends BaseParser
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
            $info = ((array) $this->xmlToStructure($data->GetMigrationData201901Result));

            if (!\is_array($info['InformacjaOWpisie'])) {
                $info['InformacjaOWpisie'] = [$info['InformacjaOWpisie']];
            }

            $info = array_map(function ($el) {
                return $this->arrayToObjectR($this->iterateToNull($el)['InformacjaOWpisie']);
            }, $info['InformacjaOWpisie']);

            return 1 === \count($info) ? $info[array_key_first($info)] : $info;
        } catch (Exception $e) {
            throw new Exception('Data cannot be parsed');
        }
    }
}
