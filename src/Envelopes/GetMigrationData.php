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

class GetMigrationData extends CeidgEnvelope
{
    /**
     * {@inheritdoc}
     */
    protected $allowedParams = [
        'DateTo' => 'single',
        'DateFrom' => 'single',
        'UniqueId' => 'list',
        'MigrationDateFrom' => 'single',
        'MigrationDateTo' => 'single',
        'NIP' => 'list',
        'REGON' => 'list',
        'NIP_SC' => 'list',
        'REGON_SC' => 'list',
        'Name' => 'list',
        'Province' => 'list',
        'County' => 'list',
        'Commune' => 'list',
        'City' => 'list',
        'Street' => 'list',
        'Postcode' => 'list',
        'PKD' => 'list',
        'Status' => 'list',
        'UniqueId' => 'list',
    ];

    /**
     * {@inheritdoc}
     */
    protected $callFunctionName = 'GetMigrationData201901';
}
