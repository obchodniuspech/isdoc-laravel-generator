<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocContactEntity
{
    public function __construct(
        public string $phone,
        public string $email,
    )
    {}
}
