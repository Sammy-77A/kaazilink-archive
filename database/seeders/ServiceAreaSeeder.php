<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['name' => 'CBD', 'lat' => -1.283, 'lng' => 36.816],
            ['name' => 'Westlands', 'lat' => -1.266, 'lng' => 36.804],
            ['name' => 'Kilimani', 'lat' => -1.288, 'lng' => 36.784],
            ['name' => 'Kileleshwa', 'lat' => -1.275, 'lng' => 36.782],
            ['name' => 'Lavington', 'lat' => -1.282, 'lng' => 36.766],
            ['name' => 'Karen', 'lat' => -1.332, 'lng' => 36.711],
            ['name' => 'Lang\'ata', 'lat' => -1.339, 'lng' => 36.764],
            ['name' => 'South B', 'lat' => -1.311, 'lng' => 36.833],
            ['name' => 'South C', 'lat' => -1.321, 'lng' => 36.820],
            ['name' => 'Eastleigh', 'lat' => -1.274, 'lng' => 36.852],
            ['name' => 'Buruburu', 'lat' => -1.281, 'lng' => 36.871],
            ['name' => 'Donholm', 'lat' => -1.291, 'lng' => 36.883],
            ['name' => 'Umoja', 'lat' => -1.282, 'lng' => 36.888],
            ['name' => 'Pipeline', 'lat' => -1.316, 'lng' => 36.892],
            ['name' => 'Kasarani', 'lat' => -1.222, 'lng' => 36.898],
            ['name' => 'Roysambu', 'lat' => -1.218, 'lng' => 36.883],
            ['name' => 'Muthaiga', 'lat' => -1.250, 'lng' => 36.834],
            ['name' => 'Ruaka', 'lat' => -1.200, 'lng' => 36.782],
            ['name' => 'Rongai', 'lat' => -1.396, 'lng' => 36.761],
            ['name' => 'Syokimau', 'lat' => -1.364, 'lng' => 36.936],
        ];

        foreach ($areas as $area) {
            \App\Models\ServiceArea::firstOrCreate(
                ['name' => $area['name']],
                ['latitude' => $area['lat'], 'longitude' => $area['lng']]
            );
        }
    }
}
