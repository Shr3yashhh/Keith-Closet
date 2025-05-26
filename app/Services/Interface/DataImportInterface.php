<?php

namespace App\Services\Interface;

interface DataImportInterface
{
    public function make(object $import): void;
}
