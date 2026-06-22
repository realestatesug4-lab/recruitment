<?php
namespace App\Domain\Jobs\Enums;

enum JobType: string
{
    case FULL_TIME = 'full-time';
    case CONTRACT = 'contract';
    case REMOTE = 'remote';
}
