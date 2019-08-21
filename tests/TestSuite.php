<?php

/*
 * This file is a part of sigrun/ceidg-api package, a PHP library for to deal
 * with the CEIDG (https://datastore.ceidg.gov.pl) SOAP webservice.
 *
 * @author Marek Kapusta-Ognicki <marek@sigrun.eu>
 * @author Sigrun Sp. z o.o. <sigrun@sigrun.eu>
 * @copy (C)2019 Sigrun Sp. z o.o. All rights reserved.
 */

namespace CeidgApi\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class TestSuite extends TestCase
{
    protected $authToken;
    protected $dotenvLoaded = false;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct();

        if(getenv('CEIDG_AUTH_TOKEN') !== null) {
            $this->authToken = getenv('CEIDG_AUTH_TOKEN');
        }

        if(getenv('DOTENV_LOADED') !== null) {
            $this->dotenvLoaded = 'true' === getenv('DOTENV_LOADED');
        }

    }
}
