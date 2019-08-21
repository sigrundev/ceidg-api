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

use CeidgApi\CeidgApi;
use CeidgApi\Contracts\CeidgApiContract;
use CeidgApi\Contracts\CeidgEnvelopeContract;
use CeidgApi\Envelopes\CeidgEnvelope;
use CeidgApi\Envelopes\GetId;
use CeidgApi\Envelopes\GetMigrationData;
use SoapClient;

final class CeidgTest extends TestSuite
{
    public function testDotenvLoaded()
    {
        $this->assertTrue($this->dotenvLoaded);
        $this->assertNotNull($this->authToken);
    }

    public function testConstruct()
    {
        $ceidgApi = new CeidgApi($this->authToken);

        $this->assertObjectHasAttribute('productionUrl', $ceidgApi);
        $this->assertObjectHasAttribute('sandboxUrl', $ceidgApi);
        $this->assertObjectHasAttribute('client', $ceidgApi);
        $this->assertObjectHasAttribute('apiKey', $ceidgApi);
        $this->assertInstanceOf(CeidgApi::class, $ceidgApi);
        $this->assertInstanceOf(CeidgApiContract::class, $ceidgApi);
    }

    public function testGetClient()
    {
        $ceidgApi = new CeidgApi($this->authToken);

        $this->assertInstanceOf(SoapClient::class, $ceidgApi->getClient());
    }

    public function testGetId()
    {
        $ceidgApi = new CeidgApi($this->authToken);

        $this->assertInstanceOf(CeidgEnvelope::class, $ceidgApi->getId());
        $this->assertInstanceOf(GetId::class, $ceidgApi->getId());
        $this->assertInstanceOf(CeidgEnvelopeContract::class, $ceidgApi->getId());

        $this->assertInstanceOf(CeidgEnvelope::class, $ceidgApi->GetId());
        $this->assertInstanceOf(GetId::class, $ceidgApi->GetId());
        $this->assertInstanceOf(CeidgEnvelopeContract::class, $ceidgApi->GetId());
    }

    public function testGetMigrationData()
    {
        $ceidgApi = new CeidgApi($this->authToken);

        $this->assertInstanceOf(CeidgEnvelope::class, $ceidgApi->getMigrationData());
        $this->assertInstanceOf(GetMigrationData::class, $ceidgApi->getMigrationData());
        $this->assertInstanceOf(CeidgEnvelopeContract::class, $ceidgApi->getMigrationData());

        $this->assertInstanceOf(CeidgEnvelope::class, $ceidgApi->GetMigrationData());
        $this->assertInstanceOf(GetMigrationData::class, $ceidgApi->GetMigrationData());
        $this->assertInstanceOf(CeidgEnvelopeContract::class, $ceidgApi->GetMigrationData());

        $this->assertInstanceOf(CeidgEnvelope::class, $ceidgApi->getMigrationData201901());
        $this->assertInstanceOf(GetMigrationData::class, $ceidgApi->getMigrationData201901());
        $this->assertInstanceOf(CeidgEnvelopeContract::class, $ceidgApi->getMigrationData201901());

        $this->assertInstanceOf(CeidgEnvelope::class, $ceidgApi->GetMigrationData201901());
        $this->assertInstanceOf(GetMigrationData::class, $ceidgApi->GetMigrationData201901());
        $this->assertInstanceOf(CeidgEnvelopeContract::class, $ceidgApi->GetMigrationData201901());
    }

    public function testGetIdParams()
    {
        $params = [
            'DateFrom' => date('Y-m-d'),
            'DateTo' => date('Y-m-d'),
            'MigrationDateFrom' => date('Y-m-d'),
            'MigrationDateTo' => date('Y-m-d'),
        ];

        $getId = (new CeidgApi($this->authToken))->getId();

        foreach ($params as $key => $value) {
            $getId->{'set'.$key}($value);
            $queryParams = $getId->getParams();
            $this->assertSame($queryParams[$key], $params[$key]);
        }

        $queryParams = $getId->getParams(true);
        $this->assertSame($this->authToken, $queryParams['AuthToken']);

        $queryParams = $getId->getParams(false);
        $this->assertFalse(isset($queryParams['AuthToken']));

        $queryParams = $getId->getParams();
        $this->assertFalse(isset($queryParams['AuthToken']));
    }

    public function testGetMigrationDataParamsWithoutListParams()
    {
        $params = [
            'DateFrom' => date('Y-m-d'),
            'DateTo' => date('Y-m-d'),
            'MigrationDateFrom' => date('Y-m-d'),
            'MigrationDateTo' => date('Y-m-d'),
        ];

        $getMigrationData = (new CeidgApi($this->authToken))->getMigrationData();

        foreach ($params as $key => $value) {
            $getMigrationData->{'set'.$key}($value);
            $queryParams = $getMigrationData->getParams();
            $this->assertSame($queryParams[$key], $params[$key]);
        }

        $queryParams = $getMigrationData->getParams(true);
        $this->assertSame($this->authToken, $queryParams['AuthToken']);

        $queryParams = $getMigrationData->getParams(false);
        $this->assertFalse(isset($queryParams['AuthToken']));

        $queryParams = $getMigrationData->getParams();
        $this->assertFalse(isset($queryParams['AuthToken']));
    }

    public function testGetMigrationDataNipParam()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $getMigrationData = (new CeidgApi($this->authToken))->getMigrationData();

        $getMigrationData->setNIP('1132008789');
        $this->assertSame($getMigrationData->getParams()['NIP'], ['1132008789']);

