<?php
declare(strict_types = 1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Entities;

final readonly class IsdocPartyEntity
{
  public function __construct(
    public int                $companyId,
    public string             $companyVatId,
    public string             $name,
    public IsdocAddressEntity $address,
    public IsdocContactEntity $contact,
  )
  {
  }

  public function getCompanyId(): string
  {
    return (string)$this->companyId;
  }

  public function getCompanyVatId(): string
  {
    return $this->companyVatId;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getAddress(): IsdocAddressEntity
  {
    return $this->address;
  }

  public function getContact(): IsdocContactEntity
  {
    return $this->contact;
  }

}
