<?php
namespace App\Domain\Jobs\Enums;

enum JobStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case CLOSED = 'closed';
}
