<?php

namespace App\Support\ValueObjects;

final class SalaryRange
{
    public function __construct(
        public readonly int $min,
        public readonly ?int $max = null,
    ) {
        if ($max !== null && $max < $min) {
            throw new \InvalidArgumentException('Max salary cannot be less than min.');
        }
    }

    public function format(string $currency = 'UGX'): string {
        $minFmt = number_format($this->min / 1000) . 'K';
        return $this->max
            ? "{$currency} {$minFmt} – " . number_format($this->max / 1000) . 'K'
            : "{$currency} {$minFmt}+";
    }

    public function overlapsWith(SalaryRange $other): bool {
        return $this->min <= ($other->max ?? PHP_INT_MAX) && ($this->max ?? PHP_INT_MAX) >= $other->min;
    }
}
