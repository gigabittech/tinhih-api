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
                'type' => 'person'
            ],
            [
                'name' => 'Phone call',
                'description' => 'Inbound or outbound calls',
                'type' => 'phone'


            ],
            [
                'name' => 'Telehealth video call',
                'description' => 'Set a virtual location',
                'type' => 'remote'

            ],
            [
                'name' => 'Google Meet',
                'description' => 'Web conference',
                'type' => 'remote'

            ],
            [
                'name' => 'Zoom',
                'description' => 'Web conference / virtual location',
                'type' => 'remote'

            ],
            [
                'name' => 'Doxy.me',
                'description' => 'Web conference / virtual location',
                'type' => 'remote'

            ],
            [
                'name' => 'Microsoft Teams',
                'description' => 'Web conference / virtual location',
                'type' => 'remote'

            ],
        ];

        foreach ($locationTypes as $locationType) {
            \App\Models\LocationType::create($locationType);
        }
    }
}
