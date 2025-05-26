<?php

namespace App\Services;

use App\Jobs\ProductStockImport;
use App\Services\Interface\DataImportInterface;

class ProductStockImporterService implements DataImportInterface
{
    public function make(object $import): void
    {
        ProductStockImport::dispatch($import);
    }
}
