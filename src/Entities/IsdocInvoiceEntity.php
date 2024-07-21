<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

use Carbon\Carbon;
use Obchodniuspech\IsdocInvoiceGenerator\Enums\InvoiceType;

final class IsdocInvoiceEntity
{
  /**
   * @param string $invoiceId
   * @param InvoiceType $invoiceType
   * @param Carbon $issuedDate
   * @param Carbon $dueDate
   * @param Carbon $taxDueDate
   * @param bool $vatApplicable
   * @param IsdocPartyEntity $seller
   * @param IsdocPartyEntity $buyer
   * @param non-empty-array<IsdocItemEntity> $items
   * @param IsdocTotalsEntity $totals
   */
  public function __construct(
    public string $invoiceId,
    public InvoiceType $invoiceType,
    public Carbon $issuedDate,
    public Carbon $dueDate,
    public Carbon $taxDueDate,
    public bool $vatApplicable,
    public IsdocPartyEntity $seller,
    public IsdocPartyEntity $buyer,
    public array $items,
    public IsdocTotalsEntity $totals,
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
