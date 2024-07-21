<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocPartyEntity
{
    public function __construct(
        int $companyId,
        string $companyVatId,
        string $name,
        IsdocAddressEntity $address,
        IsdocContactEntity $contact,
    )
    {}
}
