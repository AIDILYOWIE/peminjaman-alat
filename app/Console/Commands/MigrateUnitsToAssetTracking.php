<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alat;
use App\Models\AlatUnit;

class MigrateUnitsToAssetTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:migrate-units';
    protected $description = 'Migrate existing stock to individual alat units';

    public function handle()
    {
        $alats = Alat::all();
        $totalCreated = 0;

        foreach ($alats as $alat) {
            $currentUnits = $alat->units()->count();
            $needed = $alat->stock - $currentUnits;

            if ($needed > 0) {
                $this->info("Migrating {$needed} units for: {$alat->nama}");

                for ($i = 0; $i < $needed; $i++) {
                    $sequence = $currentUnits + $i + 1;
                    $unitCode = $alat->code . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

                    AlatUnit::create([
                        'alat_id'   => $alat->id,
                        'unit_code' => $unitCode,
                        'status'    => 'ready',
                        'condition' => 'good'
                    ]);
                    $totalCreated++;
                }
            }
        }

        $this->info("Migration completed. Total units created: {$totalCreated}");
    }
}
