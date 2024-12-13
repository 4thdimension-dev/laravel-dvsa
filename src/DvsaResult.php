<?php

namespace FourthDimension\Dvsa;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

//Singular Result - could be multiple in response (as returns an array)
class DvsaResult
{
    public string $registration;

    public string $make;

    public string $model;

    public Carbon $first_used_date;

    public string $fuel_type;

    public string $primary_colour;

    public string $engine_size;

    public string $vehicle_id;

    public Carbon $manufacture_date;

    public Carbon $registration_date;


    public Collection $tests;

    public function __construct(array $rawData)
    {
        $rawCollection = collect($rawData);

        $this->registration = $rawCollection->get('registration');
        $this->make = $rawCollection->get('make');
        $this->model = $rawCollection->get('model');
        $this->first_used_date = $rawCollection->get('firstUsedDate') ? Carbon::createFromFormat('Y.m.d', $rawCollection->get('firstUsedDate'))->startOfDay() : null;
        $this->fuel_type = $rawCollection->get('fuelType');
        $this->primary_colour = $rawCollection->get('primaryColour');
        $this->vehicle_id = $rawCollection->get('vehicleId');
        $this->registration_date = $rawCollection->get('registrationDate') ? Carbon::createFromFormat('Y.m.d', $rawCollection->get('registrationDate'))->startOfDay() : null;
        $this->manufacture_date = $rawCollection->get('manufactureDate') ? Carbon::createFromFormat('Y.m.d', $rawCollection->get('manufactureDate'))->startOfDay() : null;
        $this->engine_size = $rawCollection->get('engineSize');

        $this->tests = collect($rawCollection->get('motTests'))->map(function (array $testData) {
            return new MotTest($testData);
        });
    }

    public function hasValidMot(): bool
    {
        return $this->latestMotExpiryDate() > now();
    }

    public function latestMotExpiryDate(): ?Carbon
    {
        $testExpiryDates = $this->tests->pluck('expiry_date')->filter()->sortDesc()->values();

        if ($testExpiryDates->isEmpty()) {
            return null;
        }

        return $testExpiryDates->first();
    }
}
