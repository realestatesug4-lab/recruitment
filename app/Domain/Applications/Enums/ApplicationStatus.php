<?php
namespace App\Domain\Applications\Enums;

enum ApplicationStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case SHORTLISTED = 'shortlisted';
    case INTERVIEW = 'interview';
    case HIRED = 'hired';
    case REJECTED = 'rejected';
}
