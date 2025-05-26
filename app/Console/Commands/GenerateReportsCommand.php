<?php

namespace App\Console\Commands;

use App\Mail\NightlyReportMail;
use App\Models\Donation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductWarehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class GenerateReportsCommand extends Command
{
    protected $signature = 'reports:generate {--type=daily}';
    protected $description = 'Generate and email inventory report (daily, weekly, monthly) as PDF';

    public function handle()
    {
        $type = $this->option('type');
        $now = Carbon::now();
        $date = $now->format('Y-m-d');

        // Define date range
        switch ($type) {
            case 'weekly':
                // Last week's Monday to Sunday
                $startDate = $now->copy()->subWeek()->startOfWeek();
                $endDate = $now->copy()->subWeek()->endOfWeek();
                break;

            case 'monthly':
                // Last month's 1st to last day
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;

            default:
                // Default to today (i.e., daily report)
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $type = 'daily'; // normalize
                break;
        }
        $reportPath = storage_path("app/reports/inventory_report_{$type}_{$date}.pdf");

        $sections = [];

        // Stock by Warehouse
        $warehouseStock = ProductWarehouse::with(['product', 'warehouse'])->get()
            ->map(fn($item) => [
                'Product' => $item->product->name ?? 'Unknown',
                'Warehouse ID' => $item->warehouse->name ?? $item->warehouse->code,
                'Quantity' => $item->quantity,
            ])
            ->toArray();
        $sections['stock_by_warehouse'] = $warehouseStock;

        // Donations (within date range)
        $donations = Order::with(['orderItems.product'])
            ->where('type', 'donation')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->flatMap(function ($order) {
                return $order->orderItems->map(function ($item) use ($order) {
                    return [
                        'Product'       => $item->product->name ?? 'Unknown',
                        'Quantity'      => $item->quantity,
                        'Donated To'    => $order->username ?? 'N/A',
                        'Donation Date' => $order->created_at->format('Y-m-d H:i'),
                    ];
                });
            })
            ->values()
            ->toArray();
        $sections['donation'] = $donations;

        // Fast/Slow Moving Stock
        $movements = OrderItem::with('product', 'order')
            ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->get()
            ->groupBy('product_id')
            ->map(fn($items) => [
                'Product' => $items->first()->product->name ?? 'Unknown',
                'Total Ordered' => $items->sum('quantity')
            ]);

        $sections['fast_moving_stock'] = $movements->sortByDesc('Total Ordered')->take(5)->values()->toArray();
        $sections['slow_moving_stock'] = $movements->sortBy('Total Ordered')->take(5)->values()->toArray();

        // Inventory snapshot (same as stock by warehouse)
        $sections['inventory_snapshot'] = $warehouseStock;

        // Generate PDF
        $pdf = Pdf::loadView('admin.pages.report.nightly_report', [
            'sections' => $sections,
            'type'     => $type,
            'date'     => $date,
            'from'     => $startDate->format('Y-m-d'),
            'to'       => $endDate->format('Y-m-d'),
        ]);

        if (!file_exists(dirname($reportPath))) {
            mkdir(dirname($reportPath), 0777, true);
        }

        $pdf->save($reportPath);

        // Email it
        Mail::to('inventory@example.com')->send(new NightlyReportMail($reportPath, ucfirst($type), $startDate, $endDate));

        $this->info(strtoupper($type) . ' PDF report generated and emailed successfully.');
    }
}




// class GenerateReportsCommand extends Command
// {
//     protected $signature = 'reports:generate';
//     protected $description = 'Generate and email nightly inventory report as PDF';

//     public function handle()
//     {
//         $date = Carbon::now()->format('Y-m-d');
//         $reportPath = storage_path("app/reports/inventory_report_{$date}.pdf");

//         $sections = [];

//         // 1. Stock on Hand HQ
//         // $hqStock = ProductWarehouse::with('product')
//         //     ->where('warehouse_id', 1)
//         //     ->get()
//         //     ->map(fn($item) => [
//         //         'Product' => $item->product->name,
//         //         'Warehouse ID' => $item->warehouse_id,
//         //         'Quantity' => $item->quantity,
//         //     ])
//         //     ->toArray();
//         // $sections['Stock on Hand HQ'] = $hqStock;

//         // 2. Stock by Warehouse
//         $warehouseStock = ProductWarehouse::with('product')->get()
//             ->map(fn($item) => [
//                 'Product' => $item->product->name,
//                 'Warehouse ID' => $item->warehouse_id,
//                 'Quantity' => $item->quantity,
//             ])
//             ->toArray();
//         $sections['stock_by_warehouse'] = $warehouseStock;

//         // 3. Back Order
//         // $backOrders = BackOrder::with('product')->get()
//         //     ->map(fn($item) => [
//         //         'Product' => $item->product->name,
//         //         'Requested Quantity' => $item->requested_quantity,
//         //         'Created At' => $item->created_at->format('Y-m-d H:i'),
//         //     ])
//         //     ->toArray();
//         // $sections['Back Orders'] = $backOrders;

//         // 4. Donations
//         $donations = Order::with(['orderItems.product'])
//             ->where('type', 'donation')
//             ->get()
//             ->flatMap(function ($order) {
//                 return $order->orderItems->map(function ($item) use ($order) {
//                     return [
//                         'Product'      => $item->product->name ?? 'Unknown',
//                         'Quantity'     => $item->quantity,
//                         'Donated To'   => $order->username ?? 'N/A', // if such field exists
//                         'Donation Date'=> $order->created_at->format('Y-m-d H:i'),
//                     ];
//                 });
//             })
//             ->values()
//             ->toArray();

//         $sections['donation'] = $donations;
//         // dd($sections["Donations"]);

//         // 5. Fast/Slow Moving Stock
//         $last30 = Carbon::now()->subDays(30);
//         $movements = OrderItem::with('product', 'order')
//             ->whereHas('order', fn($q) => $q->where('created_at', '>=', $last30))
//             ->get()
//             ->groupBy('product_id')
//             ->map(fn($items) => [
//                 'Product' => $items->first()->product->name,
//                 'Total Ordered' => $items->sum('quantity')
//             ]);

//         $sections['fast_moving_stock'] = $movements->sortByDesc('Total Ordered')->take(5)->values()->toArray();
//         $sections['show_moving_stock'] = $movements->sortBy('Total Ordered')->take(5)->values()->toArray();

//         // dd($sections['fast_moving_stock'], $sections['show_moving_stock']);
//         // 6. Overnight Snapshot (Same as Stock by Warehouse)
//         $sections['inventory_snapshot'] = $warehouseStock;

//         // Generate PDF
//         $pdf = Pdf::loadView('admin.pages.report.nightly_report', [
//             'sections' => $sections,
//             'date' => $date,
//         ]);
//         // dd($reportPath);
//         if (!file_exists(dirname($reportPath))) {
//             mkdir(dirname($reportPath), 0777, true);
//         }
//         $pdf->save($reportPath);

//         // Email it
//         // TODO
//         Mail::to('inventory@example.com')->send(new NightlyReportMail($reportPath));

//         $this->info('PDF report generated and emailed successfully.');
//     }
// }