        $getMigrationData->setNIP('1132008789', '1132430459');
        $this->assertSame($getMigrationData->getParams()['NIP'], ['1132008789', '1132430459']);

        $getMigrationData->setNIP(['1132008789', '1132430459']);
        $this->assertSame($getMigrationData->getParams()['NIP'], ['1132008789', '1132430459']);

        // Sorting out faulty NIP numbers
        $getMigrationData->setNIP('1132430455');
        $this->assertFalse(isset($getMigrationData->getParams()['NIP']));

        $getMigrationData->setNIP('1132008785', '1132430459');
        $this->assertSame($getMigrationData->getParams()['NIP'], ['1132430459']);

        $getMigrationData->setNIP(['1132008785', '1132430453']);
        $this->assertFalse(isset($getMigrationData->getParams()['NIP']));
    }

    public function testGetMigrationDataRegonParam()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $getMigrationData = (new CeidgApi($this->authToken))->getMigrationData();

        $getMigrationData->setREGON('141702750');
        $this->assertSame($getMigrationData->getParams()['REGON'], ['141702750']);

        $getMigrationData->setREGON('141702750', '012693590');
        $this->assertSame($getMigrationData->getParams()['REGON'], ['141702750', '012693590']);

        $getMigrationData->setREGON(['141702750', '012693590']);
        $this->assertSame($getMigrationData->getParams()['REGON'], ['141702750', '012693590']);

        // Sorting out faulty REGON numbers
        $getMigrationData->setREGON('141702755');
        $this->assertFalse(isset($getMigrationData->getParams()['REGON']));

        $getMigrationData->setREGON('141702755', '012693590');
        $this->assertSame($getMigrationData->getParams()['REGON'], ['012693590']);

        $getMigrationData->setREGON(['141702755', '012693550']);
        $this->assertFalse(isset($getMigrationData->getParams()['REGON']));
    }

    public function testGetMigrationDataPostcodeParam()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $getMigrationData = (new CeidgApi($this->authToken))->getMigrationData();

        $getMigrationData->setPostcode('04-023');
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['04-023']);

        $getMigrationData->setPostcode('03-075', '04-023');
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['03-075', '04-023']);

        $getMigrationData->setPostcode(['03-075', '04-023']);
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['03-075', '04-023']);

        // Sorting out faulty postcodes
        $getMigrationData->setPostcode('04023');
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['04-023']);

        $getMigrationData->setPostcode('03075   ', '04023');
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['03-075', '04-023']);

        $getMigrationData->setPostcode(['03-075', '040023']);
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['03-075']);

        $getMigrationData->setPostcode([' 03075', '040023']);
        $this->assertSame($getMigrationData->getParams()['Postcode'], ['03-075']);
    }

    public function testGetMigrationDataPKDParam()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $getMigrationData = (new CeidgApi($this->authToken))->getMigrationData();

        $getMigrationData->setPKD('631', '6311Z', '6312');
        $this->assertSame($getMigrationData->getParams()['PKD'], ['631', '6311Z', '6312']);

        // Sorting out faulty PKDs
        $getMigrationData->setPKD('63.11.Z');
        $this->assertSame($getMigrationData->getParams()['PKD'], ['6311Z']);

        $getMigrationData->setPKD('63 11 Z', '63.91Z');
        $this->assertSame($getMigrationData->getParams()['PKD'], ['6311Z', '6391Z']);

        $getMigrationData->setPKD(['63 11 ZZ', '63...91Z', '63/99/Z']);
        $this->assertSame($getMigrationData->getParams()['PKD'], ['6391Z', '6399Z']);
    }

    public function testGetMigrationDataStatusParam()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $getMigrationData = (new CeidgApi($this->authToken))->getMigrationData();

        $getMigrationData->setStatus(1, '2', '3 ', 4, 5, 6, '7', '8', 9, 10);
        $this->assertSame($getMigrationData->getParams()['Status'], [1, 2, 3, 4, 9]);
    }

    public function testGetIdVSM()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $vsm = (new CeidgApi($this->authToken))->getMigrationData()->setNIP('1132430459')->send();

        $this->assertIsNotArray($vsm);
        $this->assertSame('1465011', $vsm->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci->TERC);
        $this->assertSame('-', $vsm->DaneDodatkowe->MalzenskaWspolnoscMajatkowa);
        $this->assertSame('Wykreślony', $vsm->DaneDodatkowe->Status);
        $this->assertSame('Włodzimierz', $vsm->DanePodstawowe->Imie);
    }

    public function testGetIdTwoCompanies()
    {
        /**
         * Initial tests - everything should be ok.
         */
        $vsm = (new CeidgApi($this->authToken))->getMigrationData()->setNIP('1132430459', '1132632105')->send();

        $this->assertIsArray($vsm);
        $this->assertSame('1465011', $vsm[0]->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci->TERC);
        $this->assertSame('-', $vsm[0]->DaneDodatkowe->MalzenskaWspolnoscMajatkowa);
        $this->assertSame('Wykreślony', $vsm[0]->DaneDodatkowe->Status);
        $this->assertSame('Włodzimierz', $vsm[0]->DanePodstawowe->Imie);
        $this->assertSame('1465011', $vsm[1]->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci->TERC);
        $this->assertSame('-', $vsm[1]->DaneDodatkowe->MalzenskaWspolnoscMajatkowa);
        $this->assertSame('Wykreślony', $vsm[1]->DaneDodatkowe->Status);
        $this->assertSame('MAREK', $vsm[1]->DanePodstawowe->Imie);
    }
}
