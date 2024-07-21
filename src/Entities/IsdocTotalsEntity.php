<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final class IsdocTotalsEntity
{
  public function __construct(
    public float $taxExclusive,
    public float $taxInclusive,
    public float $payable,
  ) {}

  public function getTaxExclusive(): string
  {
    return (string)$this->taxExclusive;
  }

  public function getTaxInclusive(): string
  {
    return (string)$this->taxInclusive;
  }

  public function getPayable(): string
  {
    return (string)$this->payable;
  }
}
