<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Services;

use Obchodniuspech\IsdocInvoiceGenerator\Entities\IsdocInvoiceEntity;
use Obchodniuspech\IsdocInvoiceGenerator\Entities\IsdocPartyEntity;
use SimpleXMLElement;

class IsdocService
{
  /**
   * @param IsdocInvoiceEntity $data
   * @return string
   */
  public function generateInvoice(IsdocInvoiceEntity $data): string
  {
    $invoice = new SimpleXMLElement('<Invoice xmlns="urn:cz:isdoc:v6" version="6.0"></Invoice>');

    // invoice header
    $header = $invoice->addChild('InvoiceHeader');
    $header->addChild('ID', $data->invoiceId);
    $header->addChild('InvoiceType', $data->invoiceType->value);
    $header->addChild('IssueDate', $data->issuedDate->toDateString());
    $header->addChild('DueDate', $data->dueDate->toDateString());
    $header->addChild('TaxPointDate', $data->taxDueDate->toDateString());
    $header->addChild('VATApplicable', $data->vatApplicable ? 'true' : 'false');

    // invoice parties
    $parties = $invoice->addChild('InvoiceParties');
    $this->addParty($parties, 'SellerParty', $data['seller']);
    $this->addParty($parties, 'BuyerParty', $data['buyer']);

    // invoice items
    $lines = $invoice->addChild('InvoiceLines');
    foreach ($data['items'] as $item) {
      $line = $lines->addChild('InvoiceLine');
      $line->addChild('ID', $item['id']);
      $line->addChild('InvoicedQuantity', $item['quantity'])->addAttribute('unitCode', $item['unit']);
      $line->addChild('Item')->addChild('Description', $item['description']);
      $line->addChild('UnitPrice', $item['unit_price']);
      $line->addChild('LineExtensionAmount', $item['total']);
    }

    // invoice merged info
    $totals = $invoice->addChild('LegalMonetaryTotal');
    $totals->addChild('TaxExclusiveAmount', $data['totals']['tax_exclusive']);
    $totals->addChild('TaxInclusiveAmount', $data['totals']['tax_inclusive']);
    $totals->addChild('PayableAmount', $data['totals']['payable']);

    return $invoice->asXML();
  }

  private function addParty(SimpleXMLElement $parent, string $type, IsdocPartyEntity $data)
  {
    $party = $parent->addChild($type)->addChild('Party');
    $party->addChild('PartyIdentification')->addChild('ID', $data->companyId);
    $party->addChild('PartyName')->addChild('Name', $data['name']);
    $address = $party->addChild('PostalAddress');
    $address->addChild('StreetName', $data['address']['street']);
    $address->addChild('BuildingNumber', $data['address']['number']);
    $address->addChild('CityName', $data['address']['city']);
    $address->addChild('PostalZone', $data['address']['postal']);
    $address->addChild('Country')->addChild('IdentificationCode', $data['address']['country']);
    $contact = $party->addChild('Contact');
    $contact->addChild('Telephone', $data['contact']['phone']);
    $contact->addChild('ElectronicMail', $data['contact']['email']);
  }
}
