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

class GetId extends CeidgEnvelope
{
    /**
     * {@inheritdoc}
     */
    protected $allowedParams = [
        'DateTo'            => 'single',
        'DateFrom'          => 'single',
        'MigrationDateFrom' => 'single',
        'MigrationDateTo'   => 'single',
    ];

    /**
     * {@inheritdoc}
     */
    protected $callFunctionName = 'GetId';
}
