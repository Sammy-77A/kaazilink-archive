<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProviderService;
use App\Models\ServiceArea;
use App\Models\KycSubmission;
use App\Data\ServiceDirectory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account
        User::firstOrCreate(
            ['email' => 'admin@kz.com'],
            [
                'name' => 'KaaziLink Admin',
                'phone' => '254700000000',
                'password' => Hash::make('1234'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Test Client Account
        User::firstOrCreate(
            ['email' => 'client@kz.com'],
            [
                'name' => 'Main Test Client',
                'phone' => '254711000000',
                'password' => Hash::make('1234'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );

        // Fetch area IDs by name
        $getAreaIds = function (array $names) {
            return ServiceArea::whereIn('name', $names)->pluck('id')->toArray();
        };

        $providers = [
            // --- ELECTRICIANS ---
            [
                'email' => 'eth@kz.com',
                'name' => 'Ethan Njuguna',
                'phone' => '254712345601',
                'category' => 'electrical',
                'bio' => 'EPRA certified residential & commercial electrician with 8+ years experience in domestic wiring, solar inverter setup, and fault diagnosis across Westlands & Kilimani.',
                'areas' => $getAreaIds(['Westlands', 'Kilimani', 'Lavington', 'Kileleshwa']),
                'services' => [
                    ['slug' => 'house-rewiring', 'price' => 15000],
                    ['slug' => 'socket-switch-installation', 'price' => 800],
                    ['slug' => 'lighting-fixture-fitting', 'price' => 1200],
                    ['slug' => 'circuit-breaker-repair', 'price' => 2500],
                ],
                'test_service' => [
                    ['slug' => 'test-service-electrical', 'custom_name' => 'Test Service – Electrical', 'price' => 1],
                ],
            ],
            [
                'email' => 'sam@kz.com',
                'name' => 'Samuel Waweru',
                'phone' => '254712345602',
                'category' => 'electrical',
                'bio' => 'Specialist in solar power system installation, backup inverter configurations, generator maintenance, and industrial electrical panel fitting.',
                'areas' => $getAreaIds(['Karen', 'Runda', 'Muthaiga', 'Gigiri']),
                'services' => [
                    ['slug' => 'solar-panel-installation', 'price' => 25000],
                    ['slug' => 'generator-maintenance', 'price' => 4500],
                    ['slug' => 'electrical-fault-diagnosis', 'price' => 2000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-electrical', 'custom_name' => 'Test Service – Electrical', 'price' => 1],
                ],
            ],
            [
                'email' => 'vic@kz.com',
                'name' => 'Victor Ochieng',
                'phone' => '254712345603',
                'category' => 'electrical',
                'bio' => 'Experienced electrician focused on smart home automation, security lighting installation, and heavy appliance connections.',
                'areas' => $getAreaIds(['Nyali', 'Bamburi', 'Kisauni', 'Mvita']),
                'services' => [
                    ['slug' => 'security-lighting-installation', 'price' => 3000],
                    ['slug' => 'appliance-wiring', 'price' => 1500],
                    ['slug' => 'socket-switch-installation', 'price' => 750],
                ],
                'test_service' => [
                    ['slug' => 'test-service-electrical', 'custom_name' => 'Test Service – Electrical', 'price' => 1],
                ],
            ],

            // --- PLUMBERS ---
            [
                'email' => 'jam@kz.com',
                'name' => 'James Kimani',
                'phone' => '254712345604',
                'category' => 'plumbing',
                'bio' => 'Master plumber with 10 years experience solving water pressure issues, solar water heater installations, pipe leak repairs, and full bathroom fitting.',
                'areas' => $getAreaIds(['South B', 'South C', 'Langata', 'Madaraka']),
                'services' => [
                    ['slug' => 'pipe-leak-repair', 'price' => 1800],
                    ['slug' => 'drain-unblocking', 'price' => 2200],
                    ['slug' => 'water-heater-installation', 'price' => 5000],
                    ['slug' => 'bathroom-fixture-fitting', 'price' => 3500],
                ],
                'test_service' => [
                    ['slug' => 'test-service-plumbing', 'custom_name' => 'Test Service – Plumbing', 'price' => 1],
                ],
            ],
            [
                'email' => 'dan@kz.com',
                'name' => 'Daniel Kiprop',
                'phone' => '254712345605',
                'category' => 'plumbing',
                'bio' => 'Plumbing specialist skilled in water tank fitting, booster pump repair, sewer line clearing, and emergency leak stopping.',
                'areas' => $getAreaIds(['Eldoret CBD', 'Huruma (Eldoret)', 'Langas', 'Kapseret']),
                'services' => [
                    ['slug' => 'water-tank-installation', 'price' => 8000],
                    ['slug' => 'booster-pump-repair', 'price' => 3500],
                    ['slug' => 'sink-toilet-repair', 'price' => 1500],
                ],
                'test_service' => [
                    ['slug' => 'test-service-plumbing', 'custom_name' => 'Test Service – Plumbing', 'price' => 1],
                ],
            ],
            [
                'email' => 'kev@kz.com',
                'name' => 'Kevin Mutua',
                'phone' => '254712345606',
                'category' => 'plumbing',
                'bio' => 'Reliable plumber providing clean tap installations, shower mixer fittings, underground pipe repairs, and septic tank drainage services.',
                'areas' => $getAreaIds(['Milimani (Nakuru)', 'Nakuru CBD', 'Kaptembwa', 'Free Area']),
                'services' => [
                    ['slug' => 'tap-mixer-replacement', 'price' => 1200],
                    ['slug' => 'underground-pipe-repair', 'price' => 6000],
                    ['slug' => 'drain-unblocking', 'price' => 2000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-plumbing', 'custom_name' => 'Test Service – Plumbing', 'price' => 1],
                ],
            ],

            // --- CARPENTERS ---
            [
                'email' => 'joh@kz.com',
                'name' => 'John Maina',
                'phone' => '254712345607',
                'category' => 'carpentry',
                'bio' => 'Custom woodworking craftsman specializing in modern MDF/hardwood kitchen cabinets, custom wardrobes, door repairs, and furniture assembly.',
                'areas' => $getAreaIds(['Ruaka', 'Thindigua', 'Kiambu Town', 'Kikuyu Town']),
                'services' => [
                    ['slug' => 'kitchen-cabinet-fitting', 'price' => 20000],
                    ['slug' => 'wardrobe-installation', 'price' => 18000],
                    ['slug' => 'door-lock-hinge-repair', 'price' => 1000],
                    ['slug' => 'furniture-assembly', 'price' => 2500],
                ],
                'test_service' => [
                    ['slug' => 'test-service-carpentry', 'custom_name' => 'Test Service – Carpentry', 'price' => 1],
                ],
            ],
            [
                'email' => 'pet@kz.com',
                'name' => 'Peter Kamau',
                'phone' => '254712345608',
                'category' => 'carpentry',
                'bio' => 'Experienced timber artisan proficient in roof truss construction, wooden flooring, pergolas, and custom dining table sets.',
                'areas' => $getAreaIds(['Milimani (Kisumu)', 'Kisumu CBD', 'Nyalenda', 'Kondele']),
                'services' => [
                    ['slug' => 'roof-truss-construction', 'price' => 35000],
                    ['slug' => 'wooden-flooring-installation', 'price' => 15000],
                    ['slug' => 'custom-furniture-making', 'price' => 12000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-carpentry', 'custom_name' => 'Test Service – Carpentry', 'price' => 1],
                ],
            ],
            [
                'email' => 'dav@kz.com',
                'name' => 'David Omwenga',
                'phone' => '254712345609',
                'category' => 'carpentry',
                'bio' => 'Interior carpentry specialist doing acoustic ceiling panels, gyproc partitioning frames, office desk assembly, and door lock replacements.',
                'areas' => $getAreaIds(['Roysambu', 'Kasarani', 'Kahawa West', 'Zimmerman']),
                'services' => [
                    ['slug' => 'door-lock-hinge-repair', 'price' => 1200],
                    ['slug' => 'gyproc-partitioning-frame', 'price' => 10000],
                    ['slug' => 'furniture-assembly', 'price' => 2000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-carpentry', 'custom_name' => 'Test Service – Carpentry', 'price' => 1],
                ],
            ],

            // --- PAINTERS ---
            [
                'email' => 'bri@kz.com',
                'name' => 'Brian Mwangi',
                'phone' => '254712345610',
                'category' => 'painting',
                'bio' => 'Professional interior & exterior decorator skilled in silk finishes, textured wall designs, water-proofing primer application, and wallpaper installation.',
                'areas' => $getAreaIds(['Parklands', 'Westlands', 'Lavington', 'Kilimani']),
                'services' => [
                    ['slug' => 'interior-wall-painting', 'price' => 8000],
                    ['slug' => 'exterior-building-painting', 'price' => 25000],
                    ['slug' => 'textured-wall-finish', 'price' => 12000],
                    ['slug' => 'wallpaper-installation', 'price' => 4000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-painting', 'custom_name' => 'Test Service – Painting', 'price' => 1],
                ],
            ],
            [
                'email' => 'cha@kz.com',
                'name' => 'Charles Otieno',
                'phone' => '254712345611',
                'category' => 'painting',
                'bio' => 'Expert house painter specializing in damp proofing treatments, epoxy floor coatings, timber varnish application, and roof painting.',
                'areas' => $getAreaIds(['Nyali', 'Bamburi', 'Shanzu', 'Mvita']),
                'services' => [
                    ['slug' => 'epoxy-floor-coating', 'price' => 18000],
                    ['slug' => 'damp-proofing-treatment', 'price' => 9000],
                    ['slug' => 'timber-varnish-staining', 'price' => 5000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-painting', 'custom_name' => 'Test Service – Painting', 'price' => 1],
                ],
            ],
            [
                'email' => 'geo@kz.com',
                'name' => 'George Njoroge',
                'phone' => '254712345612',
                'category' => 'painting',
                'bio' => 'Detail-oriented painter delivering flawless residential repaints, crown molding painting, gate painting, and budget-friendly apartment touch-ups.',
                'areas' => $getAreaIds(['Ruaka', 'Kiamumbi', 'Thika Town', 'Juja']),
                'services' => [
                    ['slug' => 'interior-wall-painting', 'price' => 7000],
                    ['slug' => 'gate-fence-metal-painting', 'price' => 3500],
                    ['slug' => 'wallpaper-installation', 'price' => 3500],
                ],
                'test_service' => [
                    ['slug' => 'test-service-painting', 'custom_name' => 'Test Service – Painting', 'price' => 1],
                ],
            ],

            // --- MASONRY ---
            [
                'email' => 'jos@kz.com',
                'name' => 'Joseph Kariuki',
                'phone' => '254712345613',
                'category' => 'masonry',
                'bio' => 'Seasoned mason with 12+ years expertise in ceramic & porcelain floor tiling, stone pitching, boundary wall construction, and plastering.',
                'areas' => $getAreaIds(['Karen', 'Runda', 'Lavington', 'Kitisuru']),
                'services' => [
                    ['slug' => 'floor-wall-tiling', 'price' => 12000],
                    ['slug' => 'boundary-wall-construction', 'price' => 45000],
                    ['slug' => 'wall-plastering-screeding', 'price' => 8500],
                    ['slug' => 'cabro-paving-installation', 'price' => 15000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-masonry', 'custom_name' => 'Test Service – Masonry', 'price' => 1],
                ],
            ],
            [
                'email' => 'pat@kz.com',
                'name' => 'Patrick Mutiso',
                'phone' => '254712345614',
                'category' => 'masonry',
                'bio' => 'Masonry contractor skilled in granite countertop fitting, concrete foundation slab pouring, paving block installation, and structural repairs.',
                'areas' => $getAreaIds(['South B', 'South C', 'Syokimau', 'Kitengela']),
                'services' => [
                    ['slug' => 'granite-countertop-fitting', 'price' => 16000],
                    ['slug' => 'concrete-slab-pouring', 'price' => 30000],
                    ['slug' => 'floor-wall-tiling', 'price' => 10000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-masonry', 'custom_name' => 'Test Service – Masonry', 'price' => 1],
                ],
            ],
            [
                'email' => 'emm@kz.com',
                'name' => 'Emmanuel Kibet',
                'phone' => '254712345615',
                'category' => 'masonry',
                'bio' => 'Mason specializing in stone cladding, chimney construction, terrazzo polishing, and foundation underpinning.',
                'areas' => $getAreaIds(['Eldoret CBD', 'Elgon View (Eldoret)', 'Kapseret', 'Huruma (Eldoret)']),
                'services' => [
                    ['slug' => 'stone-cladding-installation', 'price' => 14000],
                    ['slug' => 'terrazzo-polishing-repair', 'price' => 9000],
                    ['slug' => 'wall-plastering-screeding', 'price' => 7500],
                ],
                'test_service' => [
                    ['slug' => 'test-service-masonry', 'custom_name' => 'Test Service – Masonry', 'price' => 1],
                ],
            ],

            // --- APPLIANCE REPAIR ---
            [
                'email' => 'den@kz.com',
                'name' => 'Dennis Odhiambo',
                'phone' => '254712345616',
                'category' => 'appliance-repair',
                'bio' => 'Specialized technician fixing automatic washing machines, domestic refrigerators, microwave ovens, and electric cookers across Nairobi estates.',
                'areas' => $getAreaIds(['Kilimani', 'Westlands', 'Kileleshwa', 'South C']),
                'services' => [
                    ['slug' => 'washing-machine-repair', 'price' => 3500],
                    ['slug' => 'refrigerator-repair-re-gassing', 'price' => 4500],
                    ['slug' => 'microwave-repair', 'price' => 1800],
                    ['slug' => 'electric-cooker-oven-repair', 'price' => 3000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-appliance-repair', 'custom_name' => 'Test Service – Appliance Repair', 'price' => 1],
                ],
            ],
            [
                'email' => 'ste@kz.com',
                'name' => 'Stephen Mboya',
                'phone' => '254712345617',
                'category' => 'appliance-repair',
                'bio' => 'HVAC & refrigeration specialist handling inverter AC unit installation, commercial fridge servicing, cold room maintenance, and water dispenser fixes.',
                'areas' => $getAreaIds(['Nyali', 'Mvita', 'Bamburi', 'Kisauni']),
                'services' => [
                    ['slug' => 'air-conditioner-installation-service', 'price' => 6000],
                    ['slug' => 'commercial-refrigerator-repair', 'price' => 8000],
                    ['slug' => 'water-dispenser-repair', 'price' => 2000],
                ],
                'test_service' => [
                    ['slug' => 'test-service-appliance-repair', 'custom_name' => 'Test Service – Appliance Repair', 'price' => 1],
                ],
            ],
            [
                'email' => 'fra@kz.com',
                'name' => 'Francis Wambua',
                'phone' => '254712345618',
                'category' => 'appliance-repair',
                'bio' => 'Electronics & home appliance expert fixing LED/Smart TVs, audio home theater systems, dishwasher units, and automatic washing machines.',
                'areas' => $getAreaIds(['Milimani (Nakuru)', 'Nakuru CBD', 'Free Area', 'Kaptembwa']),
                'services' => [
                    ['slug' => 'tv-mounting-repair', 'price' => 2200],
                    ['slug' => 'dishwasher-repair', 'price' => 4000],
                    ['slug' => 'washing-machine-repair', 'price' => 3200],
                ],
                'test_service' => [
                    ['slug' => 'test-service-appliance-repair', 'custom_name' => 'Test Service – Appliance Repair', 'price' => 1],
                ],
            ],
        ];

        $directoryServices = ServiceDirectory::getServices();

        foreach ($providers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('1234'),
                    'role' => 'provider',
                    'bio' => $data['bio'],
                    'kyc_status' => 'approved',
                    'email_verified_at' => now(),
                ]
            );

            // Sync Service Areas
            if (!empty($data['areas'])) {
                $user->serviceAreas()->syncWithoutDetaching($data['areas']);
            }

            // Sync/Create Standard Provider Services
            foreach ($data['services'] as $srv) {
                $matchedSrv = null;
                foreach ($directoryServices as $s) {
                    if ($s['slug'] === $srv['slug']) {
                        $matchedSrv = $s;
                        break;
                    }
                }

                ProviderService::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'service_slug' => $srv['slug'],
                    ],
                    [
                        'custom_name' => $matchedSrv ? $matchedSrv['name'] : $srv['slug'],
                        'custom_description' => $matchedSrv ? 'Professional ' . ($matchedSrv ? $matchedSrv['name'] : $srv['slug']) . ' service' : 'Professional service',
                        'price' => $srv['price'],
                        'is_custom' => false,
                    ]
                );
            }

            // Create 1 KES Test Service
            foreach ($data['test_service'] as $testSrv) {
                ProviderService::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'service_slug' => $testSrv['slug'],
                    ],
                    [
                        'custom_name' => $testSrv['custom_name'],
                        'custom_description' => '1 KES test service for M-Pesa payment validation',
                        'price' => $testSrv['price'],
                        'is_custom' => true,
                    ]
                );
            }

            // Approved KYC Record
            KycSubmission::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id_front_path' => 'kyc/dummy_id_front.jpg',
                    'id_back_path' => 'kyc/dummy_id_back.jpg',
                    'kra_pin_path' => 'kyc/dummy_kra_pin.pdf',
                    'status' => 'approved',
                ]
            );
        }
    }
}
