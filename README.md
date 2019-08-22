[![Build Status](https://travis-ci.org/sigrundev/ceidg-api.svg?branch=master)](https://travis-ci.org/sigrundev/ceidg-api) [![Coverage Status](https://coveralls.io/repos/github/sigrundev/ceidg-api/badge.svg?branch=master)](https://coveralls.io/github/sigrundev/ceidg-api?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/e2e75fb45810272e7bf0/maintainability)](https://codeclimate.com/github/sigrundev/ceidg-api/maintainability)

# PHP CEIDG API library
We proudly present a PHP library to connect with CEIDG (Polish registry on businesses) API, using SOAP protocol.

Our library is capable of parsing querying result into well-formatted object or array of objects, validating inputted data (such as VAT number). Build atop the SOLID, DRY, and KISS principles, it provides a comprehensive tool to communicate with CEIDG API.

Please refer to the official API documentation, available at the CEIDG system website at https://datastore.ceidg.gov.pl (you should be logged in to view documentation files). Our library reflects their idea wholly.

## Usage

### Installation

As simple as it can be:
```bash
composer require sigrun/ceidg-api
```

### Declaring API client

To declare API client, create new client class, with authorization token and (optionally) sandbox flag as arguments:

```php
use CeidgApi\CeidgApi;

$authToken = 'secret';

// To connect with the production environment
$ceidgProductionApi = new CeidgApi($authToken);

// To connect with the sandbox environment
$ceidgSandboxApi = new CeidgApi($authToken, true);
```

### Declaring requested function

CEIDG API provides two SOAP functions - _GetId_ and _GetMigrationData201901_. The first returns only companies' unique IDs, whereas the second one - full data on the companies.

```php
$getId = $ceidgProductionApi->getId();

$getMigrationData = $ceidgProductionApi->getMigrationData();
// or
$getMigrationData = $ceidgProductionApi->getMigrationData201901();
```

### Setting query parameters with a simple chain of responsibility pattern

We've implemented a simple chain of responsibility pattern, enabling easy setting up querying params.

```php

// To get IDs of all companies modified or created on August 5th, 2019
$result = $getId->setMigrationDateFrom('2019-08-05')->setMigrationDateTo('2019-08-05')->send();

// To get IDs of all companies having postcode '02-662'
$result = $getId->setPostcode('02-662')->send();
```

### Parsing of response

You can demand on-the-fly parsing of the result. Depending on the number of retrieved entries, you will receive either a single object or an array of them. ```send()``` method has a ```$parse``` argument, default set to ```true```.

An example (abbreviated) of parsed response looks as following:

```json
{
  "IdentyfikatorWpisu": "ff83fff2fc2ab947f78fb6069f1767df",
  "DanePodstawowe": {
    "Imie": "Ryszard",
    "Nazwisko": "Petru",
    "NIP": "8991999655",
    "REGON": "147022306",
    "Firma": "Ryszard Petru Consulting"
  }
}
```
### Single line of code

You can do everything mentioned above, within a single line of code:

```php
$result = (new CeidgApi($authToken))->getId()->setMigrationDateFrom('2019-08-05')->setMigrationDateTo('2019-08-05')->send();
```

### Available params

Available query params are compliant with those described in the official API documentation. A 'UniqueId' param can be set using the 'setUniqueId' method, a 'NIP' param - using the 'setNIP' method, etc.

### Removing param from query

To remove a param from query params array, you can call a method with 'null' as its only argument, like 'SetUniqueId(null)'. There's no difference between method names starting with a capital letter or not.

#### GetMigrationData

|       Param       | Query functions         |     Setter method    |                 Type                 | Has validator? |
|:-----------------:|-------------------------|:--------------------:|:------------------------------------:|----------------|
| DateTo            | GetId, GetMigrationData | SetDateTo            | String ('Y-m-d')                     | not yet        |
| DateFrom          | GetId, GetMigrationData | SetDateFrom          | String ('Y-m-d')                     | not yet        |
| MigrationDateTo   | GetId, GetMigrationData | SetMigrationDateTo   | String ('Y-m-d')                     | not yet        |
| MigrationDateFrom | GetId, GetMigrationData | SetMigrationDateFrom | String ('Y-m-d')                     | not yet        |
| UniqueId          | GetMigrationData        | SetUniqueId          | Array of strings                     | not yet        |
| NIP               | GetMigrationData        | SetNIP               | Array of strings                     | yes            |
| REGON             | GetMigrationData        | SetREGON             | Array of strings                     | yes            |
| NIP_SC            | GetMigrationData        | SetNIP_SC            | Array of strings                     | yes            |
| REGON_SC          | GetMigrationData        | SetREGON_SC          | Array of strings                     | yes            |
| Name              | GetMigrationData        | SetName              | Array of strings                     | no             |
| Province          | GetMigrationData        | SetProvince          | Array of strings                     | no             |
| County            | GetMigrationData        | SetCounty            | Array of strings                     | no             |
| Commune           | GetMigrationData        | SetCommune           | Array of strings                     | no             |
| City              | GetMigrationData        | SetCity              | Array of strings                     | no             |
| Street            | GetMigrationData        | SetStreet            | Array of strings                     | no             |
| Postcode          | GetMigrationData        | SetPostcode          | Array of strings                     | yes            |
| PKD               | GetMigrationData        | SetPKD               | Array of strings                     | yes            |
| Status            | GetMigrationData        | SetStatus            | Array of integers within [1,2,3,4,9] | yes            |

When a SOAP request envelope is sent, all previously set params are cleared.
