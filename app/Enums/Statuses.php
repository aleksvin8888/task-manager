<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\ToArrayConverterTrait;

enum Statuses: string
{
    use ToArrayConverterTrait;
    case New = 'new';
    case Pending = 'pending';
    case  InProgress = ' in-progress';
    case Completed = 'completed';
}
