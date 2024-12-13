<?php

namespace FourthDimension\Dvsa;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MotTest
{
    public Carbon $completed_date;

    public ?Carbon $expiry_date;

    public string $test_result;

    public bool $passed;

    public int $odometer_value;

    public string $odometer_unit;

    public string $odometer_result_type;

    public string $mot_test_number;

    public Collection $comments;

    public function __construct(array $rawData)
    {
        $rawCollection = collect($rawData);

        $this->test_result = $rawCollection->get('testResult');
        $this->odometer_value = $rawCollection->get('odometerValue') ? intval($rawCollection->get('odometerValue')) : null;
        $this->odometer_unit = $rawCollection->get('odometerUnit');
        $this->odometer_result_type = $rawCollection->get('odometerResultType');
        $this->mot_test_number = $rawCollection->get('motTestNumber');
        $this->completed_date = $rawCollection->get('completedDate') ? Carbon::createFromFormat('Y.m.d H:i:s', $rawCollection->get('completedDate')) : null;
        $this->expiry_date = $rawCollection->get('expiryDate') ? Carbon::createFromFormat('Y.m.d', $rawCollection->get('expiryDate'))->endOfDay() : null;
        $this->passed = $this->test_result == 'PASSED';

        $this->comments = collect($rawCollection->get('rfrAndComments'))->map(function (array $comment) {
            return new MotTestComment($comment);
        });
    }
}
