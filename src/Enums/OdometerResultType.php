<?php

namespace FourthDimension\Dvsa\Enums;

enum OdometerResultType: string
{
    case READ = 'READ';
    case UNREADABLE = 'UNREADABLE';
    case NO_ODOMETER = 'NO_ODOMETER';
}
