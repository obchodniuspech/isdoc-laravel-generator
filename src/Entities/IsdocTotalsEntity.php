<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocTotalsEntity
{
    public function __construct(
        float $taxExclusive,
        float $taxInclusive,
        float $payable,
    )
    {}
}
