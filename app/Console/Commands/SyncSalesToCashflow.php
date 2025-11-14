<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\CashFlow;

class SyncSalesToCashflow extends Command
{
    protected $signature = 'sales:sync-cashflow';
    protected $description = 'Sync existing sales data to cashflow';

    public function handle()
    {
        $this->info('Syncing sales to cashflow...');

        $sales = Sale::all();
        $synced = 0;
        $skipped = 0;

        foreach ($sales as $sale) {
            // Check if cashflow already exists for this sale
            $exists = CashFlow::where('date', $sale->date)
                ->where('type', 'income')
                ->where('category', 'penjualan')
                ->whereRaw("description LIKE ?", ['%(Sale ID: ' . $sale->id . ')%'])
                ->exists();

            if (!$exists) {
                CashFlow::create([
                    'date' => $sale->date,
                    'type' => 'income',
                    'category' => 'penjualan',
                    'amount' => $sale->total,
                    'description' => 'Penjualan ' . ($sale->product_type === 'small' ? 'Botol Kecil' : 'Botol Besar') .
                                     ' x' . $sale->quantity .
                                     ($sale->location ? ' - ' . $sale->location->name : '') .
                                     ' (Sale ID: ' . $sale->id . ')',
                ]);
                $synced++;
            } else {
                $skipped++;
            }
        }

        $this->info("Sync completed! Synced: {$synced}, Skipped: {$skipped}");
        return 0;
    }
}
