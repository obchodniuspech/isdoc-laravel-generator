<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocAddressEntity
{
    public function __construct(
        string $street,
        string $number,
        string $city,
        int $postal,
        string $country,
    )
    {}
}
