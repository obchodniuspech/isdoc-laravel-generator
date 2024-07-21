<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocItemEntity
{
    public function __construct(
        int $itemId,
        int $quantity,
        string $unit,
        string $description,
        float $unitPrice,
        float $totalPrice,
    )
    {}
}
