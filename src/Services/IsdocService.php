<?php

declare(strict_types=1);

namespace Obchodniuspech\IsdocInvoiceGenerator\Services;

use Obchodniuspech\IsdocInvoiceGenerator\Entities\IsdocInvoiceEntity;
use Obchodniuspech\IsdocInvoiceGenerator\Entities\IsdocPartyEntity;
use SimpleXMLElement;

class IsdocService
{
    public function generateInvoice(IsdocInvoiceEntity $data): string
    {
        $invoice = new SimpleXMLElement('<Invoice xmlns="http://isdoc.cz/namespace/2013" version="6.0.2"></Invoice>');

        // Invoice header
        $invoice->addChild('DocumentType', '1');
        $invoice->addChild('ID', $data->invoiceId);
        $invoice->addChild('UUID', 'A126D4C4-118C-FE70-43BD-B05E4ACCE94E');
        $invoice->addChild('IssuingSystem', 'Ekonomický a informační systém POHODA');
        $invoice->addChild('IssueDate', $data->issuedDate->toDateString());
        $invoice->addChild('TaxPointDate', $data->taxDueDate->toDateString());
        $invoice->addChild('VATApplicable', $data->vatApplicable ? 'true' : 'false');
        $invoice->addChild('ElectronicPossibilityAgreementReference', '');
        $invoice->addChild('Note', $data->note); // Přidání poznámky
        $invoice->addChild('LocalCurrencyCode', 'CZK');
        $invoice->addChild('CurrRate', '1');
        $invoice->addChild('RefCurrRate', '1');

        // Accounting Supplier Party
        $this->addParty($invoice, 'AccountingSupplierParty', $data->seller);

        // Accounting Customer Party
        $this->addParty($invoice, 'AccountingCustomerParty', $data->buyer);

        // Invoice Lines
        $lines = $invoice->addChild('InvoiceLines');
        foreach ($data->items as $item) {
            $line = $lines->addChild('InvoiceLine');
            $line->addChild('ID', $item->getItemId());
            $line->addChild('InvoicedQuantity', $item->getQuantity())->addAttribute('unitCode', $item->unit);
            $line->addChild('LineExtensionAmount', $item->getTotalPrice());
            $line->addChild('LineExtensionAmountTaxInclusive', $item->getTotalPriceTaxInclusive()); // S daňovým zvýšením
            $line->addChild('LineExtensionTaxAmount', $item->getTaxAmount());
            $line->addChild('UnitPrice', $item->getUnitPrice());
            $line->addChild('UnitPriceTaxInclusive', $item->getUnitPriceTaxInclusive()); // S daňovým zvýšením
            $taxCategory = $line->addChild('ClassifiedTaxCategory');
            $taxCategory->addChild('Percent', $item->getTaxPercentage());
            $taxCategory->addChild('VATCalculationMethod', '0');
            $taxCategory->addChild('VATApplicable', 'true');
            $line->addChild('Item')->addChild('Description', $item->description);
        }

        foreach ($data->vatSummary as $vatSummaryIndex => $vatSummaryItem) {
            // Tax Total
            $taxTotal = $invoice->addChild('TaxTotal');
            $taxSubTotal = $taxTotal->addChild('TaxSubTotal');
            $taxSubTotal->addChild('TaxableAmount', (string) $vatSummaryItem['total_base']);
            $taxSubTotal->addChild('TaxAmount', (string) $vatSummaryItem['total_vat']);
            $taxSubTotal->addChild('TaxInclusiveAmount', (string) $vatSummaryItem['total_with_vat']);
            $taxSubTotal->addChild('AlreadyClaimedTaxableAmount', '0');
            $taxSubTotal->addChild('AlreadyClaimedTaxAmount', '0');
            $taxSubTotal->addChild('AlreadyClaimedTaxInclusiveAmount', '0');
            $taxSubTotal->addChild('DifferenceTaxableAmount', (string) $vatSummaryItem['total_base']);
            $taxSubTotal->addChild('DifferenceTaxAmount', (string) $vatSummaryItem['total_vat']);
            $taxSubTotal->addChild('DifferenceTaxInclusiveAmount', (string) $vatSummaryItem['total_with_vat']);

            // also needed
            $taxCategory = $taxSubTotal->addChild('TaxCategory');
            $taxCategory->addChild('Percent', (string) $vatSummaryItem['vat_group']);
            $taxCategory->addChild('VATApplicable', 'true');
            $taxCategory->addChild('LocalReverseChargeFlag', 'false');
            $taxTotal->addChild('TaxAmount', (string) $vatSummaryItem['total_vat']);
        }

        // Legal Monetary Total
        $totals = $invoice->addChild('LegalMonetaryTotal');
        $totals->addChild('TaxExclusiveAmount', $data->totals->getTaxExclusive());
        $totals->addChild('TaxInclusiveAmount', $data->totals->getTaxInclusive());
        $totals->addChild('AlreadyClaimedTaxExclusiveAmount', '0');
        $totals->addChild('AlreadyClaimedTaxInclusiveAmount', '0');
        $totals->addChild('DifferenceTaxExclusiveAmount', $data->totals->getTaxExclusive());
        $totals->addChild('DifferenceTaxInclusiveAmount', $data->totals->getTaxInclusive());
        $totals->addChild('PayableRoundingAmount', '0');
        $totals->addChild('PaidDepositsAmount', '0');
        $totals->addChild('PayableAmount', $data->totals->getPayable());

        // Payment Means
        $paymentMeans = $invoice->addChild('PaymentMeans');
        $payment = $paymentMeans->addChild('Payment');
        $payment->addChild('PaidAmount', $data->totals->getPayable());
        $payment->addChild('PaymentMeansCode', '42'); // Kód platby
        $details = $payment->addChild('Details');
        $details->addChild('PaymentDueDate', $data->dueDate->toDateString());

        if ($data->getIBAN()) {
            $details->addChild('ID', ''); // Číslo účtu
            $details->addChild('BankCode', ''); // Kód banky
            $details->addChild('Name', ''); // Název banky
            $details->addChild('IBAN', $data->getIBAN()); // IBAN pokud existuje
            $details->addChild('BIC', ''); // BIC
        }
        else {
            $details->addChild('ID', $data->getAccount()); // Číslo účtu
            $details->addChild('BankCode', $data->getBankCode()); // Kód banky
            $details->addChild('Name', $data->getBankName()); // Název banky
            $details->addChild('IBAN', '');
            $details->addChild('BIC', '');
        }

        $details->addChild('VariableSymbol', $data->getVs());
        $details->addChild('ConstantSymbol', $data->getKs()); // Konstantní symbol

        $invoice = $invoice->asXML();
        //$this->validatInvoice($invoice);

        return $invoice;
    }

