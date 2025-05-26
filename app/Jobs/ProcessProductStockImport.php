<?php

namespace App\Jobs;

use Exception;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Bus\Batch;
use App\Models\ImportData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Models\ProductWarehouse;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessProductStockImport implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected bool $notify = false;

    public function __construct(
        protected array $data,
        protected string $importId,
    ) {
        $this->notify = true;
    }

    public function handle(): void
    {
        try {
            $importData = ImportData::create([
                "import_id" => $this->importId,
                "data" => $this->data,
                "status" => "processing"
            ]);

            $product = Product::where("sku", $this->data['sku']);
            $warehouse = Warehouse::where("code", $this->data["warehouse"]);

            $updateData = [
                "product_id" => $product->id,
                "warehouse_id" => $warehouse->id,
                "quantity" => $this->data["quantity"],
                "bin_location" => $this->data["bin_location"]
            ];

            $productWarehouse = ProductWarehouse::where("product_id", $product->id)
                ->where("warehouse_id", $warehouse->id)->first();
                dd($productWarehouse);

            if ($productWarehouse) {
                $updateData = [
                    "quantity" => $this->data["quantity"],
                    "bin_location" => $this->data["bin_location"]
                ];
                $productWarehouse->update($updateData);
            } else {
                ProductWarehouse::store($updateData);
            }

            $importData->update([
                "status" => "success"
            ]);
        } catch (Exception $exception) {
            $this->markImportDataFailed($importData->id, $exception->getMessage());
            $this->job->fail();
        }
    }

    private function markImportDataFailed(string $importDataId, string $errorMessage): void
    {
        $importData = ImportData::where("id", $importDataId)->firstOrFail();
        $importData->update([
            "status" => "failed",
            "description" => $errorMessage
        ]);
    }

    private function isEligible(
        ?object $store,
        ?object $product,
    ): void {
        // throw_if(
        //     condition: !$store,
        //     exception: "Request store does not exist."
        // );

        // throw_if(
        //     condition: empty($product),
        //     exception: "Request product does not exist."
        // );

        // throw_if(
        //     condition: !empty($this->data["valid_from"])
        //         && !Carbon::parse($this->data["valid_from"], "UTC")->isValid(),
        //     exception: "Valid from date is invalid."
        // );

        // throw_if(
        //     condition: !empty($this->data["valid_to"])
        //         && !Carbon::parse($this->data["valid_to"], "UTC")->isValid(),
        //     exception: "Valid to date is invalid."
        // );
    }

}
