<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocContactEntity
{
    public function __construct(
        string $phone,
        string $email,
    )
    {}
}
