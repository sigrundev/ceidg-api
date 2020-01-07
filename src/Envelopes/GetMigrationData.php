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

/**
 * Class GetMigrationData
 *
 * @method self setDateTo(string $date)
 * @method self setDateFrom(string $date)
 * @method self setUniqueId(string ...$args)
 * @method self setMigrationDateFrom(string $date)
 * @method self setMigrationDateTo(string $date)
 * @method self setNIP(string ...$args)
 * @method self setREGON(string ...$args)
 * @method self setNIP_SC(string ...$args)
 * @method self setREGON_SC(string ...$args)
 * @method self setName(string ...$args)
 * @method self setProvince(string ...$args)
 * @method self setCounty(string ...$args)
 * @method self setCommune(string ...$args)
 * @method self setCity(string ...$args)
 * @method self setStreet(string ...$args)
 * @method self setPostcode(string ...$args)
 * @method self setPKD(string ...$args)
 * @method self setStatus(string ...$args)
 */
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
    ];

    /**
     * {@inheritdoc}
     */
    protected $callFunctionName = 'GetMigrationData201901';
}
