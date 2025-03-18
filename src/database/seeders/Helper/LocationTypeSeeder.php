<?php

namespace Database\Seeders\Helper;

use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locationTypes = [
            [
                'name' => 'In-person meeting',
                'description' => 'Set a physical address',
            ],
            [
                'name' => 'Phone call',
                'description' => 'Inbound or outbound calls',
            ],
            [
                'name' => 'Telehealth video call',
                'description' => 'Set a virtual location',
            ],
            [
                'name' => 'Google Meet',
                'description' => 'Web conference',
            ],
            [
                'name' => 'Zoom',
                'description' => 'Web conference / virtual location',
            ],
            [
                'name' => 'Doxy.me',
                'description' => 'Web conference / virtual location',
            ],
            [
                'name' => 'Microsoft Teams',
                'description' => 'Web conference / virtual location',
            ],
        ];

        foreach ($locationTypes as $locationType) {
            \App\Models\LocationType::create($locationType);
        }
    }
}
