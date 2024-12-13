<?php

namespace FourthDimension\Dvsa;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Dvsa
{
    public string $endpoint = '/trade/vehicles/mot-tests';

    public string $registration;

    public function motHistory(string $registration): DvsaResult
    {
        $this->registration = $registration;

        $response = $this->sendRequest();

        return new DvsaResult($response->json()[0]);
    }

    public function fullUrl(): string
    {
        return config('dvsa.host').$this->endpoint;
    }

    public function hasRegistration(): bool
    {
        return isset($this->registration);
    }

    public function sendRequest(): Response
    {
        if (is_null(config('dvsa.key'))) {
            throw new Exception('DVSA API Key not set', 1);
        }

        if ($this->hasRegistration()) {
            return Http::withQueryParameters([
                'registration' => $this->registration,
            ])->withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => config('dvsa.key'),
            ])->acceptJson()
                ->get($this->fullUrl())
                ->throw();
        } else {
            throw new Exception('Registration Required', 1);
        }
    }
}
