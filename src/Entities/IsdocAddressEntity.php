<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocAddressEntity
{
  public function __construct(
    public string $street,
    public string $number,
    public string $city,
    public int $postal,
    public string $country,
  )
  {}

  public function getPostal(): string
  {
    return (string)$this->postal;
  }
}
