<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Enums;

enum InvoiceType: string
{
    case Issued = 'issued';
    case Received = 'received';
    case IssuedPersonal = 'issued_personal';
    case RecievedPersonal = 'recieved_personal';
    case OutcomeBusiness = 'outcomeBusiness';
    case OutcomePersonal = 'outcomePersonal';
    case IncomeBusiness = 'incomeBusiness';
    case Deleted = 'deleted';
}
