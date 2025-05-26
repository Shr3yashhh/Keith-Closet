<?php

use App\Services\ProductStockImporterService;

return [
    "stock" => [
        "label" => "Product Stock",
        "identifier" => [
            "sku",
        ],
        "action" => [
            "create",
            "delete",
            "view",
        ],
        "class" => ProductStockImporterService::class,
        "fields" =>[
            [
                "identifier" => "sku",
                "label" => "sku",
            ],
            [
                "identifier" => "size",
                "label" => "Size",
            ],
            [
                "identifier" => "category",
                "label" => "Category",
            ],
            [
                "identifier" => "bin_location",
                "label" => "Bin Location",
            ],
            [
                "identifier" => "quantity",
                "label" => "Quantity",
            ],
            [
                "identifier" => "warehouse",
                "label" => "Warehouse",
            ],
            [
                "identifier" => "sex",
                "label" => "SEX",
            ]
        ],
    ],
];
