<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Enums;

enum InvoiceType: string
{
    case Issued = 'issued';
    case Recieved = 'recieved';
    case IssuedPersonal = 'issued_personal';
    case RecievedPersonal = 'recieved_personal';
}
