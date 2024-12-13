<?php

namespace FourthDimension\Dvsa\Enums;

enum CommentType: string
{
    case FAIL = 'FAIL';
    case DANGEROUS = 'DANGEROUS';
    case MAJOR = 'MAJOR';
    case ADVISORY = 'ADVISORY';
}
