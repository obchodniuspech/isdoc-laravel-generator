<?php

declare(strict_types=1);

namespace YourNamespace\IsdocInvoiceGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Obchodniuspech\IsdocInvoiceGenerator\Services\IsdocService;

class IsdocServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->singleton(IsdocService::class, function ($app) {
      return new IsdocService();
    });
  }

  public function boot()
  {
    // configs & routing
  }
}
