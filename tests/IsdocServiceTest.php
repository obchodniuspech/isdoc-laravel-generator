<?php

declare(strict_types=1);

namespace YourNamespace\IsdocInvoiceGenerator\Tests;

use PHPUnit\Framework\TestCase;
use YourNamespace\IsdocInvoiceGenerator\Services\IsdocService;

class IsdocServiceTest extends TestCase
{
  public function testGenerateInvoice()
  {
    $service = new IsdocService();

    $data = [
      'id' => 'F20230001',
      'invoice_type' => 'RegularInvoice',
      'issue_date' => '2023-01-15',
      'due_date' => '2023-01-30',
      'tax_point_date' => '2023-01-15',
      'vat_applicable' => true,
      'seller' => [
        'id' => '12345678',
        'name' => 'Dodavatel s.r.o.',
        'address' => [
          'street' => 'Dodavatelská',
          'number' => '123',
          'city' => 'Praha',
          'postal' => '10000',
          'country' => 'CZ',
        ],
        'contact' => [
          'phone' => '+420123456789',
          'email' => 'info@dodavatel.cz',
        ],
      ],
      'buyer' => [
        'id' => '87654321',
        'name' => 'Odběratel a.s.',
        'address' => [
          'street' => 'Odběratelská',
          'number' => '456',
          'city' => 'Brno',
          'postal' => '60200',
          'country' => 'CZ',
        ],
        'contact' => [
          'phone' => '+420987654321',
          'email' => 'info@odberatel.cz',
        ],
      ],
      'items' => [
        [
          'id' => '1',
          'quantity' => '100',
          'unit' => 'KGM',
          'description' => 'Zboží 1',
          'unit_price' => '100.00',
          'total' => '10000.00',
        ],
        [
          'id' => '2',
          'quantity' => '50',
          'unit' => 'KGM',
          'description' => 'Zboží 2',
          'unit_price' => '200.00',
          'total' => '10000.00',
        ],
      ],
      'totals' => [
        'tax_exclusive' => '20000.00',
        'tax_inclusive' => '24000.00',
        'payable' => '24000.00',
      ],
    ];

    $xml = $service->generateInvoice($data);
    $this->assertStringContainsString('<Invoice', $xml);
    $this->assertStringContainsString('<ID>F20230001</ID>', $xml);
  }
}
