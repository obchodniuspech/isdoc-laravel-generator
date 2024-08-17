<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

use Carbon\Carbon;
use Obchodniuspech\IsdocInvoiceGenerator\Enums\InvoiceType;

final class IsdocContactTaxSchemeEntity
{
    /**
     * @param string $companyId
     * @param string $taxScheme,
     */
    public function __construct(
        public string $companyId,
        public string $taxScheme
    ) {}

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function getInvoiceType(): string
    {
        return $this->invoiceType->value;
    }

    public function getIssuedDate(): string
    {
        return $this->issuedDate->toDateString();
    }

    public function getDueDate(): string
    {
        return $this->dueDate->toDateString();
    }

    public function getTaxDueDate(): string
    {
        return $this->taxDueDate->toDateString();
    }

    public function isVatApplicable(): string
    {
        return $this->vatApplicable ? 'true' : 'false';
    }

    public function getSeller(): IsdocPartyEntity
    {
        return $this->seller;
    }

    public function getBuyer(): IsdocPartyEntity
    {
        return $this->buyer;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotals(): IsdocTotalsEntity
    {
        return $this->totals;
    }
}
