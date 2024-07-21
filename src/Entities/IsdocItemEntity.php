<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final class IsdocItemEntity
{
  public function __construct(
    public int $itemId,
    public int $quantity,
    public string $unit,
    public string $description,
    public float $unitPrice,
    public float $totalPrice,
  )
  {}

  public function getItemId(): string
  {
    return (string)$this->itemId;
  }

  public function getQuantity(): string
  {
    return (string)$this->quantity;
  }

  public function getUnit(): string
  {
    return $this->unit;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getUnitPrice(): string
  {
    return (string)$this->unitPrice;
  }

  public function getTotalPrice(): string
  {
    return (string)$this->totalPrice;
  }

}
