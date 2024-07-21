<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

use Carbon\Carbon;

final class InvoicePaidMethodEntity
{
    public function __construct(
        public readonly string $publicId,
        public readonly Carbon $from,
        public readonly ?int $agencyId,
    ) {
    }
}
