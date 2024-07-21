<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

use Carbon\Carbon;
use Obchodniuspech\IsdocInvoiceGenerator\Enums\InvoiceType;

final readonly class IsdocInvoiceEntity
{
    /**
     * @param int $invoiceId
     * @param InvoiceType $invoiceType
     * @param Carbon $issuedDate
     * @param Carbon $dueDate
     * @param Carbon $taxDueDate
     * @param bool $vatApplicable
     * @param IsdocPartyEntity $seller
     * @param IsdocPartyEntity $buyer
     * @param non-empty-array<IsdocItemEntity> $items
     */
    public function __construct(
        int $invoiceId,
        InvoiceType $invoiceType,
        Carbon $issuedDate,
        Carbon $dueDate,
        Carbon $taxDueDate,
        bool $vatApplicable,
        IsdocPartyEntity $seller,
        IsdocPartyEntity $buyer,
        array $items,
        IsdocTotalsEntity $totals,
    )
    {}
}
