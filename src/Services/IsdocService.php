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
    $header->addChild('DocumentType', '1');
    $header->addChild('UUID', 'A126D4C4-118C-FE70-43BD-B05E4ACCE94E');
    $header->addChild('IssuingSystem', config('APP_NAME'));
    $header->addChild('ID', $data->invoiceId);
    $header->addChild('Note', '');
    $header->addChild('InvoiceType', $data->invoiceType->value);
    $header->addChild('IssueDate', $data->issuedDate->toDateString());
    $header->addChild('DueDate', $data->dueDate->toDateString());
    $header->addChild('TaxPointDate', $data->taxDueDate->toDateString());
    $header->addChild('VATApplicable', $data->vatApplicable ? 'true' : 'false');
    $header->addChild('LocalCurrencyCode', 'CZK');
    $header->addChild('CurrRate', '1.0');
    $header->addChild('RefCurrRate', '1');

    // invoice parties
    //$parties = $invoice->addChild('InvoiceParties');

    // Accounting supplier party
    $this->addParty($invoice, 'AccountingSupplierParty', $data->seller);
    // Seller supplier party
    $this->addParty($invoice, 'SellerSupplierParty', $data->seller);

    // Accounting customer party
    $this->addParty($invoice, 'AccountingCustomerParty', $data->buyer);

    // Buyer customer party
    $this->addParty($invoice, 'BuyerCustomerParty', $data->buyer);

    // invoice items
    $lines = $invoice->addChild('InvoiceLines');
    foreach ($data->items as $item) {
      $line = $lines->addChild('InvoiceLine');
      $line->addChild('ID', $item->getItemId());
      $line->addChild('InvoicedQuantity', $item->getQuantity())->addAttribute('unitCode', $item->unit);
      $line->addChild('Item')->addChild('Description', $item->description);
      $line->addChild('UnitPrice', $item->getUnitPrice());
      $line->addChild('LineExtensionAmount', $item->getTotalPrice());
    }

    // invoice merged info
    $totals = $invoice->addChild('LegalMonetaryTotal');
    $totals->addChild('TaxExclusiveAmount', $data->totals->getTaxExclusive());
    $totals->addChild('TaxInclusiveAmount', $data->totals->getTaxInclusive());
    $totals->addChild('PayableAmount', $data->totals->getPayable());

    // invoice merged info
    $totals = $invoice->addChild('LegalMonetaryTotal');
    $totals->addChild('TaxExclusiveAmount', $data->totals->getTaxExclusive());
    $totals->addChild('TaxInclusiveAmount', $data->totals->getTaxInclusive());
    $totals->addChild('AlreadyClaimedTaxExclusiveAmount', '0'); // Add actual value
    $totals->addChild('AlreadyClaimedTaxInclusiveAmount', '0'); // Add actual value
    $totals->addChild('DifferenceTaxExclusiveAmount', $data->totals->getTaxExclusive()); // Add actual value
    $totals->addChild('DifferenceTaxInclusiveAmount', $data->totals->getTaxInclusive()); // Add actual value
    $totals->addChild('PayableRoundingAmount', '0'); // Add actual value
    $totals->addChild('PaidDepositsAmount', '0'); // Add actual value
    $totals->addChild('PayableAmount', $data->totals->getPayable());

    // Payment means
    /*$paymentMeans = $invoice->addChild('PaymentMeans');
    $payment = $paymentMeans->addChild('Payment');
    $payment->addChild('PaidAmount', '7260.0'); // Add actual value
    $payment->addChild('PaymentMeansCode', '42'); // Change to appropriate value
    $details = $payment->addChild('Details');
    $details->addChild('PaymentDueDate', '2024-07-23'); // Add actual value
    $details->addChild('ID', '2900499336'); // Add actual value
    $details->addChild('BankCode', '2010'); // Add actual value
    $details->addChild('Name', 'Fio banka'); // Add actual value
    $details->addChild('IBAN', 'CZ4820100000002900499336'); // Add actual value
    $details->addChild('BIC', 'FIOBCZPPXXX'); // Add actual value
    $details->addChild('VariableSymbol', '20240002'); // Add actual value*/

    return $invoice->asXML();
  }

  private function addParty(SimpleXMLElement $parent, string $type, IsdocPartyEntity $data)
  {
    $party = $parent->addChild($type)->addChild('Party');
    $party->addChild('PartyIdentification')->addChild('ID', $data->getCompanyId());
    $party->addChild('PartyName')->addChild('Name', $data->getName());
    $address = $party->addChild('PostalAddress');
    $address->addChild('StreetName', $data->address->street);
    $address->addChild('BuildingNumber', $data->address->number);
    $address->addChild('CityName', $data->address->city);
    $address->addChild('PostalZone', $data->address->getPostal());
    $address->addChild('Country')->addChild('IdentificationCode', $data->address->country);
    $contact = $party->addChild('Contact');
    $contact->addChild('Telephone', $data->contact->phone);
    $contact->addChild('ElectronicMail', $data->contact->email);
  }
}
