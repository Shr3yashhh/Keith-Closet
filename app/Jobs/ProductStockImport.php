<?php

namespace App\Jobs;

use App\Models\Import;
use App\Models\ImportData;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ProductStockImport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected bool $notify = false;

    public function __construct(
        protected object $import
    ) {
    }

    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $importId = $this->import->id;
            $countRows = count($this->import->data);

            foreach ($this->import->data as $row) {
                // new ProcessProductStockImport($row, $importId);
                $importData = ImportData::create([
                    "import_id" => $importId,
                    "data" => $row,
                    "status" => "processing"
                ]);

                $product = Product::where("sku", $row['sku'])->first();

                if (!$product) {
                    $product = Product::create([
                        "sku" => $row['sku'],
                        "name" => $row['sku'] ?? 'Unknown Product',
                        "category" => $row['category'] ?? '',
                        "size" => $row['size'] ?? '',
                    ]);
                }
                $warehouse = Warehouse::where("code", $row["warehouse"])->first();
                if (!$warehouse) {
                    $warehouse = Warehouse::create([
                        "code" => $row["warehouse"],
                        "name" => $row["warehouse"] ?? 'Unknown Warehouse',
                    ]);
                }

                $createData = [
                    "product_id" => $product->id,
                    "warehouse_id" => $warehouse->id,
                    "quantity" => $row["quantity"],
                    "bin_location" => $row["bin_location"]
                ];

                $productWarehouse = ProductWarehouse::where("product_id", $product->id)
                    ->where("warehouse_id", $warehouse->id)->first();

                if ($productWarehouse) {
                    $updateData = [
                        "quantity" => $row["quantity"],
                        "bin_location" => $row["bin_location"]
                    ];
                    $productWarehouse->update($updateData);
                } else {
                    $productWarehouse = ProductWarehouse::create($createData);
                }

                $importData->update([
                    "status" => "success"
                ]);
            }

            $import = Import::findOrFail($importId);
            $import->update([
                "status" => "completed",
                "total_rows" => $countRows,
                "updated_rows" => $countRows,
            ]);

        } catch (Exception $exception) {
            $importData->update([
                "status" => "fail"
            ]);
            // DB::rollBack();
            // throw $exception;
        }

        DB::commit();
    }
}
