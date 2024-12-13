<?php

use FourthDimension\Dvsa\Facades\Dvsa;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

function jsonResponse(): string
{
    return '[{
        "registration": "ZZ99ABC",
        "make": "FORD",
        "model": "FOCUS",
        "firstUsedDate": "2010.11.13",
        "fuelType": "Petrol",
        "primaryColour": "Yellow",
        "vehicleId": "4Tq319nVKLz+25IRaUo79w==",
        "registrationDate": "2010.11.13",
        "manufactureDate": "2010.11.13",
        "engineSize": "1800",
        "motTests":[
            {
                "completedDate": "2013.11.03 09:33:08",
                "testResult": "PASSED",
                "expiryDate": "2014.10.02",
                "odometerValue": "47125",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "914655760009",
                "rfrAndComments": []
            },
            {
                "completedDate": "2013.11.03 09:33:08",
                "testResult": "PASSED",
                "expiryDate": "2014.11.02",
                "odometerValue": "47125",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "914655760009",
                "rfrAndComments": []
            },
            {
                "completedDate": "2013.11.01 11:28:34",
                "testResult": "FAILED",
                "odometerValue": "47118",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "841470560098",
                "rfrAndComments":[
                    {
                        "text": "Front brake disc excessively pitted (3.5.1h)",
                        "type": "FAIL",
                        "dangerous": true
                    },
                    {
                        "text": "Nearside Rear wheel bearing has slight play (2.6.2)",
                        "type": "ADVISORY",
                        "dangerous": false
                    }
                ]
            },
            {
                "completedDate": "2018.05.20 11:28:34",
                "testResult": "FAILED",
                "odometerValue": "57318",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "741489560458",
                "rfrAndComments":[
                    {
                        "text": "Nearside Parking brake efficiency below requirements (1.4.2 (a) (i))",
                        "type": "MAJOR",
                        "dangerous": false
                    },
                    {
                        "text": "Front brake disc excessively pitted (3.5.1h)",
                        "type": "DANGEROUS",
                        "dangerous": false
                    },
                    {
                        "text": "tyres wearing unevenly",
                        "type": "USER ENTERED",
                        "dangerous": true
                    }
                ]
            }
        ]
    }]';
}


function jsonResponse2(): string
{
    return '[{
        "registration": "ZZ99ABC",
        "make": "FORD",
        "model": "FOCUS",
        "firstUsedDate": "2010.11.13",
        "fuelType": "Petrol",
        "primaryColour": "Yellow",
        "vehicleId": "4Tq319nVKLz+25IRaUo79w==",
        "registrationDate": "2010.11.13",
        "manufactureDate": "2010.11.13",
        "engineSize": "1800",
        "motTests":[
            {
                "completedDate": "2034.12.25 09:33:08",
                "testResult": "PASSED",
                "expiryDate": "2035.12.25",
                "odometerValue": "47125",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "914655760009",
                "rfrAndComments": []
            },
            {
                "completedDate": "2013.11.03 09:33:08",
                "testResult": "PASSED",
                "expiryDate": "2014.11.02",
                "odometerValue": "47125",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "914655760009",
                "rfrAndComments": []
            },
            {
                "completedDate": "2013.11.01 11:28:34",
                "testResult": "FAILED",
                "odometerValue": "47118",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "841470560098",
                "rfrAndComments":[
                    {
                        "text": "Front brake disc excessively pitted (3.5.1h)",
                        "type": "FAIL",
                        "dangerous": true
                    },
                    {
                        "text": "Nearside Rear wheel bearing has slight play (2.6.2)",
                        "type": "ADVISORY",
                        "dangerous": false
                    }
                ]
            },
            {
                "completedDate": "2018.05.20 11:28:34",
                "testResult": "FAILED",
                "odometerValue": "57318",
                "odometerUnit": "mi",
                "odometerResultType": "READ",
                "motTestNumber": "741489560458",
                "rfrAndComments":[
                    {
                        "text": "Nearside Parking brake efficiency below requirements (1.4.2 (a) (i))",
                        "type": "MAJOR",
                        "dangerous": false
                    },
                    {
                        "text": "Front brake disc excessively pitted (3.5.1h)",
                        "type": "DANGEROUS",
                        "dangerous": false
                    },
                    {
                        "text": "tyres wearing unevenly",
                        "type": "USER ENTERED",
                        "dangerous": true
                    }
                ]
            }
        ]
    }]';
}

beforeEach(function () {});


it('can call DVSA API and return result', function () {
    $registration = 'ZZ99ABC';

    Http::fake([
        '*' => Http::response(jsonResponse(), 200),
    ]);

    $result = Dvsa::motHistory($registration);

    assertEquals($registration, $result->registration);

    assertEquals('Yellow', $result->primary_colour);

    assertCount(4, $result->tests);

    $test = $result->tests->first();

    assertTrue($test->passed);

    assertEquals(47125, $test->odometer_value);

    assertCount(0, $test->comments);

    assertCount(2, $result->tests[2]->comments);

    assertEquals('FAIL', $result->tests[2]->comments[0]->type);
});

it('can call DVSA API and determine invalid mot status', function () {
    $registration = 'ZZ99ABC';

    Http::fake([
        '*' => Http::response(jsonResponse(), 200),
    ]);

    $result = Dvsa::motHistory($registration);

    assertEquals('2014/11/02', $result->latestMotExpiryDate()?->format('Y/m/d'));

    assertFalse($result->hasValidMot());
});

it('can call DVSA API and determine valid mot status', function () {
    $registration = 'ZZ99ABC';

    Http::fake([
        '*' => Http::response(jsonResponse2(), 200),
    ]);

    $result = Dvsa::motHistory($registration);

    assertEquals('2035/12/25', $result->latestMotExpiryDate()?->format('Y/m/d'));

    assertTrue($result->hasValidMot());
});