    private function addParty(SimpleXMLElement $parent, string $type, IsdocPartyEntity $data)
    {
        $party = $parent->addChild($type)->addChild('Party');
        $party->addChild('PartyIdentification')->addChild('ID', (string)$data->getCompanyId());
        $party->addChild('PartyName')->addChild('Name', $data->getName());
        $address = $party->addChild('PostalAddress');
        $address->addChild('StreetName', $data->address->street);
        $address->addChild('BuildingNumber', $data->address->number);
        $address->addChild('CityName', $data->address->city);
        $address->addChild('PostalZone', $data->address->getPostal());
        $country = $address->addChild('Country');
        $country->addChild('IdentificationCode', '');
        $country->addChild('Name', '');

        $partyTaxScheme = $party->addChild('PartyTaxScheme');
            $partyTaxScheme->addChild('CompanyID', $data->getTaxId());
            $partyTaxScheme->addChild('TaxScheme', 'VAT');

        $party->addChild('RegisterIdentification')->addChild('Preformatted', '');
        $party->addChild('Contact');
    }

    private function validatInvoice(string $invoice) {
        libxml_use_internal_errors(true);

        $xml = new \DOMDocument();
        $xml->loadXML($invoice);

        $path = base_path() . '/vendor/obchodniuspech/isdoc-invoice-generator/src/Schema/isdoc-invoice-6.0.2.xsd';

        if (!$xml->schemaValidate($path)) {
            print '<b>DOMDocument::schemaValidate() Generated Errors!</b><br>';
            foreach (libxml_get_errors() as $error) {
                echo "Libxml error: {$error->message}\n";
            }
        }

        libxml_use_internal_errors(false);
    }
}
